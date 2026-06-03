@php
    $cart_added = [];
    $active_offer = get_product_active_offer($product);
@endphp
<div class="aiz-card-box h-auto bg-white p-3 hov-scale-img modern-product-card position-relative">
    @php
        $product_url = route('product', $product->slug);
        if ($product->auction_product == 1) {
            $product_url = route('auction-product', $product->slug);
        }
    @endphp
    <!-- Card Link Overlay -->
    <a href="{{ $product_url }}" class="position-absolute h-100 w-100" style="top: 0; left: 0; z-index: 1;"></a>

     <div class="position-relative h-140px h-md-160px img-fit overflow-hidden modern-product-img-wrap">
        @php
            $product_url = route('product', $product->slug);
            if ($product->auction_product == 1) {
                $product_url = route('auction-product', $product->slug);
            }
        @endphp
        <!-- Image -->
        <a href="{{ $product_url }}" class="d-block h-100">
            <img class="lazyload mx-auto img-fit has-transition modern-product-img" style="height: 100% !important;"
                src="{{ get_image($product->thumbnail) }}" alt="{{ $product->getTranslation('name') }}"
                title="{{ $product->getTranslation('name') }}"
                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
        </a>

        <!-- Discount percentage tag -->
        @if ($active_offer)
            @php
                $badge_txt = $active_offer->badge_text;
                if (
                    is_numeric($badge_txt) ||
                    (str_ends_with($badge_txt, '%') && !str_contains(strtolower($badge_txt), 'off'))
                ) {
                    $badge_txt .= ' OFF';
                }
            @endphp
            <span class="absolute-top-left text-white px-2 py-1 fs-10 fw-700 ml-2 mt-2 modern-discount-tag"
                style="background-color: var(--primary, #685b4e); border-radius: 4px; text-transform: uppercase; z-index: 1;">
                {{ $badge_txt }}
            </span>
        @elseif (discount_in_percentage($product) > 0)
            <span class="absolute-top-left bg-primary ml-2 mt-2 fs-11 fw-700 text-white px-2 py-1 modern-discount-tag"
                style="border-radius: 4px; z-index: 1;">-{{ discount_in_percentage($product) }}%</span>
        @endif

        <!-- Wholesale tag -->
        @if ($product->wholesale_product)
            <span class="absolute-top-left fs-11 text-white fw-700 px-2 py-1 ml-2 mt-2 modern-wholesale-tag"
                style="background-color: #455a64; border-radius: 4px; z-index: 1; @if (discount_in_percentage($product) > 0 || $active_offer) top:32px; @endif">
                {{ translate('Wholesale') }}
            </span>
        @endif
    </div>

    <div class="p-2 p-md-3 text-left">
        <!-- Product name -->
        <h3 class="fw-500 fs-14 text-truncate-2 lh-1-4 mb-1 modern-product-title">
            <a href="{{ $product_url }}" class="d-block text-reset hov-text-primary"
                style="letter-spacing:0.3px !important;"
                title="{{ $product->getTranslation('name') }}">{{ $product->getTranslation('name') }}</a>
        </h3>

        <div class="modern-card-bottom-row">
            <!-- Price Wrap (Left) -->
            <div class="fs-14 modern-price-wrap 1">
                @if ($product->auction_product == 0)
                    @if ($active_offer)
                        @php
                            $old_offer_price = home_offer_old_price($product);
                        @endphp
                        <div class="d-flex align-items-center flex-wrap" style="gap: 2px;">
                            @if ($old_offer_price)
                                <del class="fw-400 fs-11 text-secondary"
                                    style="text-decoration: line-through; opacity: 0.6; color: #9e9e9e !important;">{{ $old_offer_price }}</del>
                            @endif
                            <span class="fw-700 fs-14 text-primary">{{ home_discounted_base_price($product) }}</span>
                        </div>
                    @else
                        <!-- Previous price -->
                        @if (home_base_price($product) != home_discounted_base_price($product))
                            <div class="disc-amount has-transition mr-2">
                                <del class="fw-400 text-secondary fs-11"
                                    style="opacity: 0.6;">{{ home_base_price($product) }}</del>
                            </div>
                        @endif
                        <!-- price -->
                        <div class="price d-block">
                            <span class="fw-700 text-primary fs-14">{{ home_discounted_base_price($product) }}</span>
                        </div>
                    @endif
                @endif
                @if ($product->auction_product == 1)
                    <!-- Bid Amount -->
                    <div class="">
                        <span class="fw-700 text-primary fs-14">{{ single_price($product->starting_bid) }}</span>
                    </div>
                @endif
            </div>

            <!-- Action Buttons (Right) -->
            <div class="modern-card-actions-bottom" style="position: relative; z-index: 2;">
                @if ($product->auction_product == 0)
                    <!-- Wishlist Icon -->
                    <a href="javascript:void(0)" class="modern-action-btn wishlist-btn"
                        onclick="addToWishList({{ $product->id }})" data-toggle="tooltip"
                        data-title="{{ translate('Add to wishlist') }}" data-placement="top">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="svg-heart-icon">
                            <path
                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                            </path>
                        </svg>
                    </a>

                    <!-- Add to Basket (Sleek Cart Icon) -->
                    <a href="javascript:void(0)"
                        class="modern-action-btn cart-btn @if (in_array($product->id, $cart_added)) active @endif"
                        @if (Auth::check()) onclick="showAddToCartModal({{ $product->id }})" @else onclick="showLoginModal()" @endif
                        data-toggle="tooltip" data-title="{{ translate('Add to Basket') }}" data-placement="top">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="svg-cart-icon">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
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
                    <a href="javascript:void(0)" class="modern-action-btn bid-btn"
                        onclick="bid_single_modal({{ $product->id }}, {{ $min_bid_amount }})" data-toggle="tooltip"
                        data-title="{{ translate('Place Bid') }}" data-placement="top">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="svg-bid-icon">
                            <path
                                d="m14 13-5 5M16 11l-3.5 3.5M6 15H2v8h8v-4M21 3l-7 7M21 3a2.5 2.5 0 1 0-3.5 3.5L21 3Z">
                            </path>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
