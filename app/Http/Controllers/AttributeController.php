<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\Color;
use App\Models\AttributeTranslation;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\AttributeCategory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use CoreComponentRepository;
use Str; 
class AttributeController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:view_product_attributes'])->only('index');
        $this->middleware(['permission:edit_product_attribute'])->only('edit');
        $this->middleware(['permission:delete_product_attribute'])->only('destroy');

        $this->middleware(['permission:view_product_attribute_values'])->only('show');
        $this->middleware(['permission:edit_product_attribute_value'])->only('edit_attribute_value');
        $this->middleware(['permission:delete_product_attribute_value'])->only('destroy_attribute_value');

        $this->middleware(['permission:view_colors'])->only('colors');
        $this->middleware(['permission:edit_color'])->only('edit_color');
        $this->middleware(['permission:delete_color'])->only('destroy_color');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        CoreComponentRepository::instantiateShopRepository();
        CoreComponentRepository::initializeCache();
        $attributes = Attribute::with(['attribute_values', 'categories'])->orderBy('created_at', 'desc')->get();
        $categories = Category::where('parent_id', 0)->where('digital', 0)->with('childrenCategories')->get();
        return view('backend.product.attribute.index', compact('attributes','categories'));
    }

    /**
     * AJAX: Store a new attribute value inline.
     */
    public function ajax_store_attribute_value(Request $request)
    {
        $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        ]);

        $attribute_value = new AttributeValue;
        $attribute_value->attribute_id = $request->attribute_id;
        $attribute_value->value = ucfirst($request->value);
        if ($this->canSaveAttributeValueImage($request)) {
            $attribute_value->image = $this->storeAttributeValueImage($request);
        }
        $attribute_value->save();

        return response()->json([
            'success' => true,
            'id' => $attribute_value->id,
            'value' => $attribute_value->value,
            'image' => $attribute_value->image,
            'image_url' => $attribute_value->image ? my_asset($attribute_value->image) : '',
        ]);
    }

    /**
     * AJAX: Update an existing attribute value image inline.
     */
    public function ajax_update_attribute_value_image(Request $request, $id)
{
    $request->validate([
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        'value' => 'nullable|string|max:255',
    ]);

    $this->ensureAttributeValueImageColumn();

    $attribute_value = AttributeValue::findOrFail($id);

    // Attribute::where('user_id', auth()->id())
        // ->findOrFail($attribute_value->attribute_id);

    // Update image only if a new image was uploaded
    if ($request->hasFile('image')) {
        $attribute_value->image = $this->storeAttributeValueImage($request);
    }

    // Update value only if provided
    if ($request->filled('value')) {
        $attribute_value->value = ucfirst($request->value);
    }

    $attribute_value->save();
    flash(translate('Attribute has been update successfully'))->success();

    return response()->json([
        'success'   => true,
        'value'     => $attribute_value->value,
        'image'     => $attribute_value->image,
        'image_url' => $attribute_value->image
            ? my_asset($attribute_value->image)
            : '',
    ]);
}
    /**
     * AJAX: Delete an attribute value inline.
     */
    public function ajax_destroy_attribute_value($id)
    {
        $attribute_value = AttributeValue::findOrFail($id);
        $attribute_value->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name'     => 'required|string|max:500',
            'categories'  => 'required'
        ];
        
        $attribute = new Attribute;
        $attribute->name = $request->name;
        $attribute->save();
        $categories_arr = [];
        if(!empty($request->categories)){
            $categories = $request->categories;
            foreach($categories as $categories_val){
                $categories_arr[] = array('category_id' => $categories_val, 'attribute_id' => $attribute->id);
            }
            AttributeCategory::insert($categories_arr);
        } 
        $attribute_translation = AttributeTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'attribute_id' => $attribute->id]);
        $attribute_translation->name = $request->name;
        $attribute_translation->save();

        flash(translate('Attribute has been inserted successfully'))->success();
        return redirect()->route('attributes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['attribute'] = Attribute::findOrFail($id);
        $data['all_attribute_values'] = AttributeValue::with('attribute')->where('attribute_id', $id)->get();

        return view("backend.product.attribute.attribute_value.index", $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $lang      = $request->lang;
        $attribute = Attribute::findOrFail($id);
        $categories = Category::where('parent_id', 0)->where('digital', 0)->with('childrenCategories')->get();
        $AttributeCategory = AttributeCategory::where('attribute_id', $id)->pluck('category_id')->toArray();
        return view('backend.product.attribute.edit', compact('attribute','lang','AttributeCategory','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $attribute = Attribute::findOrFail($id);
        if($request->lang == env("DEFAULT_LANGUAGE")){
          $attribute->name = $request->name;
        }
        $attribute->save();
        
        AttributeCategory::where('attribute_id', $attribute->id)->delete();

        if (!empty($request->categories)) {
            $categories = $request->categories;
            $categories_arr = [];
            foreach ($categories as $categories_val) {
                $categories_arr[] = array('category_id' => $categories_val, 'attribute_id' => $attribute->id);
            }

            AttributeCategory::insert($categories_arr);
        }
        
        $attribute_translation = AttributeTranslation::firstOrNew(['lang' => $request->lang, 'attribute_id' => $attribute->id]);
        $attribute_translation->name = $request->name;
        $attribute_translation->save();

        flash(translate('Attribute has been updated successfully'))->success();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attribute = Attribute::findOrFail($id);

        foreach ($attribute->attribute_translations as $key => $attribute_translation) {
            $attribute_translation->delete();
        }

        Attribute::destroy($id);
        flash(translate('Attribute has been deleted successfully'))->success();
        return redirect()->route('attributes.index');

    }

    public function store_attribute_value(Request $request)
    {
        $attribute_value = new AttributeValue;
        $attribute_value->attribute_id = $request->attribute_id;
        $attribute_value->value = ucfirst($request->value);
        if ($this->canSaveAttributeValueImage($request)) {
            $attribute_value->image = $this->storeAttributeValueImage($request);
        }
        $attribute_value->save();

        flash(translate('Attribute value has been inserted successfully'))->success();
        return redirect()->route('attributes.show', $request->attribute_id);
    }

    public function edit_attribute_value(Request $request, $id)
    {
        $attribute_value = AttributeValue::findOrFail($id);
        return view("backend.product.attribute.attribute_value.edit", compact('attribute_value'));
    }

    public function update_attribute_value(Request $request, $id)
    {
        $attribute_value = AttributeValue::findOrFail($id);
        
        $attribute_value->attribute_id = $request->attribute_id;
        $attribute_value->value = ucfirst($request->value);
        if ($this->canSaveAttributeValueImage($request)) {
            $attribute_value->image = $this->storeAttributeValueImage($request);
        }
        
        $attribute_value->save();

        flash(translate('Attribute value has been updated successfully'))->success();
        return back();
    }

    public function destroy_attribute_value($id)
    {
        $attribute_values = AttributeValue::findOrFail($id);
        AttributeValue::destroy($id);
        
        flash(translate('Attribute value has been deleted successfully'))->success();
        return redirect()->route('attributes.show', $attribute_values->attribute_id);

    }

    private function storeAttributeValueImage(Request $request): ?string
    {
        if (!$request->hasFile('image')) {
            return null;
        }

        $dir = public_path('attribute');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $file = $request->file('image');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $filename);

        return 'attribute/' . $filename;
    }

    private function canSaveAttributeValueImage(Request $request): bool
    {
        if (!$request->hasFile('image')) {
            return false;
        }

        $this->ensureAttributeValueImageColumn();

        return true;
    }

    private function ensureAttributeValueImageColumn(): void
    {
        if (!Schema::hasColumn('attribute_values', 'image')) {
            throw ValidationException::withMessages([
                'image' => translate('Missing database column: attribute_values.image'),
            ]);
        }
    }
    
    public function colors(Request $request) {
        $sort_search = null;
        $colors = Color::orderBy('created_at', 'desc');

        if ($request->search != null){
            $colors = $colors->where('name', 'like', '%'.$request->search.'%');
            $sort_search = $request->search;
        }
        $colors = $colors->paginate(10);

        return view('backend.product.color.index', compact('colors', 'sort_search'));
    }
    
    public function store_color(Request $request) {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:colors|max:255',
        ]);
        $color = new Color;
        $color->name = Str::replace(' ', '', $request->name);
        $color->code = $request->code;
        
        $color->save();

        flash(translate('Color has been inserted successfully'))->success();
        return redirect()->route('colors');
    }
    
    public function edit_color(Request $request, $id)
    {
        $color = Color::findOrFail($id);
        return view('backend.product.color.edit', compact('color'));
    }

    /**
     * Update the color.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_color(Request $request, $id)
    {
        $color = Color::findOrFail($id);

        $request->validate([
            'code' => 'required|unique:colors,code,'.$color->id,
        ]);
        
        $color->name = Str::replace(' ', '', $request->name);
        $color->code = $request->code;
        
        $color->save();

        flash(translate('Color has been updated successfully'))->success();
        return back();
    }
    
    public function destroy_color($id)
    {
        Color::destroy($id);
        
        flash(translate('Color has been deleted successfully'))->success();
        return redirect()->route('colors');

    }
   

    public function getAttributesByCategories(Request $request)
    {
        $categoryIds = (array) $request->input('category_ids');
        $parentIds = getParentCategoryIds($categoryIds);
        
        // 1. Fetch global/admin attributes and seller's custom attributes from the attributes table
        $dbAttributes = Attribute::where(function ($query) use ($parentIds) {
            $query->whereHas('categories', function ($categoryQuery) use ($parentIds) {
                $categoryQuery->whereIn('category_id', $parentIds);
            })
            ->orWhereRaw('LOWER(name) = ?', ['size']);
        })
        ->where(function ($query) {
            $query->whereNull('user_id')
                ->orWhere('user_id', auth()->id());
        })
        ->get();
        
        // 2. Fetch custom template attributes of this seller from product_stock_attributes
        $customAttributes = \App\Models\ProductStockAttribute::where('user_id', auth()->id())
            ->whereIn('category_id', $parentIds)
            ->whereNull('product_id')
            ->get()
            ->unique('attribute_name');
            
        $attributes = collect();
        foreach ($dbAttributes as $attr) {
            $attributes->push((object) [
                'id' => $attr->id,
                'name' => $attr->getTranslation('name'),
                'user_id' => $attr->user_id
            ]);
        }
        
        foreach ($customAttributes as $attr) {
            $pseudoId = -abs(crc32($attr->attribute_name));
            // Check if name is already added
            if (!$attributes->contains('name', $attr->attribute_name)) {
                $attributes->push((object) [
                    'id' => $pseudoId,
                    'name' => $attr->attribute_name,
                    'user_id' => auth()->id()
                ]);
            }
        }
    
        return response()->json($attributes->values()->all());
    }
}