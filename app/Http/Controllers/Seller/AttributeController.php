<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\AttributeTranslation;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\AttributeCategory;
use Illuminate\Support\Facades\Schema;
use CoreComponentRepository;
use Str;
use App;

class AttributeController extends Controller
{
    /**
     * Display a listing of the seller's custom attributes.
     */
    public function index()
    {
        $attributes = Attribute::where('user_id', auth()->id())
            ->with(['attribute_values', 'categories'])
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

        return view('seller.product.attributes.index', compact('attributes', 'categories'));
    }

    /**
     * Store a newly created attribute for the seller.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:500',
            'categories' => 'required'
        ]);

        $attribute = new Attribute;
        $attribute->name = $request->name;
        $attribute->user_id = auth()->id();
        $attribute->save();

        if (!empty($request->categories)) {
            $categories_arr = [];
            foreach ($request->categories as $category_val) {
                $categories_arr[] = [
                    'category_id' => $category_val,
                    'attribute_id' => $attribute->id
                ];
            }
            AttributeCategory::insert($categories_arr);
        }

        $attribute_translation = AttributeTranslation::firstOrNew([
            'lang' => env('DEFAULT_LANGUAGE', 'en'),
            'attribute_id' => $attribute->id
        ]);
        $attribute_translation->name = $request->name;
        $attribute_translation->save();

        flash(translate('Attribute has been inserted successfully'))->success();
        return redirect()->route('seller.attributes.index');
    }

    /**
     * Show the form for editing the specified attribute.
     */
    public function edit(Request $request, $id)
    {
        $lang = $request->lang ?? env('DEFAULT_LANGUAGE', 'en');
        $attribute = Attribute::where('user_id', auth()->id())->findOrFail($id);
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        $AttributeCategory = AttributeCategory::where('attribute_id', $id)->pluck('category_id')->toArray();

        return view('seller.product.attributes.edit', compact('attribute', 'lang', 'AttributeCategory', 'categories'));
    }

    /**
     * Update the specified attribute in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'       => 'required|string|max:500',
            'categories' => 'required'
        ]);

        $attribute = Attribute::where('user_id', auth()->id())->findOrFail($id);
        
        if ($request->lang == env("DEFAULT_LANGUAGE", "en")) {
            $attribute->name = $request->name;
        }
        $attribute->save();

        AttributeCategory::where('attribute_id', $attribute->id)->delete();

        if (!empty($request->categories)) {
            $categories_arr = [];
            foreach ($request->categories as $category_val) {
                $categories_arr[] = [
                    'category_id' => $category_val,
                    'attribute_id' => $attribute->id
                ];
            }
            AttributeCategory::insert($categories_arr);
        }

        $attribute_translation = AttributeTranslation::firstOrNew([
            'lang' => $request->lang,
            'attribute_id' => $attribute->id
        ]);
        $attribute_translation->name = $request->name;
        $attribute_translation->save();

        flash(translate('Attribute has been updated successfully'))->success();
        return back();
    }

    /**
     * Remove the specified attribute from storage.
     */
    public function destroy($id)
    {
        $attribute = Attribute::where('user_id', auth()->id())->findOrFail($id);

        foreach ($attribute->attribute_translations as $attribute_translation) {
            $attribute_translation->delete();
        }

        AttributeCategory::where('attribute_id', $attribute->id)->delete();
        AttributeValue::where('attribute_id', $attribute->id)->delete();
        $attribute->delete();

        flash(translate('Attribute has been deleted successfully'))->success();
        return redirect()->route('seller.attributes.index');
    }

    /**
     * AJAX: Store a new attribute value inline.
     */
    public function ajax_store_attribute_value(Request $request)
    {
        $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string|max:255',
        ]);

        // Ensure the seller owns the attribute
        $attribute = Attribute::where('user_id', auth()->id())->findOrFail($request->attribute_id);

        $attribute_value = new AttributeValue;
        $attribute_value->attribute_id = $request->attribute_id;
        $attribute_value->value = ucfirst($request->value);
        $attribute_value->save();

        return response()->json([
            'success' => true,
            'id' => $attribute_value->id,
            'value' => $attribute_value->value,
        ]);
    }

    /**
     * AJAX: Delete an attribute value inline.
     */
    public function ajax_destroy_attribute_value($id)
    {
        $attribute_value = AttributeValue::findOrFail($id);
        
        // Ensure the seller owns the parent attribute
        $attribute = Attribute::where('user_id', auth()->id())->findOrFail($attribute_value->attribute_id);
        $attribute_value->delete();

        return response()->json(['success' => true]);
    }
}
