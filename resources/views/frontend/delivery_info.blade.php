@extends('frontend.layouts.app')

@section('content')

    <section class="pt-5 mb-4">
        <div class="container">
            <style>
                .addon-toggle-btn {
                    background: #f5eee6 !important;
                    border: 1px solid #e2d2c0 !important;
                    color: #8b5e34 !important;
                    font-size: 13px !important;
                    font-weight: 600 !important;
                    border-radius: 6px !important;
                    padding: 6px 12px !important;
                    transition: all .3s ease;
                }

                .addon-toggle-btn:hover {
                    background: #8b5e34 !important;
                    color: #fff !important;
                }

                .addon-toggle-btn:focus {
                    box-shadow: none !important;
                }
            </style>
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="row gutters-5 sm-gutters-10">

                        <div class="col done">
                            <div class="text-center border border-bottom-6px p-2 text-success">
                                <i class="la-3x mb-2 las la-shopping-bag"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">
                                    <a href="{{ url('cart') }}">
                                        {{ translate('1. My Cart') }}
                                    </a>
                                </h3>
                            </div>
                        </div>

                        <div class="col done">
                            <div class="text-center border border-bottom-6px p-2 text-success">
                                <i class="la-3x mb-2 las la-map-marker"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">
                                    <a href="{{ url('checkout') }}">
                                        {{ translate('2. Shipping info') }}
                                    </a>
                                </h3>
                            </div>
                        </div>

                        <div class="col active">
                            <div class="text-center border border-bottom-6px p-2 text-primary">
                                <i class="la-3x mb-2 las la-shipping-fast cart-animate"
                                    style="margin-left:-100px;transition:2s;"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">
                                    {{ translate('3. Delivery info') }}
                                </h3>
                            </div>
                        </div>

                        <div class="col">
                            <div class="text-center border border-bottom-6px p-2">
                                <i class="la-3x mb-2 opacity-50 las la-wallet"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">
                                    {{ translate('4. Payment') }}
                                </h3>
                            </div>
                        </div>

                        <div class="col">
                            <div class="text-center border border-bottom-6px p-2">
                                <i class="la-3x mb-2 opacity-50 las la-clipboard-check"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">
                                    {{ translate('5. Confirmation') }}
                                </h3>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-4 gry-bg">
        <div class="container">
            <div class="row">
                <div class="col-xxl-12 col-xl-12 mx-auto">

                    <div class="border bg-white p-4 mb-4">

                        <form class="form-default" id="delivery-form" action="{{ route('checkout.store_delivery_info') }}"
                            method="POST">

                            @csrf

                            @php

                                $admin_products = [];
                                $seller_products = [];
                                $admin_product_variation = [];
                                $seller_product_variation = [];

                                foreach ($carts as $key => $cartItem) {
                                    $product = get_single_product($cartItem['product_id']);

                                    $variation = $cartItem['variation'];

                                    // Get stock row
                                    $stock = $product->stocks->where('variant', $variation)->first();

                                    $variant_price = $stock ? $stock->price : 0;

                                    $variation_data = [
                                        'product_id' => $cartItem['product_id'],
                                        'variation' => $variation,
                                        'price' => $variant_price,
                                        'sku' => $stock ? $stock->sku : '',
                                    ];

                                    if ($product->added_by == 'admin') {
                                        array_push($admin_products, $cartItem['product_id']);

                                        $admin_product_variation[] = $variation_data;
                                    } else {
                                        $product_ids = [];

                                        if (isset($seller_products[$product->user_id])) {
                                            $product_ids = $seller_products[$product->user_id];
                                        }

                                        array_push($product_ids, $cartItem['product_id']);

                                        $seller_products[$product->user_id] = $product_ids;

                                        $seller_product_variation[] = $variation_data;
                                    }
                                }

                                $pickup_point_list = [];

                                if (get_setting('pickup_point') == 1) {
                                    $pickup_point_list = get_all_pickup_points();
                                }
                            @endphp

                            {{-- SELLER PRODUCTS --}}
                            @if (!empty($seller_products))
                                @foreach ($seller_products as $seller_id => $seller_product)
                                    <div class="card border-0 rounded-0 shadow-none mb-4">

                                        <div class="card-header py-3 px-0 border-bottom-0">
                                            <h5 class="fs-16 fw-700 text-dark mb-0">
                                                {{ translate('Shop Name') }}: {{ get_shop_by_user_id($seller_id)->name }}
                                                - {{ translate('Products') }}
                                            </h5>
                                        </div>

                                        <div class="card-body p-0">
                                            <ul class="list-group list-group-flush border p-3 mb-3">
                                                @php
                                                    $physical = false;
                                                    $seller_subtotal = 0;
                                                @endphp

                                                @foreach ($seller_product as $key2 => $productId)
                                                    @php
                                                        $product = get_single_product($productId);

                                                        if ($product->digital == 0) {
                                                            $physical = true;
                                                        }

                                                        $cart = collect($carts)->firstWhere('product_id', $productId);

                                                        $price = 0;
                                                        $qty = 1;

                                                        if ($cart) {
                                                            $price =
                                                                cart_product_price($cart, $product, false, false) +
                                                                $cart['addon_price'];
                                                            $qty = $cart['quantity'];
                                                            $seller_subtotal += $price * $qty;
                                                        }
                                                    @endphp

                                                    <li class="list-group-item py-3 px-0" style="padding: 0; border: none;">
                                                        <div class="d-flex d-lg-flex flex-column flex-lg-row w-100 w-lg-auto"
                                                            style="gap:20px;">
                                                            <div class="flex-shrink-0" style="min-width: 120px;">
                                                                <img src="{{ get_image($product->thumbnail) }}"
                                                                    alt="{{ $product->getTranslation('name') }}"
                                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                                    style="object-fit: cover;width:120px;height:120px;border-radius:4px;display:block;">
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="fw-700 fs-15 mb-1" style="margin-bottom:5px;">
                                                                    {{ $product->getTranslation('name') }}
                                                                </div>

                                                                {{-- Variations & Addons (Toggleable) --}}
                                                                @php
                                                                    $hasVariation = !empty(
                                                                        $seller_product_variation[$key2]['variation']
                                                                    );
                                                                    $cartItem_addons = [];
                                                                    if (!empty($cart->addons)) {
                                                                        $cartItem_addons = json_decode(
                                                                            $cart->addons,
                                                                            true,
                                                                        );
                                                                    }
                                                                    $hasAddons = !empty($cartItem_addons);
                                                                    $toggleId =
                                                                        'addonCollapseDelivery' .
                                                                        ($seller_id ?? '') .
                                                                        ($key2 ?? '') .
                                                                        ($cart->id ?? uniqid());
                                                                @endphp

                                                                @if ($hasVariation || $hasAddons)
                                                                    <button
                                                                        class="btn btn-sm mt-2 addon-toggle-btn d-flex align-items-center mb-2"
                                                                        type="button" data-toggle="collapse"
                                                                        data-target="#{{ $toggleId }}"
                                                                        aria-expanded="false"
                                                                        aria-controls="{{ $toggleId }}"
                                                                        style="background: none; border: none;">
                                                                        <i class="las la-plus-circle me-1"></i>
                                                                        <span class="flex-grow-1 text-left">
                                                                            {{ translate('View Details') }}
                                                                            @if ($hasAddons)
                                                                                ({{ count($cartItem_addons) }})
                                                                            @endif
                                                                        </span>
                                                                        <i class="las la-angle-down addon-arrow"></i>
                                                                    </button>
                                                                    <div class="collapse mt-2" id="{{ $toggleId }}">
                                                                        <div
                                                                            class="addon-details d-flex flex-column gap-1 mt-2 p-0">
                                                                            {{-- Variation Table --}}
                                                                            @if ($hasVariation)
                                                                                <table class="table table-sm mb-2 w-100">
                                                                                    <tr>
                                                                                        <th class="fw-600"
                                                                                            style="width:70%">
                                                                                            {{ translate('Variation') }}
                                                                                        </th>
                                                                                        <th class="fw-600"
                                                                                            style="width:30%">
                                                                                            {{ translate('Price') }}
                                                                                        </th>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            {{ $seller_product_variation[$key2]['variation'] }}
                                                                                        </td>
                                                                                        <td>
                                                                                            @if ($seller_product_variation[$key2]['price'] > 0)
                                                                                                +{{ single_price($seller_product_variation[$key2]['price']) }}
                                                                                            @else
                                                                                                {{ single_price(0) }}
                                                                                            @endif
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            @endif

                                                                            {{-- Addon Table --}}
                                                                            @if ($hasAddons)
                                                                                <table
                                                                                    class="table table-sm mb-1 addon-table w-100">
                                                                                    <tr>
                                                                                        <th class="fw-600 addon-name-text addon-header"
                                                                                            style="width:70%">
                                                                                            {{ translate('Addons Selected') }}
                                                                                        </th>
                                                                                        <th class="fw-600 addon-price-text addon-header"
                                                                                            style="width:30%">
                                                                                            {{ translate('Pricing') }}
                                                                                        </th>
                                                                                    </tr>
                                                                                    @foreach ($cartItem_addons as $addon)
                                                                                        <tr>
                                                                                            <td>
                                                                                                {{ $addon['addon_name'] ?? '' }}
                                                                                                @if (!empty($addon['name']))
                                                                                                    <span
                                                                                                        class="mx-2 text-secondary addon-separator">|</span>
                                                                                                    {{ $addon['name'] ?? '' }}
                                                                                                @endif
                                                                                            </td>
                                                                                            <td>
                                                                                                @if (isset($addon['price']) && floatval($addon['price']) > 0)
                                                                                                    +£{{ number_format($addon['price'], 2) }}
                                                                                                @else
                                                                                                    <span
                                                                                                        class="text-success">{{ translate('Free of cost') }}</span>
                                                                                                @endif
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </table>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                                <div class="mb-1"><span
                                                                        class="fw-600">{{ translate('Qty') }}:</span>
                                                                    {{ $qty }}</div>

                                                                @if ($product->dispatch_time)
                                                                    <div><span
                                                                            class="fw-600">{{ translate('Dispatch Time') }}:</span>
                                                                        {{ $product->dispatch_time }}</div>
                                                                @endif
                                                            </div>

                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>

                                            {{-- SERVICES SECTION --}}
                                            @php
                                                $allServices = collect();
                                            @endphp

                                            @foreach ($seller_product as $productId)
                                                @php
                                                    $serviceProduct = get_single_product($productId);

                                                    if (
                                                        $serviceProduct &&
                                                        isset($serviceProduct->checkoutServices) &&
                                                        $serviceProduct->checkoutServices->count() > 0
                                                    ) {
                                                        $allServices = $allServices->merge(
                                                            $serviceProduct->checkoutServices,
                                                        );
                                                    }
                                                @endphp
                                            @endforeach

                                            @php
                                                $allServices = $allServices->unique('id')->values();
                                            @endphp

                                            @if ($allServices->count() > 0)
                                                <div class="border-top border-bottom px-3 py-4 mb-3 bg-light">

                                                    <h5 class="fw-700 fs-15 mb-3">
                                                        {{ translate('Additional Services') }}
                                                    </h5>

                                                    {{-- Error message for service selection --}}
                                                    <div id="service-required-error"
                                                        class="alert alert-danger mb-3 px-3 py-2 fw-600 d-flex align-items-center d-none"
                                                        style="font-size: 15px;">
                                                        <span class="mr-2" style="font-size: 1.4em;"><i
                                                                class="las la-exclamation-circle"></i></span>
                                                        <span>
                                                            {{ translate('Please select at least one service to continue.') }}
                                                        </span>
                                                    </div>

                                                    <div class="row">
                                                        @foreach ($allServices as $service)
                                                            <div class="col-md-6 mb-3">
                                                                <label class="aiz-megabox d-block bg-white mb-0">
                                                                    <input type="checkbox" name="selected_services[]"
                                                                        value="{{ $service->id }}"
                                                                        class="service-checkbox"
                                                                        data-price="{{ $service->price }}">
                                                                    <span class="d-flex aiz-megabox-elem rounded-0 p-3">
                                                                        <span
                                                                            class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                                        <span class="flex-grow-1 pl-3">
                                                                            <span class="d-block fw-700 fs-14 text-dark">
                                                                                {{ $service->name }}
                                                                            </span>
                                                                            @if (!empty($service->description))
                                                                                <span
                                                                                    class="d-block fs-14 text-muted mt-1">
                                                                                    {{ $service->description }}
                                                                                </span>
                                                                            @endif
                                                                            <span
                                                                                class="d-block fs-13 fw-600 text-primary mt-2">
                                                                                {{ ucfirst(str_replace('_', ' ', $service->type)) }}
                                                                                -
                                                                                {{ single_price($service->price) }}
                                                                            </span>
                                                                        </span>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                </div>
                                            @endif

                                            <!-- Seller Subtotal -->
                                            <div class="text-right pr-3 pb-2 ms-5 fw-700">
                                                {{ translate('Subtotal') }} :
                                                <span id="seller-subtotal">
                                                    {{ single_price($seller_subtotal) }}
                                                </span>
                                            </div>

                                            <!-- Services Total -->
                                            <div class="text-right pr-3 pb-2 ms-5 fw-700">
                                                {{ translate('Services') }} :
                                                <span id="services-total">
                                                    £0.00
                                                </span>
                                            </div>

                                            <!-- Grand Total -->
                                            <div class="text-right pr-3 pb-3 ms-5 fw-700 fs-18">
                                                {{ translate('Total') }} :
                                                <span id="grand-total" data-base-total="{{ $seller_subtotal }}">
                                                    {{ single_price($seller_subtotal) }}
                                                </span>
                                            </div>
                                        </div> {{-- end card-body --}}
                                    </div>
                                @endforeach
                            @endif



                    </div>

                    <div class="checkout-btn-row mb-4 d-flex flex-wrap justify-content-between">
                        <!-- Return to shop -->
                        <div class="mb-2 mb-md-0 w-100 w-lg-auto">
                            <a href="{{ route('home') }}"
                                class="btn btn-outline-secondary  borderbtn fs-15 fw-600 rounded-2 w-100 py-3 custom_checkout_button_design filled">
                                <i class="las la-arrow-left fs-17"></i>
                                {{ translate('Return to shop') }}
                            </a>
                        </div>
                        <!-- Continue to Payment -->
                        <div class="w-100 w-lg-auto">
                            <button type="submit" id="continue-to-payment-btn"
                                class="btn fs-15 fw-600 borderbtn rounded-2 w-100 py-3 border-none custom_checkout_button_design unfilled">
                                {{ translate('Continue to Payment') }}
                            </button>
                        </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </section>
    <style>
        #continue-to-payment-btn[disabled] {
            background: #d9cbbb !important;
            cursor: no-drop;
            outline: none !important;

        }

        #continue-to-payment-btn:focus-visible {
            outline: none !important;
        }
    </style>
