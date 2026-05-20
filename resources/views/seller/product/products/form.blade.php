<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

    :root {
        --primary: #c59259;
        --primary-soft: rgba(108, 92, 231, 0.1);
        --secondary: #debd96ff;
        --success: #00b894;
        --info: #0984e3;
        --warning: #fdcb6e;
        --danger: #d63031;
        --dark: #2d3436;
        --light: #f9f9fb;
        --card-bg: #ffffff;
        --border-radius: 12px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
        font-family: 'Outfit', sans-serif;
        background-color: #f4f7f6;
        color: var(--dark);
    }

    .card {
        border: none;
        border-radius: var(--border-radius);
        margin-bottom: 1.5rem;
        transition: var(--transition);
        overflow: hidden;
        background: var(--card-bg);
    }


    .card-header {
        background: transparent;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-header h5 {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 0;
        font-size: 1.1rem;
    }

    .card {
        border-radius: 14px;
        border: 1px solid #eae9e9;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding: 0.6rem 1rem;
        transition: var(--transition);
        font-size: 0.95rem;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-soft);
    }

    .btn {
        border-radius: 8px;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
        transition: var(--transition);
        text-transform: none;
        letter-spacing: 0.3px;
    }


    .aiz-switch input:checked+span {
        background-color: var(--success);
    }

    /* Section Toggle Styles */
    .section-toggle-btn {
        background: var(--primary-soft);
        color: var(--primary);
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition);
    }

    .section-toggle-btn.collapsed {
        transform: rotate(-90deg);
    }

    .collapsible-content {
        transition: max-height 0.5s ease-out, opacity 0.3s ease;
        max-height: 2000px;
        opacity: 1;
        overflow: scroll;
    }

    .collapsible-content.collapsed {
        max-height: 0;
        opacity: 0;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }

    /* Addon Specific Styles */
    .addon-block {
        background: #fcfcfd;
        border: 1px solid #eee !important;
        border-radius: 10px;
        padding: 1.25rem !important;
        margin-bottom: 1rem;
        position: relative;
    }

    .select-all-addon {
        font-size: 0.85rem;
        color: var(--primary);
        cursor: pointer;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        border-radius: 6px;
        transition: var(--transition);
        background: var(--primary-soft);
    }

    .select-all-addon:hover {
        background: var(--primary);
        color: white;
        text-decoration: none;
    }

    /* Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card {
        animation: fadeInUp 0.5s ease-out forwards;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-header {
            padding: 1rem;
        }

        .card-body {
            padding: 1rem;
        }
    }

    /* Category Treeview Styling */
    .category-search-container {
        position: relative;
        margin-bottom: 1rem;
    }

    .category-search-container .form-control {
        padding-left: 2.5rem;
        background: #f8f9fa;
        border: 1px solid #eee;
    }

    .category-search-container i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
    }

    .hummingbird-treeview-converter {
        max-height: 400px;
        overflow-y: auto;
        padding: 0.5rem;
    }

    .hummingbird-treeview-converter li {
        margin: 5px 0;
        position: relative;
        padding-left: 5px;
    }

    .hummingbird-treeview-converter label {
        display: flex;
        align-items: center;
        margin-bottom: 0;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 6px;
        transition: var(--transition);
    }

    .hummingbird-treeview-converter label:hover {
        background: var(--primary-soft);
    }

    /* Custom Checkbox Styling for Treeview */
    .hummingbird-treeview-converter input[type="checkbox"] {
        appearance: none;
        width: 18px;
        height: 18px;
        border: 2px solid #ddd;
        border-radius: 4px;
        margin-right: 10px;
        position: relative;
        cursor: pointer;
        transition: var(--transition);
        background: #fff;
    }

    .hummingbird-treeview-converter input[type="checkbox"]:checked {
        background: var(--primary);
        border-color: var(--primary);
    }

    .hummingbird-treeview-converter input[type="checkbox"]:checked::after {
        content: '\2713';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 12px;
    }

    .hummingbird-treeview-converter input[type="radio"] {
        display: none !important;
    }

    /* Hummingbird renders radios next to labels inside #treeview */
    #treeview input[type="radio"] {
        display: none !important;
    }

    .hummingbird-treeview-converter input[type="radio"]:checked {
        border-color: var(--primary);
    }

    .hummingbird-treeview-converter input[type="radio"]:checked::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 8px;
        height: 8px;
        background: var(--primary);
        border-radius: 50%;
    }

    .hummingbird-treeview-converter ul {
        border-left: 1px dashed #ddd;
        margin-left: 10px;
        padding-left: 15px;
    }

    /* Sticky Header Styles */
    .sticky-action-container {
        top: 65px;
        z-index: 1000;
        background: #faf7f2;
        /* Matches aiz-main-wrapper */

        transition: all 0.3s ease;
        padding: 10px 0;
        margin-top: -10px;
    }

    @media (max-width: 991px) {
        .sticky-action-container {
            top: 55px;
        }
    }

    .sticky-action-container.stuck {
        background: rgba(250, 247, 242, 0.98);
        backdrop-filter: blur(10px);
        padding: 15px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .sticky-action-container.stuck .card {
        border-color: transparent;
    }

    #choice_form {
        color: #202223;
    }

    #choice_form .card {
        border: 1px solid #dfe3e8;
        border-radius: 8px;
        box-shadow: 0 1px 0 rgba(0, 0, 0, 0.04);
    }

    #choice_form .card-header {
        background: #fff;
        border-bottom: 1px solid #edf0f2;
        padding: 16px 20px;
    }

    #choice_form .card-header h5,
    #choice_form .card-header .h6 {
        color: #202223;
        font-size: 15px;
        font-weight: 600;
    }

    #choice_form .card-body {
        padding: 20px;
    }

    #choice_form .form-control,
    #choice_form .bootstrap-select .dropdown-toggle {
        border-color: #c9cccf;
        border-radius: 6px;
        min-height: 38px;
        box-shadow: none;
    }

    #choice_form .form-control:focus,
    #choice_form .bootstrap-select.show .dropdown-toggle {
        border-color: #2c6ecb;
        box-shadow: 0 0 0 1px #2c6ecb;
    }

    #choice_form label,
    #choice_form .col-from-label,
    #choice_form .control-label {
        color: #202223;
        font-size: 13px;
        font-weight: 500;
    }

    #choice_form .btn-primary {
        background: #008060;
        border-color: #008060;
    }

    #choice_form .btn-primary:hover,
    #choice_form .btn-primary:focus {
        background: #006e52;
        border-color: #006e52;
    }

    #choice_form .btn-soft-primary {
        background: #eaf4ff;
        color: #2c6ecb;
    }

    #choice_form .sticky-action-container .card {
        border-radius: 8px;
    }
