<div class="container my-4">
    @if ($carts && count($carts) > 0)
        <div class="row justify-content-center">
            <div class="col-xxl-12 col-xl-10">
                <div class="border shadow-sm p-3 p-lg-4 bg-white maincontainer">
                    <div class="mb-4">
                        <!-- Headers for desktop -->
                        <div class="row d-none d-lg-flex border-bottom bg-cart-header text-white fs-14 py-3 px-2 rounded-2">
                            <div class="col-md-1 fw-bold">#</div>
                            <div class="col-md-4 fw-bold">{{ translate('Product') }}</div>
                            <div class="col-md-2 fw-bold" style="text-align:right ; ">{{ translate('Price') }}</div>
                            <div class="col-md-2 fw-bold">{{ translate('Quantity') }}</div>
                            <div class="col-md-2 fw-bold">{{ translate('Total') }}</div>
                            <div class="col-md-1 fw-bold text-end">{{ translate('Remove') }}</div>
                        </div>

                        <!-- Cart Items (responsive) -->
                        <ul class="list-group list-group-flush px-0 cart-list-responsive">
                            @php
                                $total = 0;
                                $i = 1;
                            @endphp
                            @foreach ($carts as $key => $cartItem)
                                @php
                                    $product = get_single_product($cartItem['product_id']);
                                    $product_stock = $product->stocks->where('variant', $cartItem['variation'])->first();
                                    $product_name_with_choice = $product->getTranslation('name');
                                    if ($cartItem['variation'] != null) {
                                        $product_name_with_choice = $product->getTranslation('name') . ' - ' . $cartItem['variation'];
                                    }
                                    $total_addon = $cartItem['addon_price'] * $cartItem['quantity'];
                                    // Attribute price calculation
                                    $attribute_price = 0;
                                    $cartItem_attributes = [];
                                    if (!empty($cartItem->attributes)) {
                                        $cartItem_attributes = json_decode($cartItem->attributes, true);
                                        if(is_array($cartItem_attributes)) {
                                            foreach($cartItem_attributes as $att) {
                                                if(isset($att['price'])){
                                                    $attribute_price += floatval($att['price']);
                                                }
                                            }
                                        }
                                    }
                                    $total += (cart_product_price($cartItem, $product, false) + $attribute_price) * $cartItem['quantity'];
                                    $total += $total_addon;
                                    $cartItem_addons = [];
                                    if (!empty($cartItem->addons)) {
                                        $cartItem_addons = json_decode($cartItem->addons, true);
                                    }
                                @endphp

                                <!-- Responsive Cart row -->
                                <li class="list-group-item p-0 cart-item-row position-relative border-0 border-bottom">
                                    <div class="row gx-2 align-items-start d-none d-lg-flex mt-5">
                                        <!-- Item # -->
                                        <div class="col-1 d-flex justify-content-center align-items-start fw-bold">{{ $i }}</div>

                                        <!-- Product Image & Name -->
                                        <div class="col-lg-4 d-flex align-items-start mb-2 mb-lg-0 flex-column min-w-0 product-col">
                                            <!-- Image on top -->
												<div class="w-100 mb-2">
													<img src="{{ uploaded_asset($product->thumbnail_img) }}"
														class="img-fit rounded w-50"
														style="height:100px;object-fit:cover;"
														alt="{{ $product->getTranslation('name') }}"
														onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
												</div>

												<!-- Name below image -->
												<div class="w-100">
													<span class="fs-16 fw-bold d-block product-name-text">{{ $product_name_with_choice }}</span>
												</div>


                                            <!-- Selected Attributes -->
                                            @if(!empty($cartItem_attributes) && count($cartItem_attributes) > 0)
                                                <div class="attribute-details mt-2 w-100">
                                                    @foreach($cartItem_attributes as $attribute)
                                                        <div class="attribute-item text-primary" style="font-size: 13px;">
                                                            <strong>
                                                                {{ $attribute['attribute_name'] ?? '' }}
                                                                @if(!empty($attribute['option_name']))
                                                                    : {{ $attribute['option_name'] }}
                                                                @endif
                                                            </strong>
                                                            @if(isset($attribute['price']) && $attribute['price'] > 0)
                                                                (<span class="text-dark">+ £{{ number_format($attribute['price'], 2) }}</span>)
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <!-- Addons -->
                                            @if(count($cartItem_addons) > 0)
                                                <!-- Toggle Button -->
                                                <button class="btn btn-sm mt-2 addon-toggle-btn d-flex align-items-center w-100"
                                                    type="button"
                                                    data-toggle="collapse"
                                                    data-target="#addonCollapseDesktop{{ $cartItem['id'] }}"
                                                    aria-expanded="false"
                                                    aria-controls="addonCollapseDesktop{{ $cartItem['id'] }}">
                                                    <i class="las la-plus-circle me-1"></i>
                                                    <span class="flex-grow-1 text-left">{{ translate('View Addons') }} ({{ count($cartItem_addons) }})</span>
                                                    <i class="las la-angle-down addon-arrow"></i>
                                                </button>

                                                <!-- Collapse Addons -->
                                                <div class="collapse mt-2 w-100" id="addonCollapseDesktop{{ $cartItem['id'] }}">
                                                    <div class="addon-details d-flex flex-column gap-1 px-2 py-2">
                                                        <div class="fw-bold fs-13 mb-1 text-uppercase addon-header">
                                                            {{ translate('Addons Selected') }}
                                                        </div>

                                                        @foreach ($cartItem_addons as $addon)
                                                            <div class="addon-item d-flex align-items-start py-2">
                                                                <span class="me-2 flex-shrink-0 addon-icon">
                                                                    <i class="las la-check-circle"></i>
                                                                </span>
                                                                <div class="flex-grow-1 min-w-0">
                                                                    <div class="d-flex justify-content-between align-items-start addon-row">
                                                                        <span class="fw-600 text-dark addon-name-text">
                                                                            {{ $addon['addon_name'] ?? '' }}
                                                                            @if(isset($addon['name']))
                                                                                <span class="mx-2 text-secondary addon-separator">|</span>
                                                                                {{ $addon['name'] ?? '' }}
                                                                            @endif
                                                                        </span>
                                                                        @if(isset($addon['price']) && floatval($addon['price']) > 0)
                                                                            <span class="ms-2 text-dark addon-price-text flex-shrink-0">
                                                                                +£{{ number_format($addon['price'], 2) }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Price -->
                                        <div class="col-lg-2 mb-2 mb-lg-0 text-lg-center">
                                            @php
                                                $base_price = cart_product_price($cartItem, $product, false);
                                                $unit_attribute_price = $attribute_price;
                                                $price_for_display = $base_price + $unit_attribute_price;
                                            @endphp
                                            <span class="fw-700 fs-14">{{ single_price($price_for_display) }}</span>
                                        </div>

                                        <!-- Quantity -->
                                        <div class="col-lg-2 d-flex align-items-center justify-content-center">
                                            @if ($cartItem['digital'] != 1 && $product->auction_product == 0)
                                                <div class="quantity-group" style="max-width:110px;">
                                                    <div class="d-flex flex-wrap input-group input-group-sm">
                                                        <button class="btn btn-outline-secondary border-0 px-2"
                                                            type="button" data-type="minus"
                                                            onclick="handleCartQuantity(this, {{ $cartItem['id'] }}, 'minus')"
                                                            style="background:#f3f3f5; color:#555;"
                                                            @if($cartItem['quantity'] <= 1) disabled @endif>
                                                            <i class="las la-minus"></i>
                                                        </button>
                                                        <input type="number"
                                                            name="quantity[{{ $cartItem['id'] }}]"
                                                            class="form-control text-center fw-bold fs-15 border-0 p-0 cart-qty-input"
                                                            value="{{ $cartItem['quantity'] }}"
                                                            min="{{ $product->min_qty }}"
                                                            max="{{ $product_stock->qty ?? 1 }}"
                                                            onchange="updateQuantity({{ $cartItem['id'] }}, this)"
                                                            style="max-width:46px;height:32px;">
                                                        <button class="btn btn-outline-secondary border-0 px-2"
                                                            type="button" data-type="plus"
                                                            onclick="handleCartQuantity(this, {{ $cartItem['id'] }}, 'plus')"
                                                            style="background:#f3f3f5; color:#555;"
                                                            @if($cartItem['quantity'] >= ($product_stock->qty ?? 1)) disabled @endif>
                                                            <i class="las la-plus"></i>
                                                        </button>
                                                    </div>
                                                    <div>
                                                        <div class="text-danger fs-10 ms-2 mt-2">
                                                            {{ translate('Only 1 item left in stock. More coming soon!') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($product->auction_product == 1)
                                                <span class="fw-700 fs-15">1</span>
                                            @endif
                                        </div>

                                        <!-- Total -->
                                        <div class="col-lg-2 text-lg-center mb-2 mb-lg-0">
                                            <span class="fw-700 fs-16 text-primary">
                                                {{ single_price(($base_price + $unit_attribute_price) * $cartItem['quantity'] + $total_addon) }}
                                            </span>
                                        </div>

                                        <!-- Remove -->
                                        <div class="col-lg-1 d-flex justify-content-end">
                                            <a href="javascript:void(0)"
                                               onclick="removeFromCartView(event, {{ $cartItem['id'] }})"
                                               class="btn btn-outline-danger btn-sm rounded-circle confirm-delete"
                                               style="width:34px;height:34px;display:flex;align-items:center;justify-content:center;">
                                                <i class="las la-trash fs-16"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Mobile view -->
                                    <div class="d-block d-lg-none">
                                        <div class="row mb-2">
                                            <div class="col-12">
                                                <div class="fw-bold fs-18 border-bottom pb-2 mb-2">{{ 'Product Name' }}</div>
                                                <div class="d-flex align-items-start min-w-0">
                                                    <img src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                         class="img-fit rounded my-2 flex-shrink-0" style="width:120px;height:70px;object-fit:cover;"
                                                         alt="{{ $product->getTranslation('name') }}"
                                                         onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                    <div class="flex-grow-1 min-w-0 ml-2">
                                                        <span class="fs-18 fw-bold d-block product-name-text">{{ $product_name_with_choice }}</span>
                                                    </div>
                                                </div>

                                                <!-- Selected Attributes -->
                                                @if(!empty($cartItem_attributes) && count($cartItem_attributes) > 0)
                                                    <div class="attribute-details mt-1">
                                                        @foreach($cartItem_attributes as $attribute)
                                                            <div class="attribute-item text-primary" style="font-size: 13px;">
                                                                <strong>
                                                                    {{ $attribute['attribute_name'] ?? '' }}
                                                                    @if(!empty($attribute['option_name']))
                                                                        : {{ $attribute['option_name'] }}
                                                                    @endif
                                                                </strong>
                                                                @if(isset($attribute['price']) && $attribute['price'] > 0)
                                                                    (<span class="text-dark">+ £{{ number_format($attribute['price'], 2) }}</span>)
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                <!-- Addons -->
                                                @if(count($cartItem_addons) > 0)
                                                    <button class="btn btn-sm mt-2 addon-toggle-btn w-100 d-flex align-items-center"
                                                        type="button"
                                                        data-toggle="collapse"
                                                        data-target="#addonCollapseMobile{{ $cartItem['id'] }}"
                                                        aria-expanded="false"
                                                        aria-controls="addonCollapseMobile{{ $cartItem['id'] }}">
                                                        <i class="las la-plus-circle me-1"></i>
                                                        <span class="flex-grow-1 text-left">{{ translate('View Addons') }} ({{ count($cartItem_addons) }})</span>
                                                        <i class="las la-angle-down addon-arrow"></i>
                                                    </button>

                                                    <div class="collapse mt-2" id="addonCollapseMobile{{ $cartItem['id'] }}">
                                                        <div class="addon-details d-flex flex-column gap-1 mt-2 px-2 py-2">
                                                            <div class="fw-bold fs-13 mb-1 text-uppercase addon-header">
                                                                {{ translate('Addons Selected') }}
                                                            </div>
                                                            @foreach ($cartItem_addons as $addon)
                                                                <div class="addon-item d-flex align-items-start py-1">
                                                                    <span class="mr-2 flex-shrink-0 addon-icon">
                                                                        <i class="las la-check-circle"></i>
                                                                    </span>
                                                                    <div class="flex-grow-1 min-w-0">
                                                                        <div class="d-flex justify-content-between align-items-start addon-row">
                                                                            <span class="fw-600 text-dark addon-name-text">
                                                                                {{ $addon['addon_name'] ?? '' }}
                                                                                @if(isset($addon['name']))
                                                                                    <span class="mx-2 text-secondary addon-separator">|</span>
                                                                                    {{ $addon['name'] ?? '' }}
                                                                                @endif
                                                                            </span>
                                                                            @if(isset($addon['price']) && floatval($addon['price']) > 0)
                                                                                <span class="ms-2 p-2 text-dark addon-price-text flex-shrink-0">
                                                                                    +£{{ number_format($addon['price'], 2) }}
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row mb-2 align-items-center">
                                            <div class="col-12">
                                                <div class="fw-bold fs-18 border-bottom pb-2 mb-2">{{ translate('Price') }}</div>
                                                <span class="fw-700 fs-14">{{ single_price($base_price + $attribute_price) }}</span>
                                            </div>
                                        </div>

                                        <div class="row mb-2 align-items-center">
                                            <div class="col-12">
                                                <div class="fw-bold">{{ translate('Quantity') }}</div>
                                                @if ($cartItem['digital'] != 1 && $product->auction_product == 0)
                                                    <div class="quantity-group" style="max-width:110px;">
                                                        <div class="d-flex flex-wrap input-group input-group-sm">
                                                            <button class="btn btn-outline-secondary border-0 px-2"
                                                                type="button" data-type="minus"
                                                                onclick="handleCartQuantity(this, {{ $cartItem['id'] }}, 'minus')"
                                                                style="background:#f3f3f5; color:#555;"
                                                                @if($cartItem['quantity'] <= 1) disabled @endif>
                                                                <i class="las la-minus"></i>
                                                            </button>
                                                            <input type="number"
                                                                name="quantity[{{ $cartItem['id'] }}]"
                                                                class="form-control text-center fw-bold fs-15 border-0 p-0 cart-qty-input"
                                                                value="{{ $cartItem['quantity'] }}"
                                                                min="{{ $product->min_qty }}"
                                                                max="{{ $product_stock->qty ?? 1 }}"
                                                                onchange="updateQuantity({{ $cartItem['id'] }}, this)"
                                                                style="max-width:46px;height:32px;">
                                                            <button class="btn btn-outline-secondary border-0 px-2"
                                                                    type="button" data-type="plus"
                                                                    onclick="handleCartQuantity(this, {{ $cartItem['id'] }}, 'plus')"
                                                                    style="background:#f3f3f5; color:#555;"
                                                                    @if($cartItem['quantity'] >= ($product_stock->qty ?? 1)) disabled @endif>
                                                                <i class="las la-plus"></i>
                                                            </button>
                                                        </div>
                                                        <div>
                                                            <div class="text-danger fs-10 ms-2 mt-2">
                                                                {{ translate('Only 1 item left in stock. More coming soon!') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif($product->auction_product == 1)
                                                    <span class="fw-700 fs-15">1</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row mb-2 align-items-center">
                                            <div class="col-12">
                                                <div class="fw-bold fs-18 border-bottom pb-2 mb-2">{{ translate('Total') }}</div>
                                                <span class="fw-700 fs-16 text-primary">
                                                    {{ single_price(($base_price + $attribute_price) * $cartItem['quantity'] + $total_addon) }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="row align-items-center">
                                            <div class="col-12">
                                            <div class="fw-bold fs-18 border-bottom pb-2 mb-2">{{ translate('Remove') }}</div>
                                                <a href="javascript:void(0)"
                                                    onclick="removeFromCartView(event, {{ $cartItem['id'] }})"
                                                    class="btn btn-outline-danger btn-sm rounded-circle confirm-delete"
                                                    style="width:34px;height:34px;display:flex;align-items:center;justify-content:center;">
                                                    <i class="las la-trash fs-16"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="border-bottom mt-2 mb-3"></div>
                                    </div>
                                </li>
                                @php $i++; @endphp
                            @endforeach
                        </ul>
                    </div>

                    <!-- Subtotal -->
                    <div class="px-0 py-3 mb-4 border-top d-flex justify-content-between align-items-center">
                        <span class="opacity-70 fs-20 text-black">{{ translate('Subtotal') }}</span>
                        <span style="font-weight: 700;" class="fs-20 text-dark">{{ single_price($total) }}</span>
                    </div>

                    <div class="row g-2">
                        <!-- Return to shop -->
                        <div class="col-12 col-md-6 mb-2 mb-md-0">
                            <a href="{{ route('home') }}"
                                class="btn borderbtn fs-14 fw-700 rounded-0 w-100 w-md-auto py-3">
                                <i class="las la-arrow-left fs-17"></i>
                                {{ translate('Return to shop') }}
                            </a>
                        </div>
                        <!-- Continue to Shipping -->
                        <div class="col-6 col-md-6 text-center text-md-right">
                            @if(Auth::check())
                                <a href="{{ route('checkout.shipping_info') }}"
                                   class="btn borderbtn fs-14 fw-700 rounded-0 w-100 w-md-auto py-3">
                                    {{ ('Complete Order')}}
                                </a>
                            @else
                                <button onclick="showLoginModal()"
                                    class="btn borderbtn fs-14 fw-700 rounded-0 w-100 w-md-auto py-3">
                                    {{ ('Complete Order')}}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="border bg-white rounded-3 p-5 shadow-sm">
                    <!-- Empty cart -->
                    <div class="text-center">
                        <i class="las la-frown la-3x opacity-60 mb-3"></i>
                        <h3 class="h4 fw-700">{{ translate('Your Cart is empty') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script type="text/javascript">
function handleCartQuantity(btn, cartId, type) {
    let group = btn.closest('.quantity-group');
    if (!group) return;
    let inp = group.querySelector('input[type=number]');
    if (!inp) return;
    let qty = parseInt(inp.value, 10);
    let min = parseInt(inp.min, 10) || 1;
    let max = parseInt(inp.max, 10) || 1;

    if (type === 'plus' && qty < max) {
        qty += 1;
        inp.value = qty;
        updateQuantity(cartId, inp);
    }
    if (type === 'minus' && qty > min) {
        qty -= 1;
        inp.value = qty;
        updateQuantity(cartId, inp);
    }
}

// Arrow rotation for collapse
$(document).ready(function(){
    $('.collapse').on('show.bs.collapse', function () {
        $(this).prev('.addon-toggle-btn').find('.addon-arrow').addClass('la-rotate-180');
    });
    $('.collapse').on('hide.bs.collapse', function () {
        $(this).prev('.addon-toggle-btn').find('.addon-arrow').removeClass('la-rotate-180');
    });
});
</script>

<style>
    :focus-visible{
        outline: none !important;
    }
    .bg-cart-header {
        background: #877561 !important;
        border-radius: 6px 6px 0 0;
    }
    .maincontainer {
        background: #fdfdfc;
    }
    .cart-item-row {
        transition: box-shadow .15s;
    }
    .cart-item-row:hover {
        box-shadow: 0 2px 8px rgba(124, 113, 94, .05);
        background: #faf8f3;
    }
    .addon-details .addon-item {
        margin-bottom: 3px;
    }
    .attribute-details .attribute-item {
        margin-bottom: 3px;
    }

    @media (max-width: 991.98px) {
        .bg-cart-header > div {
            font-size:12px !important;
        }
        .cart-item-row .col-lg-4, .cart-item-row .col-lg-2 {
            font-size:14px !important;
        }
        .cart-item-row {
            padding: 1rem .5rem;
        }
        .maincontainer { padding:1rem !important; }
    }

    .quantity-group .btn {
        border: 1px solid #e7e7ed !important;
        min-width: 32px;
        min-height: 32px;
        background: #f7f7fa;
    }
    .quantity-group .form-control {
        box-shadow: none;
        border-radius: 0;
    }

    @media (max-width: 991.98px) {
        .cart-list-responsive .cart-item-row {
            border-radius: 10px;
            background: #fff;
            margin-bottom: 12px;
            box-shadow: 0 2px 6px rgba(121, 96, 52, .07);
        }
        .cart-list-responsive .cart-item-row .row {
            margin-left: 0;
            margin-right: 0;
        }
    }

    @media (max-width: 767.98px) {
        .d-lg-flex, .d-lg-block, .d-lg-inline, .d-lg-inline-block {
            display: none !important;
        }
        .d-lg-none {
            display: block !important;
        }
        .cart-list-responsive .fw-bold {
            font-weight: 600 !important;
        }
    }

    a:-webkit-any-link:focus-visible {
        outline: none !important;
    }
    .remove {
        margin-right: 20px;
    }
    .list {
        margin-left: 10px;
    }
    @media (max-width: 767px) {
        .list {
            display: none;
        }
    }
    @media (max-width: 767px) {
        .borderbtn {
            font-size: 15px;
        }
    }
    .row>div {
        padding-left: 6px;
        padding-right: 6px;
    }
    @media (min-width: 768px) {
        .desktop-auto {
            width: auto !important;
        }
    }

	.addon-toggle-btn{
        background:#f5eee6;
        border:1px solid #e2d2c0;
        color:#8b5e34;
        font-size:13px;
        font-weight:600;
        border-radius:6px;
        padding:6px 12px;
        transition:all .3s ease;
    }
    .addon-toggle-btn:hover{
        background:#8b5e34;
        color:#fff;
    }
    .addon-toggle-btn:focus{
        box-shadow:none !important;
    }

    /* Text wrap fixes */
    .product-name-text {
        word-wrap: break-word;
        word-break: break-word;
        white-space: normal;
        overflow-wrap: anywhere;
        line-height: 1.4;
    }
    .addon-name-text {
        word-wrap: break-word;
        word-break: break-word;
        white-space: normal;
        overflow-wrap: anywhere;
        flex: 1 1 auto;
        min-width: 0;
    }
    .addon-price-text {
        white-space: nowrap;
        flex: 0 0 auto;
        margin-left: auto;
    }
    .addon-row {
        gap: 8px;
        flex-wrap: wrap;
    }
    .min-w-0 {
        min-width: 0;
    }
    .la-rotate-180 {
        transform: rotate(180deg);
        transition: transform 0.2s ease;
    }

    /* Addon box alignment fix */
    .product-col {
        width: 100% !important;
        max-width: 100%;
    }
    .addon-details {
        width: 100% !important;
        max-width: 100%;
        box-sizing: border-box;
        background:#f9f6f3;
        border-radius:8px;
        box-shadow:0 1px 4px rgba(181,122,69,.08);
    }
    .collapse {
        width: 100% !important;
    }
    .addon-header {
        letter-spacing:.08em;
        color:#b57a45;
    }
    .addon-item {
        border-bottom:1px solid #f3e7db;
        font-size:13px;
        color:#74542f;
    }
    .addon-item:last-child {
        border-bottom: 0;
    }
    .addon-icon {
        display:inline-block;
        width:18px;
        height:18px;
        background:#fff4e5;
        border-radius:4px;
        text-align:center;
        line-height:20px;
    }
    .addon-icon i {
        color:#28a745;
        font-size:15px;
    }
    .addon-separator {
        opacity:.7;
    }
	.borderbtn {
    border-radius: 12px !important; /* change 12px to whatever you want */
}
</style>

<script type="text/javascript">
    if (typeof window !== "undefined" && window.AIZ && window.AIZ.extra && typeof window.AIZ.extra.plusMinus === 'function') {
        window.AIZ.extra.plusMinus();
    }
</script>