@endsection


@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            let $serviceCheckboxes = $('.service-checkbox');
            let $continueBtn = $('#continue-to-payment-btn');
            let $serviceError = $('#service-required-error');

            // Ensure only one checkbox can be selected at a time
            $serviceCheckboxes.on('change', function() {
                // Uncheck all others
                $serviceCheckboxes.not(this).prop('checked', false);

                // Update service and grand total
                let serviceTotal = 0;
                let $checked = $('.service-checkbox:checked');
                if ($checked.length > 0) {
                    serviceTotal = parseFloat($checked.data('price')) || 0;
                }
                let baseTotal = parseFloat($('#grand-total').data('base-total'));
                let finalTotal = baseTotal + serviceTotal;

                $('#services-total').html('£' + serviceTotal.toFixed(2));
                $('#grand-total').html('£' + finalTotal.toFixed(2));

                checkServiceSelection();
            });

            // If there is a free service, select it by default
            let $freeService = $serviceCheckboxes.filter(function() {
                return parseFloat($(this).data('price')) === 0;
            });
            if ($freeService.length > 0) {
                $serviceCheckboxes.prop('checked', false); // uncheck all
                $freeService.first().prop('checked', true).trigger('change');
            }

            // Hide or show button depending on if any service-checkbox must be chosen
            function checkServiceSelection() {
                if ($serviceCheckboxes.length > 0) {
                    // Services section present
                    if ($('.service-checkbox:checked').length > 0) {
                        $continueBtn.removeAttr('disabled').show();
                        $serviceError.addClass('d-none');
                    } else {
                        $continueBtn.attr('disabled', 'disabled').show();
                    }
                } else {
                    // No services - button is always visible
                    $continueBtn.show();
                    $serviceError.addClass('d-none');
                }
            }

            // Initial state
            checkServiceSelection();

            // Prevent form submission if services required and none checked
            $('#delivery-form').on('submit', function(e) {
                if ($serviceCheckboxes.length > 0 && $('.service-checkbox:checked').length === 0) {
                    $serviceError.removeClass('d-none');
                    $continueBtn.hide();
                    e.preventDefault();
                }
            });

            // If only one (free) option present, prevent unchecking it
            if ($serviceCheckboxes.length === 1 && parseFloat($serviceCheckboxes.first().data('price')) === 0) {
                $serviceCheckboxes.on('click', function(e) {
                    if ($(this).is(':checked')) {
                        e.preventDefault();
                        // always keep checked if it's the only (free) option
                        $(this).prop('checked', true);
                    }
                });
            }
        });

        function display_option(key) {

        }

        function show_pickup_point(el, type) {

            var value = $(el).val();

            var target = $(el).data('target');

            if (value == 'home_delivery' || value == 'carrier') {

                if (!$(target).hasClass('d-none')) {
                    $(target).addClass('d-none');
                }

                $('.carrier_id_' + type).removeClass('d-none');

            } else {

                $(target).removeClass('d-none');

                $('.carrier_id_' + type).addClass('d-none');
            }
        }
    </script>
@endsection
