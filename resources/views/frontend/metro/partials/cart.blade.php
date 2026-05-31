@php
    $total = 0;
    $carts = get_user_cart();
    if (count($carts) > 0) {
        foreach ($carts as $key => $cartItem) {
            $product = get_single_product($cartItem['product_id']);
            $item_total = cart_product_price($cartItem, $product, false) * $cartItem['quantity'];

            if (!empty($cartItem['addons'])) {
                $addons = json_decode($cartItem['addons'], true);
                if (is_array($addons)) {
                    foreach ($addons as $addon) {
                        if (is_array($addon) && isset($addon['price'])) {
                            $item_total += $addon['price'] * $cartItem['quantity'];
                        }
                    }
                }
            }
            $total += $item_total;
        }
    }
    $cart_count = count($carts);
@endphp

<!-- Cart Button -->
<a href="javascript:void(0)" class="d-flex align-items-center text-dark px-3 h-100 position-relative"
    data-toggle="dropdown" data-display="static" title="{{ translate('Cart') }}">
    <span class="position-relative">
        <svg class="icon-bag" aria-hidden="true" focusable="false" role="presentation" xmlns="http://www.w3.org/2000/svg"
            width="22" height="24" viewBox="0 0 22 24" fill="none">
            <path
                d="M6.91699 10.993V4.95104C6.91699 2.72645 8.70785 0.923065 10.917 0.923065C13.1261 0.923065 14.917 2.72645 14.917 4.95104V10.993"
                stroke="#1a1a1a" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M18.131 23.0769C19.6697 23.0769 20.917 21.8209 20.917 20.2714C20.9174 20.1285 20.9067 19.9857 20.885 19.8445L19.221 8.95686C19.0463 7.81137 18.0679 6.96551 16.917 6.96503H4.917C3.76678 6.96536 2.78859 7.81016 2.613 8.95485L0.949001 19.8545C0.927336 19.9958 0.916636 20.1386 0.917001 20.2815C0.92251 21.827 2.16823 23.0769 3.703 23.0769H18.131Z"
                stroke="#1a1a1a" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
        @if ($cart_count > 0)
            <span class="badge badge-primary badge-circle position-absolute"
                style="top:-6px; right:-8px; font-size:10px; min-width:18px; height:18px; line-height:18px;">{{ $cart_count }}</span>
        @endif
    </span>
</a>

