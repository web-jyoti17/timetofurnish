@extends('frontend.layouts.app')

@section('content')

    <section class="pt-5 mb-4 cart_tabs">
        <div class="container">
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
                                <i class="la-3x mb-2 las la-shipping-fast cart-animate"></i>
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

    <section class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-12 col-xl-10">
                    <div class="border shadow-sm p-3 p-lg-4 bg-white delivery-maincontainer">

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

                                        <div class="card-header py-3 px-0 border-bottom-0" style="background: transparent;">
                                            <h5 class="fs-16 fw-700 text-dark mb-0">
                                                {{ translate('Shop Name') }}: {{ get_shop_by_user_id($seller_id)->name }}
                                                - {{ translate('Products') }}
                                            </h5>
                                        </div>

                                        <div class="card-body p-0">
                                            <div class="mb-4">
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
                                                        $hasVariation = false;
                                                        $cartItem_addons = [];
                                                        $cartItem_attributes = [];
                                                        $hasAddons = false;

                                                        if ($cart) {
                                                            $cartItem_addons = !empty($cart->addons)
                                                                ? json_decode($cart->addons, true)
                                                                : [];
                                                            $cartItem_attributes = !empty($cart->attributes)
                                                                ? json_decode($cart->attributes, true)
                                                                : [];

                                                            $attributeNames = [];
                                                            if (is_array($cartItem_attributes)) {
                                                                foreach ($cartItem_attributes as $attr) {
                                                                    if (!empty($attr['attribute_name'])) {
                                                                        $attributeNames[] = strtolower(
                                                                            trim($attr['attribute_name']),
                                                                        );
                                                                    }
                                                                }
                                                            }

                                                            $variation_string =
                                                                $seller_product_variation[$key2]['variation'] ?? '';
                                                            $variation_parts = array_map(function ($v) {
                                                                return strtolower(
                                                                    preg_replace('/[^a-zA-Z0-9]/', '', $v),
                                                                );
                                                            }, explode('-', $variation_string));

                                                            // Remove redundant variants that were injected into addons
                                                            $cartItem_addons = array_filter($cartItem_addons, function (
                                                                $addon,
                                                            ) use ($attributeNames, $variation_parts) {
                                                                if (
                                                                    in_array(
                                                                        strtolower(trim($addon['addon_name'] ?? '')),
                                                                        $attributeNames,
                                                                    )
                                                                ) {
                                                                    return false;
                                                                }
                                                                $addon_value_clean = strtolower(
                                                                    preg_replace(
                                                                        '/[^a-zA-Z0-9]/',
                                                                        '',
                                                                        $addon['name'] ?? '',
                                                                    ),
                                                                );
                                                                if (
                                                                    !empty($addon_value_clean) &&
                                                                    in_array($addon_value_clean, $variation_parts)
                                                                ) {
                                                                    return false;
                                                                }
                                                                return true;
                                                            });

                                                            $calculated_addon_price = 0;
                                                            foreach ($cartItem_addons as $addon) {
                                                                $calculated_addon_price += $addon['price'] ?? 0;
                                                            }

                                                            $base_price =
                                                                cart_product_price($cart, $product, false, false);
                                                            $price = $base_price + $calculated_addon_price;
                                                            $qty = $cart['quantity'];
                                                            $row_total = $price * $qty;
                                                            $seller_subtotal += $row_total;

                                                            $hasVariation = !empty($variation_string);
                                                            $hasAddons = !empty($cartItem_addons);
                                                        }
                                                    @endphp

                                                    {{-- DESKTOP VIEW --}}
                                                    <div class="d-none d-lg-flex row align-items-center p-4 delivery-desktop-card position-relative"
                                                        style="border: 1px solid #f0e6da; border-radius: 12px; margin-bottom: 20px; background: #fff;">

                                                        {{-- Product Image, Name & Pricing Breakdown --}}
                                                        <div class="col-lg-7 d-flex align-items-start gap-3 min-w-0">
                                                            <img src="{{ get_image($product->thumbnail) }}"
                                                                class="img-fit rounded-3 flex-shrink-0 shadow-sm"
                                                                style="width:100px;height:100px;object-fit:cover;"
                                                                alt="{{ $product->getTranslation('name') }}"
                                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                            <div class="min-w-0 flex-grow-1" style="margin-left: 15px;">
                                                                <span class="fs-16 fw-700 text-dark d-block delivery-product-name-text">
                                                                    {{ $product->getTranslation('name') }}
                                                                    @if($hasVariation) <span class="text-muted fs-13">- {{ $seller_product_variation[$key2]['variation'] }}</span> @endif
                                                                </span>

                                                                {{-- Selected Attributes --}}
                                                                @if (!empty($cartItem_attributes) && is_array($cartItem_attributes) && count($cartItem_attributes) > 0)
                                                                    <div class="attribute-details mt-2">
                                                                        @foreach ($cartItem_attributes as $attribute)
                                                                            <span class="d-block fs-12 text-muted">
                                                                                {{ $attribute['attribute_name'] ?? '' }}:
                                                                                {{ $attribute['option_name'] ?? '' }}
                                                                            </span>
                                                                        @endforeach
                                                                    </div>
                                                                @endif

                                                                {{-- Pricing & Addons breakdown --}}
                                                                <div class="price-breakdown-box p-3 rounded-3 mt-3"
                                                                    style="background: #faf8f5; border: 1px solid #f0e6da; border-radius: 8px; max-width: 480px;">
                                                                    {{-- Product Price --}}
                                                                    <div class="d-flex justify-content-between align-items-center mb-2 fs-13">
                                                                        <span class="text-secondary">{{ translate('Product Price') }}</span>
                                                                        <span class="fw-600 text-dark">
                                                                            {{ single_price($base_price ?? 0) }}
                                                                            @if ($qty > 1)
                                                                                <small class="text-muted fs-11" style="display: block; text-align: right;">
                                                                                    ({{ single_price(($base_price ?? 0) * $qty) }} total)
                                                                                </small>
                                                                            @endif
                                                                        </span>
                                                                    </div>

                                                                    {{-- Addons Price --}}
                                                                    @if ($calculated_addon_price > 0)
                                                                        <div class="d-flex justify-content-between align-items-center mb-2 fs-13 border-top pt-2">
                                                                            <span class="text-secondary">{{ translate('Add-on Price') }}</span>
                                                                            <span class="fw-600 text-dark">
                                                                                +{{ single_price($calculated_addon_price) }}
                                                                                @if ($qty > 1)
                                                                                    <small class="text-muted fs-11" style="display: block; text-align: right;">
                                                                                        (+{{ single_price($calculated_addon_price * $qty) }} total)
                                                                                    </small>
                                                                                @endif
                                                                            </span>
                                                                        </div>
                                                                    @endif

                                                                    {{-- Addons Details list --}}
                                                                    @if ($hasAddons)
                                                                        @php
                                                                            $toggleId = 'addonCollapseDeliveryDesktop' . ($seller_id ?? '') . ($key2 ?? '') . ($cart->id ?? uniqid());
                                                                        @endphp
                                                                        <div class="border-top pt-2 mt-2">
                                                                            <button type="button"
                                                                                class="addon-toggle-btn d-flex justify-content-between align-items-center w-100 text-left"
                                                                                data-toggle="collapse"
                                                                                data-target="#{{ $toggleId }}"
                                                                                aria-expanded="false"
                                                                                aria-controls="{{ $toggleId }}">
                                                                                <span class="fw-600 fs-11 text-uppercase">{{ translate('Selected Add-ons') }}</span>
                                                                                <i class="las la-angle-down addon-arrow"></i>
                                                                            </button>
                                                                            <div class="collapse addon-details mt-2" id="{{ $toggleId }}">
                                                                                @foreach ($cartItem_addons as $addon)
                                                                                    <div class="d-flex justify-content-between align-items-center fs-12 text-secondary py-1 addon-row">
                                                                                        <span class="addon-name-text">•
                                                                                            {{ $addon['addon_name'] ?? '' }}
                                                                                            @if (isset($addon['name']))
                                                                                                | {{ $addon['name'] }}
                                                                                            @endif
                                                                                        </span>
                                                                                        <span class="fw-600 addon-price-text">
                                                                                            @if (isset($addon['price']) && floatval($addon['price']) > 0)
                                                                                                +£{{ number_format($addon['price'], 2) }}
                                                                                            @endif
                                                                                        </span>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                                @if ($product->dispatch_time)
                                                                    <div class="mt-2 fs-12 text-muted">
                                                                        <i class="las la-clock fs-14"></i>
                                                                        <span class="fw-600">{{ translate('Dispatch Time') }}:</span>
                                                                        {{ $product->dispatch_time }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        {{-- Quantity --}}
                                                        <div class="col-lg-2 d-flex flex-column align-items-center justify-content-center text-center">
                                                            <span class="fs-12 text-secondary mb-2 text-uppercase fw-600" style="letter-spacing: 0.5px;">{{ translate('Quantity') }}</span>
                                                            <span class="fw-700 fs-18 text-dark">{{ $qty }}</span>
                                                        </div>

                                                        {{-- Total Amount --}}
                                                        <div class="col-lg-3 d-flex flex-column align-items-end justify-content-center text-end" style="padding-right: 25px;">
                                                            <span class="fs-12 text-secondary mb-2 text-uppercase fw-600" style="letter-spacing: 0.5px;">{{ translate('Total Amount') }}</span>
                                                            <span class="fw-700 fs-20 text-primary" style="color: #b57a45 !important;">
                                                                {{ single_price($row_total ?? 0) }}
                                                            </span>
                                                        </div>

                                                        {{-- Edit Button --}}
                                                        @if($cart)
                                                        <div class="position-absolute" style="top: 20px; right: 20px; z-index: 10;">
                                                            <a href="{{ route('cart.editItem', $cart->id) }}"
                                                                class="btn btn-link p-0 d-flex align-items-center justify-content-center"
                                                                style="outline:none;border:none;    border: 1px solid #EADDCF;background:#fdf6ed;width:38px;height:38px;border-radius:10px;transition:all 0.2s ease-in-out;box-shadow: 0 2px 5px rgba(181, 122, 69, 0.05);"
                                                                onmouseover="this.style.background='#b57a45'; this.querySelector('svg path').style.stroke='#ffffff'; this.style.transform='scale(1.05)';"
                                                                onmouseout="this.style.background='#fdf6ed'; this.querySelector('svg path').style.stroke='#b57a45'; this.style.transform='scale(1)';"
                                                                title="{{ translate('Edit options') }}">
                                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M11 4H4C2.89543 4 2 4.89543 2 6V20C2 21.1046 2.89543 22 4 22H18C19.1046 22 20 21.1046 20 20V13M18.5 2.5C19.3284 1.67157 20.6716 1.67157 21.5 2.5C22.3284 3.32843 22.3284 4.67157 21.5 5.5L12 15L8 16L9 12L18.5 2.5Z"
                                                                        stroke="#b57a45" stroke-width="1.8" stroke-linecap="round"
                                                                        stroke-linejoin="round" style="transition: stroke 0.2s;" />
                                                                </svg>
                                                            </a>
                                                        </div>
                                                        @endif
                                                    </div>

                                                    {{-- MOBILE VIEW --}}
                                                    <div class="d-block d-lg-none p-3 delivery-mobile-card"
                                                        style="border: 1px solid #f0e6da; border-radius: 12px; margin-bottom: 15px; background: #fff;">
                                                        <div class="d-flex justify-content-between align-items-start mb-3" style="gap:5px;">
                                                            <div class="d-flex align-items-start gap-3 min-w-0">
                                                                <img src="{{ get_image($product->thumbnail) }}"
                                                                    class="img-fit rounded-3 flex-shrink-0 shadow-sm"
                                                                    style="width:80px;height:80px;object-fit:cover;"
                                                                    alt="{{ $product->getTranslation('name') }}"
                                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                                <div class="min-w-0" style="margin-left: 10px;">
                                                                    <span class="fs-13 fw-700 text-dark d-block delivery-product-name-text">
                                                                        {{ $product->getTranslation('name') }}
                                                                        @if($hasVariation) <span class="text-muted fs-11">- {{ $seller_product_variation[$key2]['variation'] }}</span> @endif
                                                                    </span>

                                                                    {{-- Selected Attributes --}}
                                                                    @if (!empty($cartItem_attributes) && is_array($cartItem_attributes) && count($cartItem_attributes) > 0)
                                                                        <div class="attribute-details mt-1">
                                                                            @foreach ($cartItem_attributes as $attribute)
                                                                                <span class="d-block fs-11 text-muted">
                                                                                    {{ $attribute['attribute_name'] ?? '' }}:
                                                                                    {{ $attribute['option_name'] ?? '' }}
                                                                                </span>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            {{-- Edit button (mobile) --}}
                                                            @if($cart)
                                                            <div class="ms-2 flex-shrink-0">
                                                                <a href="{{ route('cart.editItem', $cart->id) }}"
                                                                    class="btn btn-link p-0 d-flex align-items-center justify-content-center"
                                                                    style="outline:none;border:none;border: 1px solid #EADDCF;background:#fdf6ed;width:32px;height:32px;border-radius:8px;transition:all 0.2s ease-in-out;box-shadow: 0 2px 5px rgba(181, 122, 69, 0.05);"
                                                                    onmouseover="this.style.background='#b57a45'; this.querySelector('svg path').style.stroke='#ffffff'; this.style.transform='scale(1.05)';"
                                                                    onmouseout="this.style.background='#fdf6ed'; this.querySelector('svg path').style.stroke='#b57a45'; this.style.transform='scale(1)';"
                                                                    title="{{ translate('Edit options') }}">
                                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M11 4H4C2.89543 4 2 4.89543 2 6V20C2 21.1046 2.89543 22 4 22H18C19.1046 22 20 21.1046 20 20V13M18.5 2.5C19.3284 1.67157 20.6716 1.67157 21.5 2.5C22.3284 3.32843 22.3284 4.67157 21.5 5.5L12 15L8 16L9 12L18.5 2.5Z"
                                                                            stroke="#b57a45" stroke-width="1.8" stroke-linecap="round"
                                                                            stroke-linejoin="round" style="transition: stroke 0.2s;" />
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                            @endif
                                                        </div>

                                                        {{-- Pricing & Addons breakdown (mobile) --}}
                                                        <div class="price-breakdown-box p-3 rounded-3 mb-3"
                                                            style="background: #faf8f5; border: 1px solid #f0e6da; border-radius: 8px;">
                                                            {{-- Product Price --}}
                                                            <div class="d-flex justify-content-between align-items-center mb-2 fs-13">
                                                                <span class="text-secondary">{{ translate('Product Price') }}</span>
                                                                <span class="fw-600 text-dark">
                                                                    {{ single_price($base_price ?? 0) }}
                                                                    @if ($qty > 1)
                                                                        <small class="text-muted fs-11" style="display: block; text-align: right;">
                                                                            ({{ single_price(($base_price ?? 0) * $qty) }} total)
                                                                        </small>
                                                                    @endif
                                                                </span>
                                                            </div>

                                                            {{-- Addons Price --}}
                                                            @if ($calculated_addon_price > 0)
                                                                <div class="d-flex justify-content-between align-items-center mb-2 fs-13 border-top pt-2">
                                                                    <span class="text-secondary">{{ translate('Add-on Price') }}</span>
                                                                    <span class="fw-600 text-dark">
                                                                        +{{ single_price($calculated_addon_price) }}
                                                                        @if ($qty > 1)
                                                                            <small class="text-muted fs-11" style="display: block; text-align: right;">
                                                                                (+{{ single_price($calculated_addon_price * $qty) }} total)
                                                                            </small>
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                            @endif

                                                            {{-- Addons Details list (mobile) --}}
                                                            @if ($hasAddons)
                                                                @php
                                                                    $toggleIdMobile = 'addonCollapseDeliveryMobile' . ($seller_id ?? '') . ($key2 ?? '') . ($cart->id ?? uniqid());
                                                                @endphp
                                                                <div class="border-top pt-2 mt-2">
                                                                    <button type="button"
                                                                        class="addon-toggle-btn d-flex justify-content-between align-items-center w-100 text-left"
                                                                        data-toggle="collapse"
                                                                        data-target="#{{ $toggleIdMobile }}"
                                                                        aria-expanded="false"
                                                                        aria-controls="{{ $toggleIdMobile }}">
                                                                        <span class="fw-600 fs-11 text-uppercase">{{ translate('Selected Add-ons') }}</span>
                                                                        <i class="las la-angle-down addon-arrow"></i>
                                                                    </button>
                                                                    <div class="collapse addon-details mt-2" id="{{ $toggleIdMobile }}">
                                                                        @foreach ($cartItem_addons as $addon)
                                                                            <div class="d-flex justify-content-between align-items-center fs-12 text-secondary py-1 addon-row">
                                                                                <span class="addon-name-text">•
                                                                                    {{ $addon['addon_name'] ?? '' }}
                                                                                    @if (isset($addon['name']))
                                                                                        | {{ $addon['name'] }}
                                                                                    @endif
                                                                                </span>
                                                                                <span class="fw-600 addon-price-text">
                                                                                    @if (isset($addon['price']) && floatval($addon['price']) > 0)
                                                                                        +£{{ number_format($addon['price'], 2) }}
                                                                                    @endif
                                                                                </span>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        @if ($product->dispatch_time)
                                                            <div class="mb-3 fs-12 text-muted">
                                                                <i class="las la-clock fs-14"></i>
                                                                <span class="fw-600">{{ translate('Dispatch Time') }}:</span>
                                                                {{ $product->dispatch_time }}
                                                            </div>
                                                        @endif

                                                        {{-- Quantity and total row (mobile) --}}
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <span class="d-block text-secondary fs-11 mb-1">{{ translate('Quantity') }}</span>
                                                                <span class="fw-700 fs-16 text-dark">{{ $qty }}</span>
                                                            </div>
                                                            <div class="text-end">
                                                                <span class="d-block text-secondary fs-11 mb-1">{{ translate('Total Amount') }}</span>
                                                                <span class="fw-700 fs-16 text-primary" style="color: #b57a45 !important;">
                                                                    {{ single_price($row_total ?? 0) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                @endforeach
                                            </div>

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

                                                // Find selected services from the carts
                                                $selectedServiceIds = [];
                                                foreach ($seller_product as $productId) {
                                                    $cart = collect($carts)->firstWhere('product_id', $productId);
                                                    if ($cart && !empty($cart->services)) {
                                                        $cartServices = json_decode($cart->services, true);
                                                        foreach ($cartServices as $cs) {
                                                            $selectedServiceIds[] = $cs['id'];
                                                        }
                                                    }
                                                }
                                            @endphp

                                            @if ($allServices->count() > 0)
                                                <div class="custom-services-section mb-4">

                                                    <h5 class="custom-services-title">
                                                        {{ translate('Additional Services') }}
                                                    </h5>

                                                    {{-- Error message for service selection --}}
                                                    <div id="service-required-error"
                                                        class="alert alert-danger custom-service-error mb-3 px-3 py-2 fw-600 d-flex align-items-center d-none"
                                                        style="font-size: 15px;">
                                                        <span class="mr-2" style="font-size: 1.4em;"><i
                                                                class="las la-exclamation-circle"></i></span>
                                                        <span>
                                                            {{ translate('Please select at least one service to continue.') }}
                                                        </span>
                                                    </div>

                                                    <div class="row">
                                                        @foreach ($allServices as $service)
                                                            <div class="col-md-6 mb-3 pr-1 pl-1">
                                                                <label class="aiz-megabox d-block mb-0">
                                                                    <input type="checkbox" name="selected_services[]"
                                                                        value="{{ $service->id }}"
                                                                        class="service-checkbox"
                                                                        data-price="{{ $service->price }}"
                                                                        @if (in_array($service->id, $selectedServiceIds)) checked @endif>
                                                                    <span
                                                                        class="d-flex aiz-megabox-elem custom-service-card p-3">
                                                                        <span
                                                                            class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                                        <span class="flex-grow-1 pl-3">
                                                                            <span class="d-block fw-700 fs-14 text-dark"
                                                                                style="color: #4a3e3d !important;">
                                                                                {{ $service->name }}
                                                                            </span>
                                                                            @if (!empty($service->description))
                                                                                <span class="d-block fs-13 text-muted mt-1"
                                                                                    style="line-height: 1.4;">
                                                                                    {{ $service->description }}
                                                                                </span>
                                                                            @endif
                                                                            <span class="d-block fs-13 fw-700 mt-2"
                                                                                style="color: #685b4e !important;">
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

                                            @php
                                                $seller_shipping = 0;
                                                foreach ($seller_product as $productId) {
                                                    $shippingProduct = get_single_product($productId);
                                                    $shippingCart = collect($carts)->firstWhere(
                                                        'product_id',
                                                        $productId,
                                                    );
                                                    $seller_shipping += getProductShippingChargeTotal(
                                                        $shippingProduct,
                                                        $shippingCart->quantity ?? 1,
                                                    );
                                                }
                                            @endphp

                                            {{-- Totals Section --}}
                                            <div class="px-0 py-3 border-top">
                                                <div class="d-flex justify-content-between align-items-center mb-2 px-2">
                                                    <span class="opacity-70 fs-14 text-dark">{{ translate('Subtotal') }}</span>
                                                    <span class="fw-600 fs-14 text-dark" id="seller-subtotal">{{ single_price($seller_subtotal) }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mb-2 px-2">
                                                    <span class="opacity-70 fs-14 text-dark">{{ translate('Shipping Charges') }}</span>
                                                    <span class="fw-600 fs-14 text-dark shipping-total-display">{{ single_price($seller_shipping) }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mb-2 px-2">
                                                    <span class="opacity-70 fs-14 text-dark">{{ translate('Services') }}</span>
                                                    <span class="fw-600 fs-14 text-dark services-total-display">£0.00</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center pt-2 px-2 border-top">
                                                    <span class="fw-700 fs-18 text-dark">{{ translate('Total') }}</span>
                                                    <span class="fw-700 fs-20 grand-total-display" style="color: #b57a45;"
                                                        data-base-total="{{ $seller_subtotal + $seller_shipping }}">
                                                        {{ single_price($seller_subtotal + $seller_shipping) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div> {{-- end card-body --}}
                                    </div>
                                @endforeach
                            @endif

                    </div>

                    <div class="row g-2 mt-3">
                        <div class="col-6 col-md-6 mb-2 mb-md-0">
                            <a href="{{ url('checkout') }}"
                                class="btn borderbtn fs-14 fw-700 rounded-0 w-100 w-md-auto py-3 custom_checkout_button_design filled">
                                <i class="las la-arrow-left fs-17"></i>
                                {{ translate('Back') }}
                            </a>
                        </div>
                        <div class="col-6 col-md-6 text-center text-md-right">
                            <button type="submit" id="continue-to-payment-btn"
                                class="btn borderbtn fs-14 fw-700 rounded-0 w-100 w-md-auto py-3 custom_checkout_button_design unfilled">
                                {{ translate('Next') }}
                            </button>
                        </div>
                    </div>
                    </form>

                </div>
            </div>
        </div>
    </section>

    <style>
        :focus-visible {
            outline: none !important;
        }

        .delivery-maincontainer {
            background: #fdfdfc;
        }

        .delivery-desktop-card {
            transition: box-shadow .15s;
        }

        .delivery-desktop-card:hover {
            box-shadow: 0 2px 8px rgba(124, 113, 94, .05);
            background: #faf8f3 !important;
        }

        .delivery-product-name-text {
            word-wrap: break-word;
            word-break: break-word;
            white-space: normal;
            overflow-wrap: anywhere;
            line-height: 1.4;
        }

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

        .addon-details {
            width: 100% !important;
            max-width: 100%;
            box-sizing: border-box;
            background: #f9f6f3;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(181, 122, 69, .08);
        }

        .la-rotate-180 {
            transform: rotate(180deg);
            transition: transform 0.2s ease;
        }

        .min-w-0 {
            min-width: 0;
        }

        a:-webkit-any-link:focus-visible {
            outline: none !important;
        }

        #continue-to-payment-btn[disabled] {
            background: #d9cbbb !important;
            cursor: no-drop;
            outline: none !important;
        }

        #continue-to-payment-btn:focus-visible {
            outline: none !important;
        }

        @media (max-width: 991.98px) {
            .delivery-maincontainer {
                padding: 1rem !important;
            }
        }
    </style>
@endsection


@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            let $serviceCheckboxes = $('.service-checkbox');
            let $continueBtn = $('#continue-to-payment-btn');
            let $serviceError = $('#service-required-error');

            // Initial calculation for all cards
            $('.card').each(function() {
                let $card = $(this);
                let $checkboxes = $card.find('.service-checkbox');
                if ($checkboxes.length === 0) return;

                // If there is no checked service but there is a free one, select it by default
                if ($card.find('.service-checkbox:checked').length === 0) {
                    let $freeService = $checkboxes.filter(function() {
                        return parseFloat($(this).data('price')) === 0;
                    });
                    if ($freeService.length > 0) {
                        $freeService.first().prop('checked', true);
                    }
                }

                // Trigger a change to calculate initial totals
                $card.find('.service-checkbox:checked').first().trigger('change');
            });

            // Ensure only one checkbox can be selected at a time per seller card
            $serviceCheckboxes.on('change', function() {
                let $card = $(this).closest('.card');
                let $cardCheckboxes = $card.find('.service-checkbox');

                // Uncheck all others in this card
                $cardCheckboxes.not(this).prop('checked', false);

                // Update service and grand total
                let serviceTotal = 0;
                let $checked = $card.find('.service-checkbox:checked');
                if ($checked.length > 0) {
                    serviceTotal = parseFloat($checked.data('price')) || 0;
                }

                let $grandTotal = $card.find('.grand-total-display');
                let baseTotal = parseFloat($grandTotal.data('base-total'));
                let finalTotal = baseTotal + serviceTotal;

                $card.find('.services-total-display').html('£' + serviceTotal.toFixed(2));
                $grandTotal.html('£' + finalTotal.toFixed(2));

                checkServiceSelection();
            });

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

            // Arrow rotation for collapse
            $('.collapse').on('show.bs.collapse', function() {
                $(this).prev('.addon-toggle-btn').find('.addon-arrow').addClass('la-rotate-180');
            });
            $('.collapse').on('hide.bs.collapse', function() {
                $(this).prev('.addon-toggle-btn').find('.addon-arrow').removeClass('la-rotate-180');
            });
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
