<div class="container my-4">
    <style>
        @media (max-width: 991.98px) {

            .custom_cart_design_css {
                display: flex;
                flex-wrap: wrap;
                width: 100%;
                border-bottom: 1px solid #00000021;
            }
        }
    </style>
    @if ($carts && count($carts) > 0)
        <div class="row justify-content-center">
            <div class="col-xxl-12 col-xl-10">
                <div class="border shadow-sm p-3 p-lg-4 bg-white maincontainer">
                    <div class="mb-4">
                        <!-- Headers for desktop (hidden as we use beautiful visual cards for desktop too) -->
                        <div class="row d-none border-bottom bg-cart-header text-white fs-14 py-3 px-2 rounded-2">
                            <div class="col-md-1 fw-bold">#</div>
                            <div class="col-md-4 fw-bold">{{ translate('Product') }}</div>
                            <div class="col-md-2 fw-bold">{{ translate('Price') }}</div>
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
                                    $product_stock = $product->stocks
                                        ->where('variant', $cartItem['variation'])
                                        ->first();
                                    // dd($cartItem);
                                    $product_name_with_choice = $product->getTranslation('name');
                                    if ($cartItem['variation'] != null) {
                                        $product_name_with_choice =
                                            $product->getTranslation('name') . ' - ' . $cartItem['variation'];
                                    }
                                    $total_addon = $cartItem['addon_price'] * $cartItem['quantity'];
                                    $total_addon = $cartItem['variation'];
                                    // Attribute price calculation
                                    $attribute_price = 0;
                                    $cartItem_attributes = [];
                                    if (!empty($cartItem->attributes)) {
                                        $cartItem_attributes = json_decode($cartItem->attributes, true);
                                        if (is_array($cartItem_attributes)) {
                                            foreach ($cartItem_attributes as $att) {
                                                if (isset($att['price'])) {
                                                    $attribute_price += floatval($att['price']);
                                                }
                                            }
                                        }
                                    }
                                    // dd($cartItem_attributes);
                                    $cartItem_addons = [];
                                    if (!empty($cartItem->addons)) {
                                        $cartItem_addons = json_decode($cartItem->addons, true);
                                    }

                                    $variant_price = 0;

                                    if ($product_stock && isset($product_stock->price)) {
                                        $variant_price = floatval($product_stock->price);
                                    } else {
                                        $variant_price = floatval($cartItem['price'] ?? 0);
                                    }

                                    // attributes
                                    $attribute_price = 0;

                                    $cartItem_attributes = [];

                                    if (!empty($cartItem->attributes)) {
                                        $cartItem_attributes = json_decode($cartItem->attributes, true);

                                        if (is_array($cartItem_attributes)) {
                                            foreach ($cartItem_attributes as $att) {
                                                if (isset($att['price'])) {
                                                    $attribute_price += floatval($att['price']);
                                                }
                                            }
                                        }
                                    }

                                    // addons total
                                    $total_addon = floatval($cartItem['addon_price'] ?? 0);

                                    // final unit price
                                    $base_price = $variant_price;

                                    // row total
                                    $row_total =
                                        ($base_price + $attribute_price + $total_addon) * $cartItem['quantity'];

                                    $total += $row_total;
                                @endphp

                                <!-- Responsive Cart row -->
                                <li class="list-group-item p-0 cart-item-row position-relative border-0 border-bottom">
                                    <div class="row align-items-center d-none d-lg-flex p-4 desktop-cart-card position-relative"
                                        style="border: 1px solid #f0e6da; border-radius: 12px; margin-bottom: 20px; background: #fff;">
                                        <!-- Product Image, Name & Pricing Breakdown (col-lg-6) -->
                                        <div class="col-lg-6 d-flex align-items-start gap-3 min-w-0">
                                            <img src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                class="img-fit rounded-3 flex-shrink-0 shadow-sm"
                                                style="width:100px;height:100px;object-fit:cover;"
                                                alt="{{ $product->getTranslation('name') }}"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                            <div class="min-w-0 flex-grow-1" style="margin-left: 15px;">
                                                <span
                                                    class="fs-16 fw-700 text-dark d-block product-name-text">{{ $product_name_with_choice }}</span>

                                                <!-- Selected Attributes (if any) -->
                                                @if (!empty($cartItem_attributes) && count($cartItem_attributes) > 0)
                                                    <div class="attribute-details mt-2">
                                                        @foreach ($cartItem_attributes as $attribute)
                                                            <span class="d-block fs-12 text-muted">
                                                                {{ $attribute['attribute_name'] ?? '' }}:
                                                                {{ $attribute['option_name'] ?? '' }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                <!-- Pricing & Addons simple breakdown nested right below attributes -->
                                                <div class="price-breakdown-box p-3 rounded-3 mt-3"
                                                    style="background: #faf8f5; border: 1px solid #f0e6da; border-radius: 8px; max-width: 480px;">
                                                    <!-- Row 1: Product Price -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center mb-2 fs-13">
                                                        <span
                                                            class="text-secondary">{{ translate('Product Price') }}</span>
                                                        <span class="fw-600 text-dark">
                                                            {{ single_price($base_price + $attribute_price) }}
                                                            @if ($cartItem['quantity'] > 1)
                                                                <small class="text-muted fs-11"
                                                                    style="display: block; text-align: right;">({{ single_price(($base_price + $attribute_price) * $cartItem['quantity']) }}
                                                                    total)</small>
                                                            @endif
                                                        </span>
                                                    </div>

                                                    <!-- Row 2: Addons Price (Only show if total_addon > 0) -->
                                                    @if ($total_addon > 0)
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-2 fs-13 border-top pt-2">
                                                            <span
                                                                class="text-secondary">{{ translate('Add-on Price') }}</span>
                                                            <span class="fw-600 text-dark">
                                                                +{{ single_price($total_addon) }}
                                                                @if ($cartItem['quantity'] > 1)
                                                                    <small class="text-muted fs-11"
                                                                        style="display: block; text-align: right;">(+{{ single_price($total_addon * $cartItem['quantity']) }}
                                                                        total)</small>
                                                                @endif
                                                            </span>
                                                        </div>
                                                    @endif

                                                    <!-- Addons Details list -->
                                                    @if (count($cartItem_addons) > 0)
                                                        <div class="border-top pt-2 mt-2">
                                                            <button type="button"
                                                                class="addon-toggle-btn d-flex justify-content-between align-items-center w-100 text-left"
                                                                data-toggle="collapse"
                                                                data-target="#cart-addons-desktop-{{ $cartItem['id'] }}"
                                                                aria-expanded="false"
                                                                aria-controls="cart-addons-desktop-{{ $cartItem['id'] }}">
                                                                <span
                                                                    class="fw-600 fs-11 text-uppercase">{{ translate('Selected Add-ons') }}</span>
                                                                <i class="las la-angle-down addon-arrow"></i>
                                                            </button>
                                                            <div class="collapse addon-details mt-2"
                                                                id="cart-addons-desktop-{{ $cartItem['id'] }}">
                                                                @foreach ($cartItem_addons as $addon)
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center fs-12 text-secondary py-1 addon-row">
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
                                            </div>
                                        </div>

                                        <!-- Quantity Selector Column (col-lg-3) -->
                                        <div
                                            class="col-lg-3 d-flex flex-column align-items-center justify-content-center text-center">
                                            <span class="fs-12 text-secondary mb-2 text-uppercase fw-600"
                                                style="letter-spacing: 0.5px;">{{ translate('Quantity') }}</span>
                                            <div>
                                                @if ($cartItem['digital'] != 1 && $product->auction_product == 0)
                                                    <div class="modern-qty-selector">
                                                        <button class="qty-btn" type="button" data-type="minus"
                                                            onclick="handleCartQuantity(this, {{ $cartItem['id'] }}, 'minus')"
                                                            @if ($cartItem['quantity'] <= 1) disabled @endif>
                                                            <i class="las la-minus"></i>
                                                        </button>
                                                        <input type="number" name="quantity[{{ $cartItem['id'] }}]"
                                                            class="qty-input cart-qty-input"
                                                            value="{{ $cartItem['quantity'] }}"
                                                            min="{{ $product->min_qty }}"
                                                            max="{{ $product_stock->qty ?? 1 }}"
                                                            onchange="updateQuantity({{ $cartItem['id'] }}, this)">
                                                        <button class="qty-btn" type="button" data-type="plus"
                                                            onclick="handleCartQuantity(this, {{ $cartItem['id'] }}, 'plus')"
                                                            @if ($cartItem['quantity'] >= ($product_stock->qty ?? 1)) disabled @endif>
                                                            <i class="las la-plus"></i>
                                                        </button>
                                                    </div>
                                                @else
                                                    <span class="fw-700 fs-14">Qty: 1</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Total Amount Column (col-lg-3) -->
                                        <div class="col-lg-3 d-flex flex-column align-items-end justify-content-center text-end"
                                            style="padding-right: 25px;">
                                            <span class="fs-12 text-secondary mb-2 text-uppercase fw-600"
                                                style="letter-spacing: 0.5px;">{{ translate('Total Amount') }}</span>
                                            <span class="fw-700 fs-20 text-primary" style="color: #b57a45 !important;">
                                                {{ single_price(($base_price + $attribute_price + $total_addon) * $cartItem['quantity']) }}
                                            </span>
                                        </div>

                                        <!-- Edit & Delete Buttons positioned absolutely in the top-right corner of the card -->
                                        <div class="modern-action-buttons-wrapper position-absolute"
                                            style="top: 20px; right: 20px; z-index: 10; display: flex !important; gap: 8px;">
                                            <!-- Edit button -->
                                            <a href="{{ route('cart.editItem', $cartItem['id']) }}"
                                                class="btn btn-link p-0 d-flex align-items-center justify-content-center"
                                                style="outline:none;border:none;background:#fdf6ed;width:38px;height:38px;border-radius:10px;transition:all 0.2s ease-in-out;box-shadow: 0 2px 5px rgba(181, 122, 69, 0.05);"
                                                onmouseover="this.style.background='#b57a45'; this.querySelector('svg path').style.stroke='#ffffff'; this.style.transform='scale(1.05)';"
                                                onmouseout="this.style.background='#fdf6ed'; this.querySelector('svg path').style.stroke='#b57a45'; this.style.transform='scale(1)';"
                                                title="{{ translate('Edit options') }}">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M11 4H4C2.89543 4 2 4.89543 2 6V20C2 21.1046 2.89543 22 4 22H18C19.1046 22 20 21.1046 20 20V13M18.5 2.5C19.3284 1.67157 20.6716 1.67157 21.5 2.5C22.3284 3.32843 22.3284 4.67157 21.5 5.5L12 15L8 16L9 12L18.5 2.5Z"
                                                        stroke="#b57a45" stroke-width="1.8" stroke-linecap="round"
                                                        stroke-linejoin="round" style="transition: stroke 0.2s;" />
                                                </svg>
                                            </a>
                                            <!-- Delete Button -->
                                            <button onclick="removeFromCartView(event, {{ $cartItem['id'] }})"
                                                class="btn btn-link p-0 d-flex align-items-center justify-content-center"
                                                style="outline:none;border:none;background:#fdf6ed;width:38px;height:38px;border-radius:10px;transition:all 0.2s ease-in-out;box-shadow: 0 2px 5px rgba(181, 122, 69, 0.05);"
                                                onmouseover="this.style.background='#b57a45'; this.querySelector('svg path').style.stroke='#ffffff'; this.style.transform='scale(1.05)';"
                                                onmouseout="this.style.background='#fdf6ed'; this.querySelector('svg path').style.stroke='#b57a45'; this.style.transform='scale(1)';"
                                                title="{{ translate('Remove from cart') }}">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M19 7L18.1327 19.1422C18.0579 20.1891 17.187 21 16.1378 21H7.86224C6.81296 21 5.94208 20.1891 5.86732 19.1422L5 7M10 11V17M14 11V17M15 7V4C15 3.44772 14.5523 3 14 3H10C9.44772 3 9 3.44772 9 4V7M4 7H20"
                                                        stroke="#b57a45" stroke-width="1.8" stroke-linecap="round"
                                                        stroke-linejoin="round" style="transition: stroke 0.2s;" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Mobile view -->
                                    <div class="d-block d-lg-none p-3 mobile-cart-card"
                                        style="border: 1px solid #f0e6da; border-radius: 12px; margin-bottom: 15px; background: #fff;">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="d-flex align-items-start gap-3 min-w-0">
                                                <img src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                    class="img-fit rounded-3 flex-shrink-0 shadow-sm"
                                                    style="width:80px;height:80px;object-fit:cover;"
                                                    alt="{{ $product->getTranslation('name') }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                <div class="min-w-0" style="margin-left: 10px;">
                                                    <span
                                                        class="fs-13 fw-700 text-dark d-block product-name-text">{{ $product_name_with_choice }}</span>

                                                    <!-- Selected Attributes (if any) -->
                                                    @if (!empty($cartItem_attributes) && count($cartItem_attributes) > 0)
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
                                            <!-- Action buttons -->
                                            <div class="modern-action-buttons-wrapper ms-2 flex-shrink-0" style="display: flex !important;    flex-direction: column; gap: 8px;">
                                                <!-- Edit button -->
                                                <a href="{{ route('cart.editItem', $cartItem['id']) }}"
                                                    class="btn btn-link p-0 d-flex align-items-center justify-content-center"
                                                    style="outline:none;border:none;background:#fdf6ed;width:38px;height:38px;border-radius:10px;transition:all 0.2s ease-in-out;box-shadow: 0 2px 5px rgba(181, 122, 69, 0.05);"
                                                    onmouseover="this.style.background='#b57a45'; this.querySelector('svg path').style.stroke='#ffffff'; this.style.transform='scale(1.05)';"
                                                    onmouseout="this.style.background='#fdf6ed'; this.querySelector('svg path').style.stroke='#b57a45'; this.style.transform='scale(1)';"
                                                    title="{{ translate('Edit options') }}">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M11 4H4C2.89543 4 2 4.89543 2 6V20C2 21.1046 2.89543 22 4 22H18C19.1046 22 20 21.1046 20 20V13M18.5 2.5C19.3284 1.67157 20.6716 1.67157 21.5 2.5C22.3284 3.32843 22.3284 4.67157 21.5 5.5L12 15L8 16L9 12L18.5 2.5Z"
                                                            stroke="#b57a45" stroke-width="1.8" stroke-linecap="round"
                                                            stroke-linejoin="round" style="transition: stroke 0.2s;" />
                                                    </svg>
                                                </a>
                                                <!-- Delete Button -->
                                                <button onclick="removeFromCartView(event, {{ $cartItem['id'] }})"
                                                    class="btn btn-link p-0 d-flex align-items-center justify-content-center"
                                                    style="outline:none;border:none;background:#fdf6ed;width:38px;height:38px;border-radius:10px;transition:all 0.2s ease-in-out;box-shadow: 0 2px 5px rgba(181, 122, 69, 0.05);"
                                                    onmouseover="this.style.background='#b57a45'; this.querySelector('svg path').style.stroke='#ffffff'; this.style.transform='scale(1.05)';"
                                                    onmouseout="this.style.background='#fdf6ed'; this.querySelector('svg path').style.stroke='#b57a45'; this.style.transform='scale(1)';"
                                                    title="{{ translate('Remove from cart') }}">
                                                    <svg width="20" height="20" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M19 7L18.1327 19.1422C18.0579 20.1891 17.187 21 16.1378 21H7.86224C6.81296 21 5.94208 20.1891 5.86732 19.1422L5 7M10 11V17M14 11V17M15 7V4C15 3.44772 14.5523 3 14 3H10C9.44772 3 9 3.44772 9 4V7M4 7H20"
                                                            stroke="#b57a45" stroke-width="1.8"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="transition: stroke 0.2s;" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Pricing & Addons simple breakdown -->
                                        <div class="price-breakdown-box p-3 rounded-3 mb-3"
                                            style="background: #faf8f5; border: 1px solid #f0e6da; border-radius: 8px;">
                                            <!-- Row 1: Product Price -->
                                            <div class="d-flex justify-content-between align-items-center mb-2 fs-13">
                                                <span class="text-secondary">{{ translate('Product Price') }}</span>
                                                <span class="fw-600 text-dark">
                                                    {{ single_price($base_price + $attribute_price) }}
                                                    @if ($cartItem['quantity'] > 1)
                                                        <small class="text-muted fs-11"
                                                            style="display: block; text-align: right;">({{ single_price(($base_price + $attribute_price) * $cartItem['quantity']) }}
                                                            total)</small>
                                                    @endif
                                                </span>
                                            </div>

                                            <!-- Row 2: Addons Price (Only show if total_addon > 0) -->
                                            @if ($total_addon > 0)
                                                <div
                                                    class="d-flex justify-content-between align-items-center mb-2 fs-13 border-top pt-2">
                                                    <span
                                                        class="text-secondary">{{ translate('Add-on Price') }}</span>
                                                    <span class="fw-600 text-dark">
                                                        +{{ single_price($total_addon) }}
                                                        @if ($cartItem['quantity'] > 1)
                                                            <small class="text-muted fs-11"
                                                                style="display: block; text-align: right;">(+{{ single_price($total_addon * $cartItem['quantity']) }}
                                                                total)</small>
                                                        @endif
                                                    </span>
                                                </div>
                                            @endif

                                            <!-- Addons Details list -->
                                            @if (count($cartItem_addons) > 0)
                                                <div class="border-top pt-2 mt-2">
                                                    <button type="button"
                                                        class="addon-toggle-btn d-flex justify-content-between align-items-center w-100 text-left"
                                                        data-toggle="collapse"
                                                        data-target="#cart-addons-mobile-{{ $cartItem['id'] }}"
                                                        aria-expanded="false"
                                                        aria-controls="cart-addons-mobile-{{ $cartItem['id'] }}">
                                                        <span
                                                            class="fw-600 fs-11 text-uppercase">{{ translate('Selected Add-ons') }}</span>
                                                        <i class="las la-angle-down addon-arrow"></i>
                                                    </button>
                                                    <div class="collapse addon-details mt-2"
                                                        id="cart-addons-mobile-{{ $cartItem['id'] }}">
                                                        @foreach ($cartItem_addons as $addon)
                                                            <div
                                                                class="d-flex justify-content-between align-items-center fs-12 text-secondary py-1 addon-row">
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

                                        <!-- Quantity and final row -->
                                        <div class="d-flex justify-content-between align-items-center">
                                            <!-- Quantity selector -->
                                            <div>
                                                @if ($cartItem['digital'] != 1 && $product->auction_product == 0)
                                                    <div class="modern-qty-selector">
                                                        <button class="qty-btn" type="button" data-type="minus"
                                                            onclick="handleCartQuantity(this, {{ $cartItem['id'] }}, 'minus')"
                                                            @if ($cartItem['quantity'] <= 1) disabled @endif>
                                                            <i class="las la-minus"></i>
                                                        </button>
                                                        <input type="number" name="quantity[{{ $cartItem['id'] }}]"
                                                            class="qty-input cart-qty-input"
                                                            value="{{ $cartItem['quantity'] }}"
                                                            min="{{ $product->min_qty }}"
                                                            max="{{ $product_stock->qty ?? 1 }}"
                                                            onchange="updateQuantity({{ $cartItem['id'] }}, this)">
                                                        <button class="qty-btn" type="button" data-type="plus"
                                                            onclick="handleCartQuantity(this, {{ $cartItem['id'] }}, 'plus')"
                                                            @if ($cartItem['quantity'] >= ($product_stock->qty ?? 1)) disabled @endif>
                                                            <i class="las la-plus"></i>
                                                        </button>
                                                    </div>
                                                @else
                                                    <span class="fw-700 fs-14">Qty: 1</span>
                                                @endif
                                            </div>

                                            <!-- Row Total -->
                                            <div class="text-end">
                                                <span
                                                    class="d-block text-secondary fs-11 mb-0.5">{{ translate('Total Amount') }}</span>
                                                <span class="fw-700 fs-16 text-primary"
                                                    style="color: #b57a45 !important;">
                                                    {{ single_price(($base_price + $attribute_price + $total_addon) * $cartItem['quantity']) }}
                                                </span>
                                            </div>
                                        </div>
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
                                class="btn borderbtn fs-14 fw-700 rounded-0 w-100 w-md-auto py-3 custom_checkout_button_design filled">
                                <i class="las la-arrow-left fs-17"></i>
                                {{ translate('Return to shop') }}
                            </a>
                        </div>
                        <!-- Continue to Shipping -->
                        <div class="col-12 col-md-6 text-center text-md-right">
                            @if (Auth::check())
                                <a href="{{ route('checkout.shipping_info') }}"
                                    class="btn borderbtn fs-14 fw-700 rounded-0 w-100 w-md-auto py-3 custom_checkout_button_design unfilled">
                                    {{ 'Complete Order' }}
                                </a>
                            @else
                                <button onclick="showLoginModal()"
                                    class="btn borderbtn fs-14 fw-700 rounded-0 w-100 w-md-auto py-3 custom_checkout_button_design unfilled">
                                    {{ 'Complete Order' }}
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
    $(document).ready(function() {
        $('.collapse').on('show.bs.collapse', function() {
            $(this).prev('.addon-toggle-btn').find('.addon-arrow').addClass('la-rotate-180');
        });
        $('.collapse').on('hide.bs.collapse', function() {
            $(this).prev('.addon-toggle-btn').find('.addon-arrow').removeClass('la-rotate-180');
        });
    });
</script>

<style>
    :focus-visible {
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
        .bg-cart-header>div {
            font-size: 12px !important;
        }

        .cart-item-row .col-lg-4,
        .cart-item-row .col-lg-2 {
            font-size: 14px !important;
        }

        .cart-item-row {
            padding: 1rem .5rem;
        }

        .maincontainer {
            padding: 1rem !important;
        }
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

        .d-lg-flex,
        .d-lg-block,
        .d-lg-inline,
        .d-lg-inline-block {
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

    .addon-toggle-btn {
        background: #f5eee6;
        border: 1px solid #e2d2c0;
        color: #8b5e34;
        font-size: 13px;
        font-weight: 600;
        border-radius: 6px;
        padding: 6px 12px;
        transition: all .3s ease;
    }

    .addon-toggle-btn:hover {
        background: #8b5e34;
        color: #fff;
    }

    .addon-toggle-btn:focus {
        box-shadow: none !important;
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
        background: #f9f6f3;
        border-radius: 8px;
        box-shadow: 0 1px 4px rgba(181, 122, 69, .08);
    }

    .collapse {
        width: 100% !important;
    }

    .addon-header {
        letter-spacing: .08em;
        color: #b57a45;
    }

    .addon-item {
        border-bottom: 1px solid #f3e7db;
        font-size: 13px;
        color: #74542f;
    }

    .addon-item:last-child {
        border-bottom: 0;
    }

    .addon-icon {
        display: inline-block;
        width: 18px;
        height: 18px;
        background: #fff4e5;
        border-radius: 4px;
        text-align: center;
        line-height: 20px;
    }

    .addon-icon i {
        color: #28a745;
        font-size: 15px;
    }

    .addon-separator {
        opacity: .7;
    }

    .borderbtn {
        border-radius: 12px !important;
        /* change 12px to whatever you want */
    }
</style>

<script type="text/javascript">
    if (typeof window !== "undefined" && window.AIZ && window.AIZ.extra && typeof window.AIZ.extra.plusMinus ===
        'function') {
        window.AIZ.extra.plusMinus();
    }
</script>
