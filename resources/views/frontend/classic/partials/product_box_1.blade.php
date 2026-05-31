@php
    $cart_added = [];
    $active_offer = get_product_active_offer($product);
@endphp
<div class="aiz-card-box h-auto bg-white py-3 hov-scale-img">
    <div class="position-relative h-140px h-md-200px img-fit overflow-hidden">
        @php
            $product_url = route('product', $product->slug);
            if ($product->auction_product == 1) {
                $product_url = route('auction-product', $product->slug);
            }
        @endphp
        <!-- Image -->
        <a href="{{ $product_url }}" class="d-block h-100">
            <img class="lazyload mx-auto img-fit has-transition"
                src="{{ get_image($product->thumbnail) }}"
                alt="{{ $product->getTranslation('name') }}" title="{{ $product->getTranslation('name') }}"
                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
        </a>
        <!-- Discount percentage tag -->
        @if ($active_offer)
            @php
                $badge_txt = $active_offer->badge_text;
                if (is_numeric($badge_txt) || (str_ends_with($badge_txt, '%') && !str_contains(strtolower($badge_txt), 'off'))) {
                    $badge_txt .= ' OFF';
                }
            @endphp
            <span class="absolute-top-left text-white px-2 py-1 fs-10 fw-700 ml-1 mt-1"
                style="background-color: var(--primary, #685b4e); border-radius: 2px; text-transform: uppercase; z-index: 1;">
                {{ $badge_txt }}
            </span>
        @elseif (discount_in_percentage($product) > 0)
            <span class="absolute-top-left bg-primary ml-1 mt-1 fs-11 fw-700 text-white w-35px text-center"
                style="padding-top:2px;padding-bottom:2px;">-{{ discount_in_percentage($product) }}%</span>
        @endif
        <!-- Wholesale tag -->
        @if ($product->wholesale_product)
            <span class="absolute-top-left fs-11 text-white fw-700 px-2 lh-1-8 ml-1 mt-1"
                style="background-color: #455a64; @if (discount_in_percentage($product) > 0) top:25px; @endif">
                {{ translate('Wholesale') }}
            </span>
        @endif
        @if ($product->auction_product == 0)
            <!-- wishlisht & compare icons -->
            <div class="absolute-top-right aiz-p-hov-icon">
                <a href="javascript:void(0)" class="hov-svg-white" onclick="addToWishList({{ $product->id }})"
                    data-toggle="tooltip" data-title="{{ translate('Add to wishlist') }}" data-placement="left">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.4" viewBox="0 0 16 14.4">
                        <g id="_51a3dbe0e593ba390ac13cba118295e4" data-name="51a3dbe0e593ba390ac13cba118295e4"
                            transform="translate(-3.05 -4.178)">
                            <path id="Path_32649" data-name="Path 32649"
                                d="M11.3,5.507l-.247.246L10.8,5.506A4.538,4.538,0,1,0,4.38,11.919l.247.247,6.422,6.412,6.422-6.412.247-.247A4.538,4.538,0,1,0,11.3,5.507Z"
                                transform="translate(0 0)" fill="#919199" />
                            <path id="Path_32650" data-name="Path 32650"
                                d="M11.3,5.507l-.247.246L10.8,5.506A4.538,4.538,0,1,0,4.38,11.919l.247.247,6.422,6.412,6.422-6.412.247-.247A4.538,4.538,0,1,0,11.3,5.507Z"
                                transform="translate(0 0)" fill="#919199" />
                        </g>
                    </svg>
                </a>
                <a href="javascript:void(0)" class="hov-svg-white" onclick="addToCompare({{ $product->id }})"
                    data-toggle="tooltip" data-title="{{ translate('Add to compare') }}" data-placement="left">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                        <path id="_9f8e765afedd47ec9e49cea83c37dfea" data-name="9f8e765afedd47ec9e49cea83c37dfea"
                            d="M18.037,5.547v.8a.8.8,0,0,1-.8.8H7.221a.4.4,0,0,0-.4.4V9.216a.642.642,0,0,1-1.1.454L2.456,6.4a.643.643,0,0,1,0-.909L5.723,2.227a.642.642,0,0,1,1.1.454V4.342a.4.4,0,0,0,.4.4H17.234a.8.8,0,0,1,.8.8Zm-3.685,4.86a.642.642,0,0,0-1.1.454v1.661a.4.4,0,0,1-.4.4H2.84a.8.8,0,0,0-.8.8v.8a.8.8,0,0,0,.8.8H12.854a.4.4,0,0,1,.4.4V17.4a.642.642,0,0,0,1.1.454l3.267-3.268a.643.643,0,0,0,0-.909Z"
                            transform="translate(-2.037 -2.038)" fill="#919199" />
                    </svg>
                </a>
            </div>
            <!-- add to cart -->
            <a class="cart-btn absolute-bottom-left w-100 h-35px aiz-p-hov-icon text-white fs-13 fw-700 d-flex flex-column justify-content-center align-items-center @if (in_array($product->id, $cart_added)) active @endif"
                href="javascript:void(0)"
                @if (Auth::check()) onclick="showAddToCartModal({{ $product->id }})" @else onclick="showLoginModal()" @endif>
                <span class="cart-btn-text">
                    {{ translate('Add to Basket') }}
                </span>
                <span><i class="las la-2x la-shopping-cart"></i></span>
            </a>
        @endif
        @if (
            $product->auction_product == 1 &&
                $product->auction_start_date <= strtotime('now') &&
                $product->auction_end_date >= strtotime('now'))
            <!-- Place Bid -->
            @php
                $carts = get_user_cart();
                if (count($carts) > 0) {
                    $cart_added = $carts->pluck('product_id')->toArray();
                }
                $highest_bid = $product->bids->max('amount');
                $min_bid_amount = $highest_bid != null ? $highest_bid + 1 : $product->starting_bid;
            @endphp
            <a class="cart-btn absolute-bottom-left w-100 h-35px aiz-p-hov-icon text-white fs-13 fw-700 d-flex flex-column justify-content-center align-items-center @if (in_array($product->id, $cart_added)) active @endif"
                href="javascript:void(0)" onclick="bid_single_modal({{ $product->id }}, {{ $min_bid_amount }})">
                <span class="cart-btn-text">{{ translate('Place Bid') }}</span>
                <br>
                <span><i class="las la-2x la-gavel"></i></span>
            </a>
        @endif
    </div>

    <div class="p-2 p-md-3 text-left">
        <!-- Product name -->
        <h3 class="fw-400 fs-13 text-truncate-2 lh-1-4 mb-0 h-35px text-center">
            <a href="{{ $product_url }}" class="d-block text-reset hov-text-primary"
                title="{{ $product->getTranslation('name') }}">{{ $product->getTranslation('name') }}</a>
        </h3>
        <div class="fs-14 d-flex justify-content-center mt-3 align-items-center flex-wrap">
            @if ($product->auction_product == 0)
                @if ($active_offer)
                    @php
                        $old_offer_price = home_offer_old_price($product);
                    @endphp
                    <div class="text-center d-flex flex-column align-items-center justify-content-center" style="gap: 2px; width: 100%;">
                        <span class="fw-700 fs-15" style="color: #e29c09 !important;">{{ home_discounted_base_price($product) }}</span>
                        @if ($old_offer_price)
                            <del class="fw-500 fs-12 text-secondary" style="text-decoration: line-through; opacity: 0.7; color: #9e9e9e !important;">{{ $old_offer_price }}</del>
                        @endif
                    </div>
                @else
                    <!-- Previous price -->
                    @if (home_base_price($product) != home_discounted_base_price($product))
                        <div class="disc-amount has-transition">
                            <del class="fw-400 text-secondary mr-1">{{ home_base_price($product) }}</del>
                        </div>
                    @endif
                    <!-- price -->
                    <div class="">
                        <span class="fw-700 text-primary">{{ home_discounted_base_price($product) }}</span>
                    </div>
                @endif
            @endif
            @if ($product->auction_product == 1)
                <!-- Bid Amount -->
                <div class="">
                    <span class="fw-700 text-primary">{{ single_price($product->starting_bid) }}</span>
                </div>
            @endif
        </div>
    </div>
</div>