</style>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div id="product-form-alert" class="alert d-none" role="alert"></div>
{{-- Data container for JS --}}
<div id="product-form-data" class="d-none" data-base-url="{{ asset('public') }}"
    data-checkout-services-route="{{ route('seller.products.checkout-services') }}"
    data-shipping-charges-route="{{ route('seller.products.shipping-charges') }}"
    data-get-attributes-route="{{ route('get-attributes-by-categories') }}"
    data-add-more-choice-route="{{ route('seller.products.add-more-choice-option') }}"
    data-sku-combination-route="{{ isset($product) && $product->id ? route('seller.products.sku_combination_edit') : route('seller.products.sku_combination') }}"
    data-old-addons='@json(old(' addons', $addons ?? []))' data-existing-addons='@json($addons ?? [])'
    data-product-id="{{ $product->id ?? '' }}" data-choice-attributes-old='@json(old(' choice_attributes', isset($product) && $product->attributes != null ? json_decode($product->attributes) : []))'>
</div>
{{-- {{ dd($addons) }} --}}

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" id="choice_form" data-ajax-submit="true">
    @csrf
    @if (isset($product) && $product->id)
    <input type="hidden" name="id" value="{{ $product->id }}">
    @endif

    @if (isset($method) && $method == 'POST')
    @method('POST')
    @endif
    <div class="sticky-action-container">
        <div class="container p-0">
            <div class="flex flex-row card align-items-center">
                <div class="card-body d-flex justify-content-between align-items-center w-100">
                    <h3 class="mb-0">
                        {{ isset($product) && $product->id
                            ? translate('Edit Your Product')
                            : translate('Add Your
                                                                                                                        Product') }}
                    </h3>
                    <button type="submit" name="button" value="publish"
                        class="btn btn-primary">{{ translate('Upload
                                                                                                                        Product') }}</button>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-light border-bottom-0 pb-2">
                    <h5 class="mb-0 h6 text-black">
                        {{ translate('Matched Shipping Charges') }}
                    </h5>
                </div>

                <div class="card-body">
                    <div id="shipping-charges-wrapper" class="row gutters-2">
                        @include('seller.product.products.partials.shipping-charges', [
                            'shippingCharges' => isset($product) && $product->id ? getProductShippingCharges($product) : collect()
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gutters-5">
        <div class="col-lg-8">
            <input type="hidden" name="added_by" value="seller">
            <input type="hidden" name="old_values" value="{{ json_encode(old()) }}">

            @include('seller.product.products.partials.product-information-sec')




            <div>
                @include('seller.product.products.partials.product-variation')
                @include('seller.product.products.partials.scripts.product-variation-script')
            </div>

            <div class="card">
                <div class="card-header bg-light border-bottom-0 pb-2">
                    <h5 class="mb-0 h6 text-black">
                        {{ translate('Delivery & Assembly Services') }}
                    </h5>
                </div>

                <div class="card-body">

                    <div id="checkout-services-wrapper" class="row gutters-2">

                        @include(
                        'seller.product.products.partials.checkout-services',
                        [
                        'services' => $services ?? collect()
                        ]
                        )

                    </div>

                </div>

            </div>


            <div class="card" style="display:none;">
                <div class="card-header bg-light border-bottom-0 pb-2">
                    <h5 class="mb-0 h6 text-black">{{ translate('SEO Meta Tags') }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{ translate('Meta Title') }}</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="meta_title"
                                placeholder="{{ translate('Meta Title') }}"
                                value="{{ old('meta_title', $product->meta_title ?? '') }}">
                        </div>
                    </div>
                    <div class="form-group row ">
                        <label class="col-md-3 col-from-label">{{ translate('Description') }}</label>
                        <div class="col-md-8">
                            <textarea name="meta_description" rows="8" class="form-control">{{ old('meta_description', $product->meta_description ?? '') }}</textarea>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-light border-bottom-0 pb-2">
                    <h5 class="mb-0 h6 text-black">{{ translate('Product Category') }}</h5>
                    <h6 class="float-right mb-0 fs-13">
                        {{ translate('Select Main') }}
                        <span class="position-relative main-category-info-icon">
                            <i class="las la-question-circle fs-18 text-info"></i>
                            <span
                                class="p-2 border main-category-info bg-soft-info position-absolute d-none">{{ translate('This will be used for commission based calculations and homepage category
                                                                                                                                                                wise product Show.') }}</span>
                        </span>
                    </h6>
                </div>
                @php
                $selectedCategories = old(
                'category_ids',
                isset($product) ? $product->categories->pluck('id')->toArray() : [],
                );
                @endphp

                <input type="hidden" name="old_categories_string" value="{{ implode(',', $selectedCategories) }}">

                <input type="hidden" name="category_id" id="main_category_id"
                    value="{{ old('category_id', $product->category_id ?? '') }}">

                <div class="card-body">

                    <div class="mb-2 d-flex justify-content-between">

                        <button type="button" class="btn btn-sm btn-soft-primary px-2 py-1"
                            onclick="$('#treeview').hummingbird('expandAll')">

                            {{ translate('Expand All') }}

                        </button>

                        <button type="button" class="btn btn-sm btn-soft-secondary px-2 py-1"
                            onclick="$('#treeview').hummingbird('collapseAll')">

                            {{ translate('Collapse All') }}

                        </button>

                    </div>

                    <div class="overflow-auto c-scrollbar-light" style="max-height: 450px;">

                        <ul id="treeview" class="hummingbird-treeview-converter list-unstyled"
                            data-checkbox-name="category_ids[]">

                            @foreach ($categories as $category)
                            <li id="{{ $category->id }}">
                                {{ $category->getTranslation('name') }}
                            </li>

                            @foreach ($category->childrenCategories as $childCategory)
                            @include('backend.product.products.child_category', [
                            'child_category' => $childCategory,
                            ])
                            @endforeach
                            @endforeach

                        </ul>

                    </div>

                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Low Stock Quantity Warning') }}</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 form-group">
                        <label for="name">
                            {{ translate('Quantity') }}
                        </label>
                        <input type="number" name="low_stock_quantity"
                            value="{{ old('low_stock_quantity', $product->low_stock_quantity ?? 1) }}" min="1"
                            step="1" class="form-control"
                            oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,3)">
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">
                        {{ translate('Stock Visibility State') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-md-6 col-from-label">{{ translate('Show Stock Quantity') }}</label>
                        <div class="col-md-6">
                            <label class="mb-0 aiz-switch aiz-switch-success">
                                <input type="radio" name="stock_visibility_state" value="quantity" checked
                                    {{ old('stock_visibility_state', $product->stock_visibility_state ?? '') == 'quantity' ? 'checked' : '' }}>
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6 col-from-label">{{ translate('Show Stock With Text Only') }}</label>
                        <div class="col-md-6">
                            <label class="mb-0 aiz-switch aiz-switch-success">
                                <input type="radio" name="stock_visibility_state" value="text"
                                    {{ old('stock_visibility_state', $product->stock_visibility_state ?? '') == 'text' ? 'checked' : '' }}>
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6 col-from-label">{{ translate('Hide Stock') }}</label>
                        <div class="col-md-6">
                            <label class="mb-0 aiz-switch aiz-switch-success">
                                <input type="radio" name="stock_visibility_state" value="hide"
                                    {{ old('stock_visibility_state', $product->stock_visibility_state ?? '') == 'hide' ? 'checked' : '' }}>
                                <span></span>
                            </label>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card" style="display:none;">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('VAT & Tax') }}</h5>
                </div>
                <div class="card-body">
                    @foreach (\App\Models\Tax::where('tax_status', 1)->get() as $tax)
                    <label for="name">
                        {{ $tax->name }}
                        <input type="hidden" value="{{ $tax->id }}" name="tax_id[]">
                    </label>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="number" lang="en" min="0"
                                value="{{ is_array(old('tax')) ? old('tax')[$loop->index] ?? ($product->tax ?? 0) : old('tax', $product->tax ?? 0) }}"
                                step="0.01" placeholder="{{ translate('Tax') }}" name="tax[]"
                                class="form-control" required>

                        </div>
                        <div class="form-group col-md-6">
                            <select class="form-control aiz-selectpicker" name="tax_type[]">
                                <option value="amount">{{ translate('Flat') }}</option>
                                <option value="percent">{{ translate('Percent') }}</option>
                            </select>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 h6 mb-0" id="dimension-title">Dimensions (<span id="unit-label">Inches</span>)
                    </h5>
                    <div>
                        @php
                        $dimensions_enabled =
                        (int) old('dimensions_enabled', $product->dimensions_enabled ?? 0) === 1;
                        @endphp
                        <!-- Toggle to Show/Hide and Save/Not Save Dimensions -->
                        <label class="mb-0 aiz-switch aiz-switch-success mr-2" title="Enable Dimensions">
                            <input type="checkbox" id="toggle-dimensions" name="dimensions_enabled" value="1"
                                @checked($dimensions_enabled && $dimensions_enabled==1)>

                            <span></span>
                        </label>
                        <!-- Toggle to switch between Inches/Centimeters -->
                        <label class="mb-0 aiz-switch aiz-switch-success" title="Switch to Centimeters">
                            <input type="hidden" name="dimensions_unit" value="in">

                            <input type="checkbox" id="toggle-units" name="dimensions_unit" value="cm"
                                @checked(old('dimensions_unit', $product->dimensions_unit ?? 'in') == 'cm')>
                            <span>

                            </span>
                        </label>
                        <span style="font-size: 12px;color:var(--secondary);" id="unit-label-toggle">
                            <span id="show-inch"
                                {{ old('dimensions_unit', $product->dimensions_unit ?? 'in') == 'cm' ? 'style=display:none;' : '' }}>IN</span>
                            <span id="show-cm"
                                {{ old('dimensions_unit', $product->dimensions_unit ?? 'in') == 'cm' ? '' : 'style=display:none;' }}>CM</span>
                        </span>
                    </div>
                </div>
                <div class="card-body" id="dimensions-section"
                    style="{{ $dimensions_enabled ? '' : 'display:none;' }}">
                    <input type="hidden" name="dimensions_unit" id="dimensions_unit_input"
                        value="{{ old('dimensions_unit', $product->dimensions_unit ?? 'in') }}">
                    <div class="mb-3 form-group">
                        <label for="product_length">
                            Length <span class="text-danger"></span>
                        </label>
                        <div class="input-group">
                            <input type="text" name="product_length"
                                value="{{ old('product_length', $product->product_length ?? '') }}"
                                class="form-control" id="product_length" placeholder="Length"
                                {{ old('dimensions_enabled', !empty($product->product_length) ? 'required' : '') }}>
                            <div class="input-group-append">
                                <span class="input-group-text" id="length-unit-addon">
                                    {{ old('dimensions_unit', $product->dimensions_unit ?? 'in') == 'cm' ? 'cm' : 'in' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 form-group">
                        <label for="product_breadth">
                            Width <span class="text-danger"></span>
                        </label>
                        <div class="input-group">
                            <input type="text" name="product_breadth"
                                value="{{ old('product_breadth', $product->product_breadth ?? '') }}"
                                class="form-control" id="product_breadth" placeholder="Width"
                                {{ old('dimensions_enabled', !empty($product->product_breadth) ? 'required' : '') }}>
                            <div class="input-group-append">
                                <span class="input-group-text" id="breadth-unit-addon">
                                    {{ old('dimensions_unit', $product->dimensions_unit ?? 'in') == 'cm' ? 'cm' : 'in' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 form-group">
                        <label for="product_height">
                            Height <span class="text-danger"></span>
                        </label>
                        <div class="input-group">
                            <input type="text" name="product_height"
                                value="{{ old('product_height', $product->product_height ?? '') }}"
                                class="form-control" id="product_height" placeholder="Height"
                                {{ old('dimensions_enabled', !empty($product->product_height) ? 'required' : '') }}>
                            <div class="input-group-append">
                                <span class="input-group-text" id="height-unit-addon">
                                    {{ old('dimensions_unit', $product->dimensions_unit ?? 'in') == 'cm' ? 'cm' : 'in' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 form-group">
                        <label for="weight">
                            Weight <small id="weight-unit-label">IN Kg</small><span class="text-danger"></span>
                        </label>
                        <input type="text" class="form-control" name="weight"
                            value="{{ old('weight', $product->weight ?? '0.00') }}" id="weight"
                            placeholder="0.00"
                            {{ old('dimensions_enabled', !empty($product->weight) ? 'required' : '') }}>
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var dimensionsToggle = document.getElementById('toggle-dimensions');
                    var dimensionsSection = document.getElementById('dimensions-section');
                    var requiredFields = dimensionsSection.querySelectorAll(
                        'input[name="product_length"],input[name="product_breadth"],input[name="product_height"],input[name="weight"]'
                    );
                    var unitToggle = document.getElementById('toggle-units');
                    var unitLabel = document.getElementById('unit-label');
                    var unitLabelToggle = document.getElementById('unit-label-toggle');
                    var showInch = document.getElementById('show-inch');
                    var showCm = document.getElementById('show-cm');
                    var unitInput = document.getElementById('dimensions_unit_input');
                    var weightUnitLabel = document.getElementById('weight-unit-label');
                    var lengthUnitAddon = document.getElementById('length-unit-addon');
                    var breadthUnitAddon = document.getElementById('breadth-unit-addon');
                    var heightUnitAddon = document.getElementById('height-unit-addon');

                    function updateUnitsUI() {
                        if (unitToggle.checked) {
                            unitLabel.textContent = "Centimeters";
                            unitInput.value = 'cm';
                            showInch.style.display = 'none';
                            showCm.style.display = '';
                            lengthUnitAddon.textContent = 'cm';
                            breadthUnitAddon.textContent = 'cm';
                            heightUnitAddon.textContent = 'cm';
                        } else {
                            unitLabel.textContent = "Inches";
                            unitInput.value = 'in';
                            showInch.style.display = '';
                            showCm.style.display = 'none';
                            lengthUnitAddon.textContent = 'in';
                            breadthUnitAddon.textContent = 'in';
                            heightUnitAddon.textContent = 'in';
                        }
                        // Weight unit is always Kg in this UI
                        weightUnitLabel.textContent = 'IN Kg';
                    }

                    function updateDisplayByToggle() {
                        if (dimensionsToggle.checked) {
                            dimensionsSection.style.display = '';
                            requiredFields.forEach(function(input) {
                                input.required = true;
                            });
                        } else {
                            dimensionsSection.style.display = 'none';
                            requiredFields.forEach(function(input) {
                                input.required = false;
                            });
                        }
                    }
                    // Initialize on page load
                    updateDisplayByToggle();
                    updateUnitsUI();
                    dimensionsToggle.addEventListener('change', updateDisplayByToggle);
                    unitToggle.addEventListener('change', updateUnitsUI);
                });
            </script>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ 'Product Price and Stock' }}</h5>
                </div>
                <div class="card-body">
                    @php
                    $unit_price_enabled = (bool) old(
                    'unit_price_enabled',
                    trim((string) old('unit_price', $product->unit_price ?? '')) !== '',
                    );
                    @endphp
                    <div class="form-group row align-items-center">
                        <label class="col-md-3 col-from-label">
                            {{ 'Unit Price (GBP)' }} <span>£</span>
                        </label>
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="mb-0 aiz-switch aiz-switch-success">
                                <input type="checkbox" id="toggleUnitPrice" name="unit_price_enabled" value="1"
                                    {{ $unit_price_enabled ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                            <span class="ml-2" id="toggleUnitPriceLabel">{{ translate('Show') }}</span>
                        </div>
                        <div class="col-md-4" id="unitPriceInputWrapper"
                            style="{{ $unit_price_enabled ? '' : 'display:none;' }}">
                            <input type="text" name="unit_price" inputmode="decimal"
                                placeholder="{{ translate('Unit price') }}"
                                value="{{ old('unit_price', $product->unit_price ?? '') }}" class="form-control"
                                oninput="
                            // allow only numbers and dot
                            this.value = this.value.replace(/[^0-9.]/g, '').slice(0, 5);
                            // allow only one dot
                            this.value = this.value.replace(/(\..*)\./g, '$1');
                            // block leading zero like 01, 00 (except 0.)
                            if (this.value.length > 1 && this.value.startsWith('0') && !this.value.startsWith('0.')) {
                                this.value = this.value.replace(/^0+/, '');
                            }
                        "
                                onblur="if(this.value > 99999) alert('Unit price cannot exceed 99999')"
                                onchange="update_sku()" id="unit_price_input">
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var toggle = document.getElementById('toggleUnitPrice');
                            var unitPriceInputWrapper = document.getElementById('unitPriceInputWrapper');
                            var label = document.getElementById('toggleUnitPriceLabel');
                            var unitPriceInput = document.getElementById('unit_price_input');

                            function updateUnitPriceVisibility() {
                                if (toggle.checked) {
                                    unitPriceInputWrapper.style.display = '';
                                    label.innerText = "{{ translate('Show') }}";
                                    // Enable the input (not required)
                                    if (unitPriceInput) {
                                        unitPriceInput.removeAttribute('disabled');
                                        unitPriceInput.tabIndex = 0;
                                    }
                                } else {
                                    unitPriceInputWrapper.style.display = 'none';
                                    label.innerText = "{{ translate('Hide') }}";
                                    // Keep the empty value submitted so update can save null.
                                    if (unitPriceInput) {
                                        unitPriceInput.value = '';
                                        unitPriceInput.removeAttribute('disabled');
                                        unitPriceInput.tabIndex = -1;
                                    }
                                }
                            }
                            toggle.addEventListener('change', updateUnitPriceVisibility);
                            updateUnitPriceVisibility();
                        });
                    </script>
                    <div class="form-group row">
                        <label class="col-md-3 control-label"
                            for="start_date">{{ translate('Discount Date Range') }}
                        </label>
                        <div class="col-md-9">
                            @php
                            $discount_enabled = (bool) old(
                            'discount_enabled',
                            (float) old('discount', $product->discount ?? 0) > 0 ||
                            !empty(old('date_range', $product->date_range ?? '')),
                            );
                            @endphp
                            <input type="text" class="form-control aiz-date-range" name="date_range"
                                id="date_range" value="{{ old('date_range', $product->date_range ?? '') }}"
                                placeholder="{{ translate('Select Date') }}" data-time-picker="true"
                                data-format="DD-MM-Y HH:mm" data-separator=" to " autocomplete="off"
                                {{ $discount_enabled ? '' : 'disabled' }}>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">
                            {{ translate('Discount') }}
                        </label>
                        <!-- Enable / Disable Button -->
                        <div class="mb-2 col-md-2 mb-md-0">
                            <label class="mb-0 aiz-switch aiz-switch-success">
                                <input id="discountToggleBtn" name="discount_enabled" onchange="toggleDiscount()"
                                    value="1" type="checkbox" {{ $discount_enabled ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <!-- Discount Input -->
                        <div class="col-md-4 discount-box" style="{{ $discount_enabled ? '' : 'display:none;' }}">
                            <input type="number" lang="en" min="0" step="0.01"
                                placeholder="{{ translate('Discount') }}" name="discount" id="discountInput"
                                value="{{ old('discount', $product->discount ?? '') }}" class="form-control"
                                oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,4)">
                        </div>
                        <!-- Discount Type -->
                        <div class="col-md-6 discount-box" style="{{ $discount_enabled ? '' : 'display:none;' }}">
                            <select class="form-control aiz-selectpicker" name="discount_type">
                                <option value="amount">{{ translate('Flat') }}</option>
                                <option value="percent">{{ translate('Percent') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <small class="text-muted discount">
                                <span>(</span>{{ translate('If you do want to sell the items in discounted price or
                                                                                                                                                                clearance') }}<span>)</span>
                            </small>
                        </div>
                    </div>
                    <div id="show-hide-div">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Quantity') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input type="number" lang="en" min="1" step="1"
                                    placeholder="{{ translate('Quantity') }}" name="current_stock"
                                    value="{{ old('current_stock', $product->current_stock ?? 1) }}"
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">
                                {{ translate('SKU') }} <span class="text-danger"></span>
                            </label>
                            <div class="col-md-6">
                                <input type="text" placeholder="{{ translate('SKU') }}" name="sku"
                                    value="{{ old('sku', $product->sku ?? '') }}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <br>

                </div>
            </div>

        </div>

    </div> {{-- ✅ END row gutters-5 --}}

    <div class="card">
        @include('seller.product.products.partials.addons', [
        'addons' => old('addons', $addons ?? []),
        'oldAddonsJson' => old('addons', $addons ?? [])
        ])
    </div>

    </div>

</form>

<style>
    .addon-collapse-header {
        cursor: pointer;
    }

    .group-name {
        cursor: text !important;
    }

    .addon-arrow {
        font-size: 18px;
        transition: 0.2s;
    }

    .disabled-addon {
        opacity: 0.5;
        pointer-events: none;
    }

    input[type="file"]::-webkit-file-upload-button {
        display: none;
    }

    input[type="file"]::file-selector-button {
        display: none;
    }

    input[type="file"] {
        padding-left: 15px;
    }

    .product-form-field-error {
        display: block;
        margin-top: 6px;
        color: #d63031;
        font-size: 12px;
        line-height: 1.4;
    }

    .is-invalid-field,
    .is-invalid-field .dropdown-toggle {
        border-color: #d63031 !important;
    }

    #product-form-alert {
        border-radius: 8px;
        margin-bottom: 16px;
    }
</style>
@section('script')
<!-- Fallback-friendly script loading with sequential guarantee -->
<script>
    function loadScriptSequentially(urls, checkSuccess, callback, index = 0) {
        if (index >= urls.length) {
            console.error("Failed to load script from any of the sources:", urls);
            if (callback) callback(false);
            return;
        }

        if (checkSuccess && checkSuccess()) {
            if (callback) callback(true);
            return;
        }

        let src = urls[index];
        let script = document.createElement('script');
        script.src = src;
        script.async = false;
        script.onload = function() {
            if (!checkSuccess || checkSuccess()) {
                console.log("Successfully loaded script from: " + src);
                if (callback) callback(true);
            } else {
                console.warn("Script loaded from " + src + " but validation check failed. Trying next...");
                loadScriptSequentially(urls, checkSuccess, callback, index + 1);
            }
        };
        script.onerror = function() {
            console.warn("Failed to load script from: " + src + ". Trying next...");
            loadScriptSequentially(urls, checkSuccess, callback, index + 1);
        };
        document.head.appendChild(script);
    }

    // Initialize script loading once jQuery is ready
    if (typeof jQuery !== 'undefined') {
        initProductFormScripts();
    } else {
        document.addEventListener('DOMContentLoaded', function() {
            initProductFormScripts();
        });
    }

    function initProductFormScripts() {
        const hummingbirdUrls = [
            "{{ static_asset('assets/js/hummingbird-treeview.js') }}",
            "{{ asset('assets/js/hummingbird-treeview.js') }}",
            "/assets/js/hummingbird-treeview.js",
            "{{ static_asset('js/hummingbird-treeview.js') }}",
            "{{ asset('js/hummingbird-treeview.js') }}",
            "/js/hummingbird-treeview.js"
        ];

        const addonUrls = [
            "{{ static_asset('assets/js/product-addon.js') }}",
            "{{ asset('assets/js/product-addon.js') }}",
            "/assets/js/product-addon.js",
            "{{ static_asset('js/product-addon.js') }}",
            "{{ asset('js/product-addon.js') }}",
            "/js/product-addon.js"
        ];

        const formUrls = [
            "{{ static_asset('assets/js/seller-product-form.js') }}",
            "{{ asset('assets/js/seller-product-form.js') }}",
            "/assets/js/seller-product-form.js",
            "{{ static_asset('js/seller-product-form.js') }}",
            "{{ asset('js/seller-product-form.js') }}",
            "/js/seller-product-form.js"
        ];

        loadScriptSequentially(hummingbirdUrls, function() {
            return typeof jQuery !== 'undefined' && jQuery.fn && jQuery.fn.hummingbird;
        }, function(success) {
            loadScriptSequentially(addonUrls, null, function(success) {
                loadScriptSequentially(formUrls, null, function(success) {
                    console.log("All custom product form JS files loaded successfully!");
                    jQuery(document).trigger('seller-scripts-loaded');
                });
            });
        });
    }
</script>
@endsection