<!-- Cart Dropdown - Redesigned (Simple, modern, minimal, responsive, no heavy shadows, subtle radius) -->
<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg p-0 stop-propagation cart-dropdown-minimal"
    style="min-width: 360px; max-width: 400px;">
    @if ($cart_count > 0)
        <!-- Header -->
        <div class="cart-header px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-700 text-dark">{{ translate('Shopping Cart') }}</h6>
            <span class="fs-13 text-muted">{{ $cart_count }} {{ translate('Items') }}</span>
        </div>
        <!-- Cart Products -->
        <ul class="list-group list-group-flush cart-products-list">
            @foreach ($carts as $key => $cartItem)
                @php
                    $product = get_single_product($cartItem['product_id']);
                    $product_price = cart_product_price($cartItem, $product, false);
                    $has_addons = !empty($cartItem['addons']);
                    $addons = $has_addons ? json_decode($cartItem['addons'], true) : [];
                @endphp

                @if ($product != null)
                    <li class="list-group-item px-4 py-3 border-0 cart-item-minimal">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center">

                                <a href="{{ route('product', $product->slug) }}"
                                    class="flex-shrink-0 mr-3 cart-thumb-minimal">

                                    <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                        data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                        class="img-fit lazyload simple-cart-img" width="64" height="64"
                                        alt="{{ $product->getTranslation('name') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    <a href="{{ route('product', $product->slug) }}"
                                        class="text-dark text-decoration-none w-100">
                                        <div class="fw-600 fs-14 product-name-text">
                                            {{ $product->getTranslation('name') }}
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-2">
                                            <div class="fs-13 text-muted">
                                                {{ $cartItem['quantity'] }} ×
                                                <span
                                                    class="fw-600 text-primary">£{{ number_format($product_price, 2) }}</span>
                                            </div>
                                            <button onclick="removeFromCart({{ $cartItem['id'] }})"
                                                class="btn btn-xs btn-light remove-btn-minimal d-flex align-items-center justify-content-center flex-shrink-0"
                                                title="{{ translate('Remove') }}">
                                                <i class="la la-trash fs-14 text-danger"></i>
                                            </button>
                                        </div>
                                    </a>


                                </a>

                            </div>
                            <div class="flex-grow-1 min-w-0">


                                @if ($has_addons && is_array($addons) && count($addons) > 0)
                                    <button
                                        class="btn btn-sm btn-link p-0 mt-2 fs-12 fw-500 text-primary text-decoration-none minimal-addon-toggle"
                                        type="button" data-toggle="collapse"
                                        data-target="#addonCollapse{{ $cartItem['id'] }}" aria-expanded="false"
                                        aria-controls="addonCollapse{{ $cartItem['id'] }}">
                                        <i class="la la-plus-circle mr-1"></i> {{ translate('View Addons') }}
                                        ({{ count($addons) }})
                                        <i class="la la-angle-down ml-1"></i>
                                    </button>
                                    <div class="collapse mt-2" id="addonCollapse{{ $cartItem['id'] }}">
                                        <div class="p-2 addoncolor simple-addon-list">
                                            @foreach ($addons as $addon)
                                                @if (is_array($addon))
                                                    <div
                                                        class="d-flex justify-content-between align-items-start fs-12 text-black mb-1 addon-row">
                                                        <span class="addon-name-text">
                                                            <i class="la la-check-circle fs-12 mr-1 text-success"></i>
                                                            <strong class="text-black">  {{ $addon['addon_name'] }}:</strong>
                                                            {{ $addon['name'] ?? ($addon[0] ?? '') }}
                                                        </span>
                                                        <span
                                                            class="fw-500 text-dark addon-price-text flex-shrink-0 ml-2">
                                                            +£{{ number_format($addon['price'] ?? 0, 2) }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="fs-12 text-black mb-1 addon-name-text">
                                                        <i class="la la-check-circle fs-12 mr-1 text-success"></i>
                                                        {{ $addon }}
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </li>
                @endif
            @endforeach
        </ul>
        <!-- Subtotal -->
        <div class="px-2 py-2 border-top cart-totals-minimal" style="background: ; border-radius: 0; margin-bottom: 0;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fs-15 fw-600 text-muted" style="letter-spacing: 0.01em;">{{ 'Subtotal' }}</span>
                <span class="fs-15 fw-600 text-muted">£{{ number_format($total, 2) }}</span>
            </div>
            <div class="d-flex gap-2 flex-row align-items-stretch" style="gap: 8px;">
                <a href="{{ route('cart') }}"
                    class="btn borderbtn d-flex  rounded-5 align-items-center flex-fill justify-content-center">
                    <i class="la la-shopping-cart mr-2 fs-18"></i>
                    <span class="fw-700 fs-12">{{ translate('View cart') }}</span>
                </a>
                @if (Auth::check())
                    <a href="{{ route('checkout.shipping_info') }}"
                        class="btn borderbtn d-flex align-items-center flex-fill justify-content-center">
                        <span class="fw-700 fs-12 ">{{ translate('Proceed to Checkout') }}</span>
                        <i class="la la-arrow-right ml-2 fs-17"></i>
                    </a>
                @else
                    <a href="{{ route('user.login') }}"
                        class="cart-btn-black d-flex align-items-center flex-fill justify-content-center">
                        <span class="fw-700 fs-12">{{ translate('Login to Checkout') }}</span>
                        <i class="la la-arrow-right ml-2 fs-17"></i>
                    </a>
                @endif
            </div>
        </div>
        <style>
            .cart-btn-outline {
                border: 1px solid #403834;
                background: transparent;
                color: #403834;
                border-radius: 24px;
                padding: 0 18px;
                font-weight: 500;
                font-size: 14px;
                transition: border .17s, color .17s, background .17s;
                min-width: 0;
                box-shadow: none;
                white-space: nowrap;
            }

            .cart-btn-outline:hover,
            .cart-btn-outline:focus {
                background: #f2eee7;
                color: #29232b;
                border-color: #29232b;
                text-decoration: none;
            }

            .cart-btn-outline i {
                font-size: 20px !important;
                margin-right: 8px;
                color: #403834;
            }

            .cart-btn-black {
                background: #000;
                color: #fff;
                border-radius: 24px;
                padding: 0 26px;
                font-weight: 700;
                font-size: 16px;
                border: none;
                min-width: 0;
                box-shadow: none;
                white-space: nowrap;
                height: 48px;
                transition: background .14s, color .14s;
            }

            .cart-btn-black:hover,
            .cart-btn-black:focus {
                background: #1d1d23;
                color: #fff;
                text-decoration: none;
            }

            .cart-btn-black i {
                margin-left: 8px;
                font-size: 21px !important;
                color: #fff;
            }

            @media (max-width: 575.98px) {

                .cart-btn-outline,
                .cart-btn-black {
                    font-size: 14px;
                    padding: 0 10px;
                    height: 40px;
                    border-radius: 17px;
                }
            }
        </style>

        <style>
            .cart-action-btn {
                /* allow both buttons to grow/shrink in flex row, no width set */
                min-width: 0;
                padding: 0;
                text-align: center;
                white-space: normal;
                font-weight: 700;
                font-size: 18px;
                box-shadow: none;
            }

            .cart-action-btn i.la-shopping-cart {
                margin-bottom: 2px;
            }

            .cart-action-btn:first-child {
                background: #f6f6f9;
                color: #32323b;
                border: none;
            }

            .cart-action-btn:last-child,
            .cart-action-btn:not(:first-child) {
                background: #22222c;
                color: #fff;
                border: none;
            }

            @media (max-width: 575.98px) {
                .cart-action-btn {
                    font-size: 16px;
                    min-height: 54px;
                    border-radius: 18px;
                }
            }
        </style>
    @else
        <!-- Empty Cart -->
        <div class="text-center py-5 px-4">
            <div class="mb-3">
                <i class="la la-shopping-bag la-3x text-muted opacity-40"></i>
            </div>
            <h6 class="fw-700 text-dark mb-2">{{ translate('Your Cart is Empty') }}</h6>
            <p class="fs-13 text-muted mb-3">{{ translate('Looks like you haven’t added anything to your cart yet.') }}
            </p>
            <a href="{{ route('home') }}"
                class="btn btn-primary btn-sm rounded-pill px-4">{{ translate('Start Shopping') }}</a>
        </div>
    @endif
</div>

<script>
    // Rotate arrow on collapse toggle
    $('.collapse').on('show.bs.collapse', function() {
        $(this).prev('button').find('.la-angle-down').addClass('la-rotate-180');
    });
    $('.collapse').on('hide.bs.collapse', function() {
        $(this).prev('button').find('.la-angle-down').removeClass('la-rotate-180');
    });
</script>

<style>
    /* --- Simpler, modern, minimal redesign for cart dropdown --- */
    .cart-dropdown-minimal {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 10px;
        /* no box-shadow */
        overflow: visible !important;
        max-width: 400px;
        width: 100%;
    }

    .cart-header {
        background: #faf9f7;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        border-bottom: 1px solid #ececec !important;
    }

    .cart-products-list {
        overflow: visible !important;
        max-height: none !important;
        background: #fff;
    }

    .cart-item-minimal {
        background: #fff !important;
        border: none !important;
        border-radius: 0;
        padding-top: 18px;
        padding-bottom: 18px;
        margin-bottom: 2px;
    }

    .cart-thumb-minimal img.simple-cart-img {
        border-radius: 8px !important;
        border: 1px solid #e6e6e6;
        background: #f7f7f7;
        object-fit: cover;
        width: 64px;
        height: 64px;
    }

    .simple-btn {
        border-radius: 24px !important;
        min-width: 150px;
        transition: background 0.2s, color 0.2s;
        margin-bottom: 8px;
        margin-right: 0;
        margin-left: 0;
    }

    @media (min-width: 768px) {
        .simple-btn {
            margin-bottom: 0;
            margin-right: 8px;
        }
    }

    .simple-btn:last-child {
        margin-right: 0;
    }

    .remove-btn-minimal {
        border-radius: 50% !important;
        background: transparent !important;
        width: 28px;
        height: 28px;
        border: 1px solid #ececec !important;
        transition: background .1s;
    }

    .remove-btn-minimal:hover,
    .remove-btn-minimal:focus {
        background: #f7f7f7 !important;
        box-shadow: none !important;
        outline: none;
    }

    .simple-addon-list {
        background: #FAF7F2;
        border-radius: 8px !important;
        margin-top: 0;
        margin-bottom: 0;
    }

    .minimal-addon-toggle {
        color: #4169e1 !important;
        font-weight: 500;
        font-size: 13px;
        background: none !important;
        border-radius: 6px !important;
        padding: 2px 0 !important;
    }

    .minimal-addon-toggle:focus {
        outline: none;
        box-shadow: none !important;
    }

    .simple-subtotal-row {
        background: #f2ede6;
        border-radius: 0 0 10px 10px;
        border-top: 1px solid #ececec !important;
    }

    .la-rotate-180 {
        transform: rotate(180deg);
        transition: transform 0.2s ease;
    }

    /* Hide scrollbar for all browsers */
    .cart-dropdown-no-scroll,
    .cart-products-list {
        -ms-overflow-style: none;
        /* IE and Edge */
        scrollbar-width: none;
        /* Firefox */
    }

    .cart-dropdown-no-scroll::-webkit-scrollbar,
    .cart-products-list::-webkit-scrollbar {
        display: none;
        width: 0;
    }

    /* Make sure text wraps responsively */
    .product-name-text,
    .addon-name-text {
        word-wrap: break-word;
        word-break: break-word;
        white-space: normal;
        overflow-wrap: anywhere;
        line-height: 1.4;
        min-width: 0;
    }

    .addon-row {
        gap: 8px;
    }

    .addon-price-text {
        white-space: nowrap;
    }

    /* Responsive adjustments */
    @media (max-width: 500px) {
        .cart-dropdown-minimal {
            min-width: 100vw !important;
            max-width: 100vw !important;
            border-radius: 0px !important;
            left: 0 !important;
            right: 0 !important;
        }

        .cart-header,
        .simple-subtotal-row {
            border-radius: 0 !important;
        }
    }
</style>
