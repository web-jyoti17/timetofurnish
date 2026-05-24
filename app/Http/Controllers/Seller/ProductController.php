<?php

namespace App\Http\Controllers\Seller;

use AizPackages\CombinationGenerate\Services\CombinationService;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\AttributeCategory;
use App\Models\AttributeTranslation;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductTax;
use App\Models\ProductTranslation;
use App\Models\Wishlist;
use App\Models\User;
use App\Notifications\ShopProductNotification;
use App\Models\ProductAddon;
use App\Models\ProductAddonOption;
use App\Models\ProductAddonGlobal;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Combinations;
use Artisan;
use Auth;
use Barryvdh\DomPDF\Facade\PDF;
use Str;

use App\Services\ProductService;
use App\Services\ProductTaxService;
use App\Services\ProductFlashDealService;
use App\Services\ProductStockService;
use Illuminate\Support\Facades\Notification;
use App\Models\CheckoutService;
class ProductController extends Controller
{
    protected $productService;
    protected $productCategoryService;
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
    }

    public function index(Request $request)
    {
        $search = null;

        $products = Product::where('user_id', Auth::user()->id)
            ->with('stocks')
            ->where('digital', 0)
            ->where('auction_product', 0)
            ->where('wholesale_product', 0)
            ->orderBy('created_at', 'desc');

        // Search
        if ($request->search) {
            $search = $request->search;
            $products->where('name', 'like', '%' . $search . '%');
        }

        // Category Filter (Parent + Children)
        if ($request->category_id) {

            $category = Category::find($request->category_id);

            if ($category) {

                $childIds = Category::where('parent_id', $category->id)
                    ->pluck('id')
                    ->toArray();

                $categoryIds = array_merge([$category->id], $childIds);

                $products->whereHas('categories', function ($query) use ($categoryIds) {
                    $query->whereIn('categories.id', $categoryIds);
                });
            }
        }

        $products = $products->paginate(10)->appends($request->all());

        return view('seller.product.products.index', compact('products', 'search'));
    }


    public function getCheckoutServicesByCategory(Request $request)
    {
        $categoryIds = $request->category_ids ?? [];
        $categoryIds = getCheckoutServiceCategoryMatchIds($categoryIds);

        $services = \App\Models\CheckoutService::whereHas('categories', function ($q) use ($categoryIds) {
            $q->whereIn('categories.id', $categoryIds);
        })
        ->where('status', 1)
        ->get();

        return view(
            'seller.product.products.partials.checkout-services',
            compact('services')
        )->render();
    }

    public function getShippingChargesByCategory(Request $request)
    {
        $categoryIds = getCheckoutServiceCategoryMatchIds($request->category_ids ?? []);

        $shippingCharges = \App\Models\ShippingCharge::whereHas('categories', function ($q) use ($categoryIds) {
            $q->whereIn('categories.id', $categoryIds);
        })
        ->where('status', 1)
        ->orderBy('sort_order')
        ->get();

        return view(
            'seller.product.products.partials.shipping-charges',
            compact('shippingCharges')
        )->render();
    }

    public function create(Request $request)
    {
        if (addon_is_activated('seller_subscription')) {
            if (!seller_package_validity_check()) {
                flash(translate('Please upgrade your package.'))->warning();
                return back();
            }
        }
        $services = collect();
        $selectedServices = [];
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        $addons = [];
        return view(
            'seller.product.products.create',
            compact(
                'categories',
                'addons',
                'services',
                'selectedServices'
            )
        );
    }

    public function store(ProductRequest $request)
    {
        if (addon_is_activated('seller_subscription')) {
            if (!seller_package_validity_check()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => translate('Please upgrade your package.'),
                        'errors' => [],
                    ], 403);
                }

                flash(translate('Please upgrade your package.'))->warning();
                return redirect()->route('seller.products.index');
            }
        }


        $product = $this->productService->store($request->except([
            '_token',
            'sku',
            'choice',
            'tax_id',
            'tax',
            'tax_type',
            'flash_deal_id',
            'flash_discount',
            'flash_discount_type',
            'meta_img',
            'addons'
        ]));
        $request->merge(['product_id' => $product->id]);

        ///Product categories
        $product->categories()->attach($request->category_ids);
        $this->syncSelectedAttributesToCategories($request->choice_no ?? [], $request->category_ids ?? []);
        /*
        |--------------------------------------------------------------------------
        | AUTO ASSIGN CATEGORY SERVICES
        |--------------------------------------------------------------------------
        */

        $serviceCategoryIds = getCheckoutServiceCategoryMatchIds($request->category_ids ?? []);

        $serviceIds = \App\Models\CheckoutService::whereHas('categories', function ($q) use ($serviceCategoryIds) {

            $q->whereIn('categories.id', $serviceCategoryIds);

        })
        ->where('status', 1)
        ->pluck('id')
        ->toArray();

        $product->checkoutServices()->sync($serviceIds);

        $shippingChargeIds = \App\Models\ShippingCharge::whereHas('categories', function ($q) use ($serviceCategoryIds) {
            $q->whereIn('categories.id', $serviceCategoryIds);
        })
        ->where('status', 1)
        ->pluck('id')
        ->toArray();

        $product->shippingCharges()->sync($shippingChargeIds);
        //VAT & Tax
        if ($request->tax_id) {
            $this->productTaxService->store($request->only([
                'tax_id',
                'tax',
                'tax_type',
                'product_id'
            ]));
        }

        //Product Stock
        $this->productStockService->store($request->only([
            'colors_active',
            'colors',
            'choice_no',
            'unit_price',
            'sku',
            'current_stock',
            'product_id',
            'dispatch_time'
        ]), $product);

        // Product Translations
        $request->merge(['lang' => env('DEFAULT_LANGUAGE')]);
        ProductTranslation::create($request->only([
            'lang',
            'name',
            'unit',
            'description',
            'specification',
            'product_id',
        ]));

        $product->dimensions_enabled = $request->has('dimensions_enabled') ? 1 : 0;

        $product->dimensions_unit = $request->dimensions_unit ?? 'in';

        $product->save();


        /************ adddon code **************/
        if ($request->has('addons')) {

            foreach ($request->addons as $aIndex => $addon) {

                // ✅ Skip if checkbox not checked
                if (empty($addon['id'])) {
                    continue;
                }

                // ✅ Determine CREATE or UPDATE
                if ($addon['id'] === 'new') {
                    $addonModel = ProductAddon::create([
                        'product_id' => $product->id,
                        'name'       => $addon['name'],
                        'sort_order' => $addon['sort_order'] ?? 0
                    ]);
                } else {
                    $addonModel = ProductAddon::updateOrCreate(
                        ['id' => $addon['id']],
                        [
                            'product_id' => $product->id,
                            'name'       => $addon['name'],
                            'sort_order' => $addon['sort_order'] ?? 0
                        ]
                    );
                }

                // ✅ OPTIONS
                if (!empty($addon['options'])) {

                    foreach ($addon['options'] as $oIndex => $option) {

                        // ❌ Skip if not checked
                        if (empty($option['id'])) {
                            continue;
                        }

                        // ❌ Skip empty name
                        if (empty($option['name'])) {
                            continue;
                        }

                        $imagePath = null;

                        // ✅ Check if new image is uploaded
                        if ($request->hasFile("addons.$aIndex.options.$oIndex.img")) {

                            $file = $request->file("addons.$aIndex.options.$oIndex.img");

                            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                            $file->move(public_path('addon'), $filename);

                            $imagePath = 'addon/' . $filename;
                        } else {

                            // ✅ Keep existing image if no new upload
                            $imagePath = $request->input("addons.$aIndex.options.$oIndex.existing_img");
                        }

                        // ✅ CREATE or UPDATE OPTION
                        if ($option['id'] === 'new') {

                            ProductAddonOption::create([
                                'product_addon_id' => $addonModel->id,
                                'option_name'      => $option['name'],
                                'img'              => $imagePath,
                                'price'            => $option['price'] ?? 0,
                                'quantity'         => $option['quantity'] ?? 0
                            ]);
                        } else {

                            ProductAddonOption::updateOrCreate(
                                ['id' => $option['id']],
                                [
                                    'product_addon_id' => $addonModel->id,
                                    'option_name'      => $option['name'],
                                    'img'              => $imagePath,
                                    'price'            => $option['price'] ?? 0,
                                    'quantity'         => $option['quantity'] ?? 0
                                ]
                            );
                        }
                    }
                }
            }
        }
        /************ adddon code **************/
        if (get_setting('product_approve_by_admin') == 1) {
            $users = User::findMany([auth()->user()->id, User::where('user_type', 'admin')->first()->id]);
            Notification::send($users, new ShopProductNotification('physical', $product));
        }

        flash(translate('Your product has been submitted successfully. It is currently pending for approval and will be live once approved.'))->success();

        if (!$request->expectsJson()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => translate('Your product has been submitted successfully. It is currently pending for approval and will be live once approved.'),
                'redirect' => route('seller.products.index'),
            ]);
        }

        return redirect()->route('seller.products.index');
    }
    public function edit(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if (Auth::user()->id != $product->user_id) {
            flash(translate('This product is not yours.'))->warning();
            return back();
        }

        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        $old_categories = $product->categories()->pluck('category_id')->toArray();
        $old_categories = $product->categories()->pluck('category_id')->toArray();

        $serviceCategoryIds = getCheckoutServiceCategoryMatchIds($old_categories);

        $services = \App\Models\CheckoutService::whereHas('categories', function ($q) use ($serviceCategoryIds) {

            $q->whereIn('categories.id', $serviceCategoryIds);

        })
        ->where('status', 1)
        ->orderBy('sort_order')
        ->get();

        $serviceIds = $services->pluck('id')->toArray();

        $product->checkoutServices()->sync($serviceIds);
        $shippingChargeIds = \App\Models\ShippingCharge::whereHas('categories', function ($q) use ($serviceCategoryIds) {
            $q->whereIn('categories.id', $serviceCategoryIds);
        })
        ->where('status', 1)
        ->pluck('id')
        ->toArray();

        $product->shippingCharges()->sync($shippingChargeIds);

        $selectedServices = $serviceIds;
        $parentIds =  getParentCategoryIds($old_categories);
        $addonCategoryIds = $this->getAddonCategoryMatchIds($old_categories);
        $attribute_values = AttributeCategory::whereIn('category_id', $parentIds)->pluck('attribute_id')->toArray();


        $productId = $id;

        /*
        |--------------------------------------------------------------------------
        | 1. Global Addons (NO ID)
        |--------------------------------------------------------------------------
        */
        $globalAddons = ProductAddonGlobal::whereHas('categories', function ($query) use ($addonCategoryIds) {
            $query->whereIn('categories.id', $addonCategoryIds);
        })
        ->with('options')
        ->get()
        ->map(function ($addon) {
            return [
                "name" => $addon->name,
                "options" => $addon->options->map(function ($opt) {
                    return [
                        "name"  => $opt->option_name,
                        "price" => (float) $opt->price,
                        "quantity" => (int) ($opt->quantity ?? 0),
                        "img"   => $opt->img ?? ''   // ✅ added image
                    ];
                })->toArray()
            ];
        })
        ->toArray();

        /*
        |--------------------------------------------------------------------------
        | 2. Product Addons (WITH ID)
        |--------------------------------------------------------------------------
        */
        $productAddons = ProductAddon::with('options')
            ->where('product_id', $productId)
            ->get()
            ->map(function ($addon) {
                return [
                    "id"   => $addon->id,
                    "name" => $addon->name,
                    "options" => $addon->options->map(function ($opt) {
                        return [
                            "id"    => $opt->id,
                            "name"  => $opt->option_name,
                            "price" => (float) $opt->price,
                            "quantity" => (int) ($opt->quantity ?? 0),
                            "img"   => $opt->img ?? ''   // ✅ added image
                        ];
                    })->toArray()
                ];
            })
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | 3. Merge दोनों
        |--------------------------------------------------------------------------
        */
        $pAddons = collect(array_merge($productAddons, $globalAddons)) // ✅ product first
            ->groupBy('name')
            ->map(function ($group) {

                // ✅ Prefer addon with ID (product addon)
                $addonWithId = collect($group)->firstWhere('id');

                return [
                    "id"   => $addonWithId['id'] ?? null,
                    "name" => $addonWithId['name'] ?? $group->first()['name'],

                    "options" => collect($group)
                        ->pluck('options')
                        ->flatten(1)

                        // 👉 Group options by name
                        ->groupBy(function ($opt) {
                            return strtolower($opt['name']);
                        })

                        ->map(function ($optGroup) {

                            // ✅ Prefer product option (has id)
                            $withId = collect($optGroup)->firstWhere('id');

                            if ($withId) {
                                return $withId; // includes id + img + price
                            }

                            // fallback → global option
                            return $optGroup->first();
                        })

                        ->values()
                        ->toArray()
                ];
            })
            ->values()
            ->toArray();
        $addons = [];
        $addons = $pAddons;

        return view(
            'seller.product.products.edit',
            compact(
                'product',
                'categories',
                'tags',
                'lang',
                'old_categories',
                'attribute_values',
                'addons',
                'services',
                'selectedServices'
            )
        );    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        //Product
        $product = $this->productService->update($request->except([
            '_token',
            'sku',
            'choice',
            'tax_id',
            'tax',
            'tax_type',
            'flash_deal_id',
            'flash_discount',
            'flash_discount_type',
            'meta_img',
            'addons'
        ]), $product);

        $request->merge(['product_id' => $product->id]);

        //Product categories
        $product->categories()->sync($request->category_ids);
        $this->syncSelectedAttributesToCategories($request->choice_no ?? [], $request->category_ids ?? []);
        // Product checkout services
        /*
        |--------------------------------------------------------------------------
        | AUTO ASSIGN CATEGORY SERVICES
        |--------------------------------------------------------------------------
        */

        $serviceCategoryIds = getCheckoutServiceCategoryMatchIds($request->category_ids ?? []);

        $serviceIds = \App\Models\CheckoutService::whereHas('categories', function ($q) use ($serviceCategoryIds) {

            $q->whereIn('categories.id', $serviceCategoryIds);

        })
        ->where('status', 1)
        ->pluck('id')
        ->toArray();

        $product->checkoutServices()->sync($serviceIds);
        $shippingChargeIds = \App\Models\ShippingCharge::whereHas('categories', function ($q) use ($serviceCategoryIds) {
            $q->whereIn('categories.id', $serviceCategoryIds);
        })
        ->where('status', 1)
        ->pluck('id')
        ->toArray();

        $product->shippingCharges()->sync($shippingChargeIds);


        //Product Stock
        $product->stocks()->delete();
        $this->productStockService->store($request->only([
            'colors_active',
            'colors',
            'choice_no',
            'unit_price',
            'sku',
            'current_stock',
            'product_id',
            'checkout_services',
            'dispatch_time'

        ]), $product);

        //VAT & Tax
        if ($request->tax_id) {
            $product->taxes()->delete();
            $request->merge(['product_id' => $product->id]);
            $this->productTaxService->store($request->only([
                'tax_id',
                'tax',
                'tax_type',
                'product_id'
            ]));
        }

        if ($request->has('addons')) {

            $existingAddonIds = [];

            foreach ($request->addons as $aIndex => $addon) {

                // ❌ Skip if checkbox not checked
                if (empty($addon['id'])) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | CREATE or UPDATE ADDON
                |--------------------------------------------------------------------------
                */
                if ($addon['id'] === 'new') {
                    $addonModel = ProductAddon::create([
                        'product_id' => $product->id,
                        'name'       => $addon['name'] ?? ''
                    ]);
                } else {
                    $addonModel = ProductAddon::updateOrCreate(
                        ['id' => $addon['id']],
                        [
                            'product_id' => $product->id,
                            'name'       => $addon['name'] ?? ''
                        ]
                    );
                }

                $existingAddonIds[] = $addonModel->id;

                /*
            |--------------------------------------------------------------------------
            | OPTIONS
            |--------------------------------------------------------------------------
            */
                $existingOptionIds = [];

                if (!empty($addon['options'])) {

                    foreach ($addon['options'] as $oIndex => $option) {

                        // ❌ Skip if checkbox not checked
                        if (empty($option['id'])) {
                            continue;
                        }

                        // ❌ Skip empty name
                        if (empty($option['name'])) {
                            continue;
                        }

                        $imagePath = null;

                        // 👉 Get existing option (for update case)
                        $existingOption = null;
                        if ($option['id'] !== 'new') {
                            $existingOption = ProductAddonOption::find($option['id']);
                        }

                        /*
                    |--------------------------------------------------------------------------
                    | IMAGE UPLOAD
                    |--------------------------------------------------------------------------
                    */
                        if ($request->hasFile("addons.$aIndex.options.$oIndex.img")) {

                            $file = $request->file("addons.$aIndex.options.$oIndex.img");

                            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                            $file->move(public_path('addon'), $filename);

                            $imagePath = 'addon/' . $filename;

                            // 👉 delete old image (from hidden or DB)
                            $oldImage = $request->input("addons.$aIndex.options.$oIndex.existing_img")
                                ?? ($existingOption->img ?? null);

                            if ($oldImage && file_exists(public_path($oldImage))) {
                                //unlink(public_path($oldImage));
                            }
                        } else {

                            // 👉 priority: hidden input → DB → null
                            $imagePath = $request->input("addons.$aIndex.options.$oIndex.existing_img")
                                ?? ($existingOption->img ?? null);
                        }

                        /*
                    |--------------------------------------------------------------------------
                    | CREATE or UPDATE OPTION
                    |--------------------------------------------------------------------------
                    */
                        if ($option['id'] === 'new') {

                            $optModel = ProductAddonOption::create([
                                'product_addon_id' => $addonModel->id,
                                'option_name'      => $option['name'],
                                'img'              => $imagePath,
                                'price'            => $option['price'] ?? 0,
                                'quantity'         => $option['quantity'] ?? 0
                            ]);
                        } else {

                            $optModel = ProductAddonOption::updateOrCreate(
                                ['id' => $option['id']],
                                [
                                    'product_addon_id' => $addonModel->id,
                                    'option_name'      => $option['name'],
                                    'img'              => $imagePath,
                                    'price'            => $option['price'] ?? 0,
                                    'quantity'         => $option['quantity'] ?? 0
                                ]
                            );
                        }

                        $existingOptionIds[] = $optModel->id;
                    }
                }

                /*
            |--------------------------------------------------------------------------
            | DELETE REMOVED OPTIONS
            |--------------------------------------------------------------------------
            */
                $deletedOptions = ProductAddonOption::where('product_addon_id', $addonModel->id)
                    ->whereNotIn('id', $existingOptionIds)
                    ->get();

                foreach ($deletedOptions as $delOpt) {
                    /*if ($delOpt->img && file_exists(public_path($delOpt->img))) {
                    unlink(public_path($delOpt->img));
                }*/
                    $delOpt->delete();
                }
            }

            /*
        |--------------------------------------------------------------------------
        | DELETE REMOVED ADDONS
        |--------------------------------------------------------------------------
        */
            $deletedAddons = ProductAddon::where('product_id', $product->id)
                ->whereNotIn('id', $existingAddonIds)
                ->get();

            foreach ($deletedAddons as $addon) {

                $options = ProductAddonOption::where('product_addon_id', $addon->id)->get();

                foreach ($options as $opt) {
                    /* if ($opt->img && file_exists(public_path($opt->img))) {
                    unlink(public_path($opt->img));
                }*/
                    $opt->delete();
                }

                $addon->delete();
            }
        } else {
            $removedAddons = ProductAddon::where('product_id', $product->id)->get();

            foreach ($removedAddons as $addon) {
                ProductAddonOption::where('product_addon_id', $addon->id)->delete();
                $addon->delete();
            }
        }
        // DELETE SERVICES FROM DATABASE
        if ($request->deleted_services) {

            $deletedIds = explode(',', $request->deleted_services);

            // remove from pivot table first
            DB::table('checkout_service_product')
                ->whereIn('checkout_service_id', $deletedIds)
                ->delete();

            // now delete services
            \App\Models\CheckoutService::whereIn('id', $deletedIds)->delete();
        }
        $product->dimensions_enabled = $request->has('dimensions_enabled') ? 1 : 0;

        $product->dimensions_unit = $request->dimensions_unit ?? 'in';

        $product->save();

        // Product Translations
        ProductTranslation::updateOrCreate(
            $request->only([
                'lang',
                'product_id'
            ]),
            $request->only([
                'name',
                'unit',
                'description',
                'specification',
            ])
        );


        flash(translate('Product has been updated successfully'))->success();

        if (!$request->expectsJson()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => translate('Product has been updated successfully'),
                'redirect' => route('seller.products.index'),
            ]);
        }

        // return back();
        return redirect()->route('seller.products.index');
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
        $old_values = $request->old_values;
        $product_name = $request->name;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = array();
                if (!empty($request[$name])) {
                    foreach ($request[$name] as $key => $item) {
                        array_push($data, $item);
                    }
                }
                array_push($options, $data);
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

        return view('backend.product.products.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'old_values'));
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
                $data = array();
                if (!empty($request[$name])) {
                    foreach ($request[$name] as $key => $item) {
                        array_push($data, $item);
                    }
                }
                array_push($options, $data);
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

    public function add_more_choice_option(Request $request)
    {
        $all_attribute_values = AttributeValue::with('attribute')->where('attribute_id', $request->attribute_id)->get();

        $html = '';

        foreach ($all_attribute_values as $row) {
            $html .= '<option value="' . $row->value . '">' . $row->value . '</option>';
        }

        echo json_encode($html);
    }

    public function storeAttribute(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'values' => 'required',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'integer',
        ]);

        $name = trim($request->name);
        $attribute = Attribute::whereRaw('LOWER(name) = ?', [strtolower($name)])->first();

        if (!$attribute) {
            $attribute = new Attribute;
            $attribute->name = $name;
            $attribute->save();

            AttributeTranslation::firstOrCreate(
                ['lang' => env('DEFAULT_LANGUAGE'), 'attribute_id' => $attribute->id],
                ['name' => $name]
            );
        }

        $values = is_array($request->values)
            ? $request->values
            : preg_split('/\s*,\s*/', (string) $request->values, -1, PREG_SPLIT_NO_EMPTY);

        $savedValues = [];

        foreach ($values as $value) {
            $cleanValue = trim((string) $value);

            if ($cleanValue === '') {
                continue;
            }

            $savedValue = ucfirst($cleanValue);

            AttributeValue::firstOrCreate([
                'attribute_id' => $attribute->id,
                'value' => $savedValue,
            ]);

            $savedValues[] = $savedValue;
        }

        $this->syncSelectedAttributesToCategories([$attribute->id], $request->category_ids ?? []);

        return response()->json([
            'message' => translate('Attribute added successfully.'),
            'attribute' => [
                'id' => $attribute->id,
                'name' => $attribute->getTranslation('name'),
            ],
            'values' => array_values(array_unique($savedValues)),
        ]);
    }

    public function getAddonsByCategories(Request $request)
    {
        $categoryIds = $request->input('category_ids') ?? [];
        if (empty($categoryIds)) {
            return response()->json([]);
        }
        $addonCategoryIds = $this->getAddonCategoryMatchIds($categoryIds);
        if (empty($addonCategoryIds)) {
            return response()->json([]);
        }

        $addons = ProductAddonGlobal::whereHas('categories', function ($query) use ($addonCategoryIds) {
            $query->whereIn('categories.id', $addonCategoryIds);
        })
        ->with('options')
        ->orderBy('sort_order', 'asc')
        ->get()
        ->map(function ($addon) {
            return [
                "id"   => 'new',
                "name" => $addon->name,
                "options" => $addon->options->map(function ($opt) {
                    return [
                        "id"    => 'new',
                        "name"  => $opt->option_name,
                        "price" => (float) $opt->price,
                        "quantity" => (int) ($opt->quantity ?? 0),
                        "img"   => $opt->img ?? ''
                    ];
                })->toArray()
            ];
        })
        ->toArray();

        return response()->json($addons);
    }

    private function getAddonCategoryMatchIds(array $categoryIds): array
    {
        $matchIds = collect($categoryIds)
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        $lookupIds = $matchIds;

        while ($lookupIds->isNotEmpty()) {
            $parentIds = Category::whereIn('id', $lookupIds)
                ->pluck('parent_id')
                ->map(fn ($id) => (int) $id)
                ->filter()
                ->diff($matchIds)
                ->unique()
                ->values();

            if ($parentIds->isEmpty()) {
                break;
            }

            $matchIds = $matchIds->merge($parentIds)->unique()->values();
            $lookupIds = $parentIds;
        }

        return $matchIds->all();
    }

    private function syncSelectedAttributesToCategories($attributeIds, $categoryIds): void
    {
        $attributeIds = collect((array) $attributeIds)->filter()->unique()->values();
        $categoryIds = collect((array) $categoryIds)->filter()->unique()->values();

        if ($attributeIds->isEmpty() || $categoryIds->isEmpty()) {
            return;
        }

        foreach ($attributeIds as $attributeId) {
            foreach ($categoryIds as $categoryId) {
                AttributeCategory::firstOrCreate([
                    'attribute_id' => $attributeId,
                    'category_id' => $categoryId,
                ]);
            }
        }
    }

    public function updatePublished(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->published = $request->status;
        if (addon_is_activated('seller_subscription') && $request->status == 1) {
            $shop = $product->user->shop;
            if (!seller_package_validity_check()) {
                return 2;
            }
        }
        $product->save();
        return 1;
    }

    public function updateFeatured(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->seller_featured = $request->status;
        if ($product->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

    public function duplicate($id)
    {
        $product = Product::find($id);

        if (Auth::user()->id != $product->user_id) {
            flash(translate('This product is not yours.'))->warning();
            return back();
        }

        if (addon_is_activated('seller_subscription')) {
            if (!seller_package_validity_check()) {
                flash(translate('Please upgrade your package.'))->warning();
                return back();
            }
        }

        //Product
        $product_new = $this->productService->product_duplicate_store($product);

        //Product Stock
        $this->productStockService->product_duplicate_store($product->stocks, $product_new);

        //VAT & Tax
        $this->productTaxService->product_duplicate_store($product->taxes, $product_new);

        flash(translate('Product has been duplicated successfully'))->success();
        return redirect()->route('seller.products.index');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if (Auth::user()->id != $product->user_id) {
            flash(translate('This product is not yours.'))->warning();
            return back();
        }

        $product->product_translations()->delete();
        $product->categories()->detach();
        $product->stocks()->delete();
        $product->taxes()->delete();
        $product->checkoutServices()->detach();

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
    public function downloadProductsPdf()
    {
        $products = Product::where('user_id', Auth::id())->get();

        $pdf = PDF::loadView('seller.product.products.product_pdf', compact('products'));

        return $pdf->download('seller_products.pdf');
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
}
