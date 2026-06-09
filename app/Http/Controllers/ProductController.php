<?php

namespace App\Http\Controllers;

use AizPackages\CombinationGenerate\Services\CombinationService;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\Category;
use App\Models\ProductTax;
use App\Models\AttributeValue;
use App\Models\Cart;
use App\Models\Wishlist;
use App\Models\User;
use App\Notifications\ShopProductNotification;
use Carbon\Carbon;
use Combinations;
use CoreComponentRepository;
use Artisan;
use Cache;
use Str;
use App\Services\ProductService;
use App\Services\ProductTaxService;
use App\Services\ProductFlashDealService;
use App\Services\ProductStockService;
use Illuminate\Support\Facades\Notification;

class ProductController extends Controller
{
    protected $productService;
    protected $productTaxService;
    protected $productFlashDealService;
    protected $productStockService;

    public function __construct(
        ProductService $productService,
        ProductTaxService $productTaxService,
        ProductFlashDealService $productFlashDealService,
        ProductStockService $productStockService
    ) {
        $this->productService = $productService;
        $this->productTaxService = $productTaxService;
        $this->productFlashDealService = $productFlashDealService;
        $this->productStockService = $productStockService;

        // Staff Permission Check
        $this->middleware(['permission:add_new_product'])->only('create');
        $this->middleware(['permission:show_all_products'])->only('all_products');
        $this->middleware(['permission:show_in_house_products'])->only('admin_products');
        $this->middleware(['permission:show_seller_products'])->only('seller_products');
        $this->middleware(['permission:product_edit'])->only('admin_product_edit', 'seller_product_edit');
        $this->middleware(['permission:product_duplicate'])->only('duplicate');
        $this->middleware(['permission:product_delete'])->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_products(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();

        $type = 'In House';
        $col_name = null;
        $query = null;
        $sort_search = null;

        $products = Product::where('added_by', 'admin')->where('auction_product', 0)->where('wholesale_product', 0);

        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }
        if ($request->search != null) {
            $sort_search = $request->search;
            $products = $products
                ->where('name', 'like', '%' . $sort_search . '%')
                ->orWhereHas('stocks', function ($q) use ($sort_search) {
                    $q->where('sku', 'like', '%' . $sort_search . '%');
                });
        }

        $products = $products->where('digital', 0)->orderBy('created_at', 'desc')->paginate(15);

        return view('backend.product.products.index', compact('products', 'type', 'col_name', 'query', 'sort_search'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function seller_products(Request $request, $product_type)
{
    $col_name    = null;
    $query       = null;
    $seller_id   = $request->user_id ?? null;
    $sort_search = null;
    $category_id = $request->category_id ?? null;

    // ===== BASE QUERY =====
    $products = Product::where('added_by', 'seller')
        ->where('auction_product', 0)
        ->where('wholesale_product', 0);

    // ===== SELLER FILTER =====
    // 0 means "All Sellers" so ignore it
    if ($request->filled('user_id') && $request->user_id != 0) {
        $products->where('user_id', $request->user_id);
    }

    // ===== CATEGORY FILTER (Parent + Child Support) =====
    if ($request->filled('category_id')) {
        $categoryIds = $this->getAllCategoryIds($request->category_id);

        $products->whereHas('categories', function ($q) use ($categoryIds) {
            $q->whereIn('category_id', $categoryIds);
        });
    }

    // ===== SEARCH FILTER =====
    if ($request->filled('search')) {
        $products->where('name', 'like', '%' . $request->search . '%');
        $sort_search = $request->search;
    }

    // ===== SORT FILTER =====
    if ($request->filled('type')) {
        $var = explode(",", $request->type);

        if (count($var) == 2) {
            $col_name = $var[0];
            $query    = $var[1];
            $products->orderBy($col_name, $query);
        }
    }

    // ===== DIGITAL / PHYSICAL FILTER =====
    if ($product_type == 'physical') {
        $products->where('digital', 0);
    } else {
        $products->where('digital', 1);
    }

    // ===== FINAL PAGINATION =====
    $products = $products->orderBy('created_at', 'desc')->paginate(15);

    $type = 'Seller';

    // ===== RETURN VIEW =====
    if ($product_type == 'digital') {
        return view('backend.product.digital_products.index',
            compact('products', 'sort_search', 'type', 'category_id', 'seller_id'));
    } 
    return view('backend.product.products.index',
        compact('products', 'type', 'col_name', 'query', 'seller_id', 'sort_search', 'category_id'));
}

public function all_products(Request $request)
{
    $col_name = null;
    $query = null;
    $seller_id = null;
    $brand_id = null;
    $sort_search = null;
    $type = 'All';
    $category_id = $request->category_id;

    $products = Product::where('auction_product', 0)
                        ->where('wholesale_product', 0);

    // ===== CATEGORY FILTER =====
    if ($request->filled('category_id')) {
        $categoryIds = $this->getAllCategoryIds($request->category_id);
        $products->whereHas('categories', function ($q) use ($categoryIds) {
            $q->whereIn('category_id', $categoryIds);
        });
    }

    // ===== SELLER FILTER =====
    if ($request->filled('user_id')) {
        $products->where('user_id', $request->user_id);
        $seller_id = $request->user_id;
    }

    // ===== BRAND FILTER =====
    if ($request->filled('brand')) {
        $products->where('brand_id', $request->brand);
        $brand_id = $request->brand;
    }

    // ===== SEARCH =====
    if ($request->filled('search')) {
        $sort_search = $request->search;
        $products->where('name', 'like', '%' . $sort_search . '%');
    }

    // ===== SORT =====
    if ($request->filled('type')) {
        $var = explode(",", $request->type);
        $col_name = $var[0];
        $query = $var[1];
        $products = $products->orderBy($col_name, $query);
    }

    // Default order
    $products = $products->orderBy('created_at', 'desc')->paginate(15);

    return view('backend.product.products.index', compact(
        'products',
        'type',
        'col_name',
        'query',
        'seller_id',
        'brand_id',
        'sort_search',
        'category_id'
    ));
}

// ===== HELPER FUNCTION =====
private function getAllCategoryIds($categoryId)
{
    $ids = collect([$categoryId]);

    $children = Category::where('parent_id', $categoryId)->pluck('id');

    foreach ($children as $childId) {
        $ids = $ids->merge($this->getAllCategoryIds($childId));
    }

    return $ids;
}

public function exportExcel()
{
  $products = \App\Models\Product::with('user.shop')->get();
    $filename = "all_sellers_products.csv";

    $headers = [
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=$filename",
    ];

    $columns = ['Product ID', 'Product Name', 'Seller Name', 'Price'];

    $callback = function() use($products, $columns) {

        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        foreach ($products as $product) {

            if ($product->added_by == 'seller') {
                $sellerName = optional($product->shop)->name;
            } else {
                $sellerName = 'Admin';
            }

            fputcsv($file, [
                $product->id,
                $product->name,
                $sellerName,
                $product->unit_price,
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
public function exportPdf(Request $request)
{
    $query = \App\Models\Product::with('user.shop');

    // Seller filter
    if ($request->filled('user_id')) {
        $query->where('user_id', $request->user_id);
    }

    $products = $query->get();

    $totalProducts = $products->count();
    $exportDate = now()->format('d-m-Y h:i A');

    $html = "
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header {
            width: 100%;
            margin-bottom: 15px;
        }

        .header h2 {
            text-align: center;
            margin: 0;
        }

        .export-date {
            text-align: right;
            font-size: 11px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
        }

        .price-column {
            text-align: right;
            width: 100px;
        }

        .footer {
            margin-top: 10px;
            font-weight: bold;
        }
    </style>

    <div class='header'>
        <div class='export-date'>Exported on: {$exportDate}</div>
        <h2>All Sellers Product List</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>Seller Name</th>
                <th>Listed Date</th>
                <th class='price-column'>Price</th>
            </tr>
        </thead>
        <tbody>
    ";

    foreach ($products as $product) {

        if ($product->added_by == 'seller') {
            $sellerName = optional(optional($product->user)->shop)->name ?? 'N/A';
        } else {
            $sellerName = 'Admin';
        }

        $price = number_format((float)$product->unit_price, 2, '.', '');

        $listedDate = $product->created_at
                        ? $product->created_at->format('d-m-Y')
                        : 'N/A';

        $html .= "
            <tr>
                <td>{$product->id}</td>
                <td>{$product->name}</td>
                <td>{$sellerName}</td>
                <td>{$listedDate}</td>
                <td class='price-column'>£ {$price}</td>
            </tr>
        ";
    }

    $html .= "
        </tbody>
    </table>

    <div class='footer'>
        Total Products: {$totalProducts}
    </div>
    ";

    $pdf = \PDF::loadHTML($html);

    return $pdf->download('all_sellers_products.pdf');
}
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        CoreComponentRepository::initializeCache();

        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

        return view('backend.product.products.create', compact('categories'));
    }

    public function add_more_choice_option(Request $request)
    {
        $all_attribute_values = AttributeValue::with('attribute')->where('attribute_id', $request->attribute_id)->get();

        $html = '';

        foreach ($all_attribute_values as $row) {
            $html .= '<option value="' . $row->value . '">' . $row->value . '</option>';
        }

        echo json_encode($html);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $product = $this->productService->store($request->except([
            '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]));
        $request->merge(['product_id' => $product->id]);

        //Product categories
        $product->categories()->attach($request->category_ids);

        //VAT & Tax
        if ($request->tax_id) {
            $this->productTaxService->store($request->only([
                'tax_id', 'tax', 'tax_type', 'product_id'
            ]));
        }

        //Flash Deal
        $this->productFlashDealService->store($request->only([
            'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]), $product);

        //Product Stock
        $this->productStockService->store($request->only([
            'colors_active', 'colors', 'choice_no', 'unit_price', 'sku', 'current_stock', 'product_id'
        ]), $product);

        // Product Translations
        $request->merge(['lang' => env('DEFAULT_LANGUAGE')]);
        ProductTranslation::create($request->only([
            'lang', 'name', 'unit', 'description', 'product_id'
        ]));

        flash(translate('Product has been inserted successfully'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return redirect()->route('products.admin');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function admin_product_edit(Request $request, $id)
    {
        CoreComponentRepository::initializeCache();

        $product = Product::findOrFail($id);
        if ($product->digital == 1) {
            return redirect('admin/digitalproducts/' . $id . '/edit');
        }

        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        return view('backend.product.products.edit', compact('product', 'categories', 'tags', 'lang'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function seller_product_edit(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        if ($product->digital == 1) {
            return redirect('digitalproducts/' . $id . '/edit');
        }
        $lang = $request->lang;
        $tags = json_decode($product->tags);
        // $categories = Category::all();
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

        return view('backend.product.products.edit', compact('product', 'categories', 'tags', 'lang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
       
        //Product
        $product = $this->productService->update($request->except([
            '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]), $product);

        $request->merge(['product_id' => $product->id]);

        //Product categories
        $product->categories()->sync($request->category_ids);

        //Product Stock
        $product->stocks()->delete();
        $this->productStockService->store($request->only([
            'colors_active', 'colors', 'choice_no', 'unit_price', 'sku', 'current_stock', 'product_id'
        ]), $product);

        //Flash Deal
        $this->productFlashDealService->store($request->only([
            'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]), $product);

        //VAT & Tax
        if ($request->tax_id) {
            $product->taxes()->delete();
            $this->productTaxService->store($request->only([
                'tax_id', 'tax', 'tax_type', 'product_id'
            ]));
        }

        // Product Translations
        ProductTranslation::updateOrCreate(
            $request->only([
                'lang', 'product_id'
            ]),
            $request->only([
                'name', 'unit', 'description'
            ])
        );

        flash(translate('Product has been updated successfully'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');

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
        $product = Product::findOrFail($id);

        $product->product_translations()->delete();
        $product->categories()->detach();
        $product->stocks()->delete();
        $product->taxes()->delete();

        if (Product::destroy($id)) {
            Cart::where('product_id', $id)->delete();
            Wishlist::where('product_id', $id)->delete();

            flash(translate('Product has been deleted successfully'))->success();

            Artisan::call('view:clear');
            Artisan::call('cache:clear');

            return back();
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function bulk_product_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $product_id) {
                $this->destroy($product_id);
            }
        }

        return 1;
    }

    /**
     * Duplicates the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function duplicate(Request $request, $id)
    {
        $product = Product::find($id);

        //Product
        $product_new = $this->productService->product_duplicate_store($product);

        //Product Stock
        $this->productStockService->product_duplicate_store($product->stocks, $product_new);

        //VAT & Tax
        $this->productTaxService->product_duplicate_store($product->taxes, $product_new);

        flash(translate('Product has been duplicated successfully'))->success();
        if ($request->type == 'In House')
            return redirect()->route('products.admin');
        elseif ($request->type == 'Seller')
            return redirect()->route('products.seller');
        elseif ($request->type == 'All')
            return redirect()->route('products.all');
    }

    public function get_products_by_brand(Request $request)
    {
        $products = Product::where('brand_id', $request->brand_id)->get();
        return view('partials.product_select', compact('products'));
    }

    public function updateTodaysDeal(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->todays_deal = $request->status;
        $product->save();
        Cache::forget('todays_deal_products');
        return 1;
    }

    public function updatePublished(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->published = $request->status;

        if ($product->added_by == 'seller' && addon_is_activated('seller_subscription') && $request->status == 1) {
            $shop = $product->user->shop;
            if (
                $shop->package_invalid_at == null
                || Carbon::now()->diffInDays(Carbon::parse($shop->package_invalid_at), false) < 0
                || $shop->product_upload_limit <= $shop->user->products()->where('published', 1)->count()
            ) {
                return 0;
            }
        }

        $product->save();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return 1;
    }

    public function updateProductApproval(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->approved = $request->approved;

        if ($product->added_by == 'seller' && addon_is_activated('seller_subscription')) {
            $shop = $product->user->shop;
            if (
                $shop->package_invalid_at == null
                || Carbon::now()->diffInDays(Carbon::parse($shop->package_invalid_at), false) < 0
                || $shop->product_upload_limit <= $shop->user->products()->where('published', 1)->count()
            ) {
                return 0;
            }
        }

        $product->save();

        $product_type   = $product->digital ==  0 ? 'physical' : 'digital';
        $status         = $request->approved == 1 ? 'approved' : 'rejected';
        $users          = User::findMany([User::where('user_type', 'admin')->first()->id, $product->user_id]);
        Notification::send($users, new ShopProductNotification($product_type, $product, $status));

        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return 1;
    }

    public function updateFeatured(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->featured = $request->status;
        if ($product->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

    public function sku_combination(Request $request)
    {
        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $unit_price = $request->unit_price;
        $product_name = $request->name;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                if (isset($request[$name])) {
                    $data = array();
                    foreach ($request[$name] as $key => $item) {
                        array_push($data, $item);
                    }
                    array_push($options, $data);
                }
            }
        }

        $combinations = array();
        foreach ($options as $option_group) {
            if (is_array($option_group)) {
                foreach ($option_group as $value) {
                    $combinations[] = [$value];
                }
            }
        }

        return view('backend.product.products.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
    }

    public function sku_combination_edit(Request $request)
    {
        $product = Product::findOrFail($request->id);

        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $product_name = $request->name;
        $unit_price = $request->unit_price;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                if (isset($request[$name])) {
                    $data = array();
                    foreach ($request[$name] as $key => $item) {
                        array_push($data, $item);
                    }
                    array_push($options, $data);
                }
            }
        }

        $combinations = array();
        foreach ($options as $option_group) {
            if (is_array($option_group)) {
                foreach ($option_group as $value) {
                    $combinations[] = [$value];
                }
            }
        }

        return view('backend.product.products.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
    }
}