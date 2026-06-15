<div class="aiz-sidebar-wrap ">
    <div class="aiz-sidebar left c-scrollbar side">
        <div class="aiz-side-nav-logo-wrap ">
            <div class="d-flex align-items-center justify-content-between p-3 logo position-relative">
                @php
                    $user = Auth::user();
                    $shop = $user && isset($user->shop) ? $user->shop : null;
                @endphp

                <div class="flex-grow-1 text-center">
                    @if ($shop && $shop->logo != null)
                        <img class="logoimg" src="{{ asset('public/assets/img/logoT.png') }}"
                            alt="{{ get_setting('site_name') }}">
                    @else
                        <img class="logoimg" src="{{ uploaded_asset(get_setting('header_logo')) }}"
                            alt="{{ get_setting('site_name') }}">
                    @endif
                </div>

                <button class="btn btn-icon btn-sm text-white d-xl-none position-absolute" style="right: 12px; top: 15px; z-index: 10;" onclick="closeMobileSidebar()">
                    <i class="las la-times fs-20"></i>
                </button>
            </div>

            <div class="shop-title-container px-3 text-center mb-3">
                <div class="shop-badge py-2 px-3">
                    <span class="fs-14 fw-600 text-white d-block text-truncate">{{ optional(Auth::user()->shop)->name }}</span>
                </div>
            </div>
        </div>

        <div class="aiz-side-nav-wrap">
          {{--  <div class="px-20px mb-3">
                <input class="form-control bg- border-0 form-control-sm" type="text" name=""
                    placeholder="{{ translate('Search in menu') }}" id="menu-search" onkeyup="menuSearch()">
            </div>--}}
           {{-- <ul class="aiz-side-nav-list" id="search-menu">
            </ul>--}}
            <ul class="aiz-side-nav-list" id="main-menu" data-toggle="aiz-side-menu">
                <li class="aiz-side-nav-item">
                    <a href="{{ route('seller.dashboard') }}" class="aiz-side-nav-link">
                        <i class="las la-home aiz-side-nav-icon icon1"></i>
                        <span class="aiz-side-nav-text t">{{ translate('Dashboard') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <i class="las la-shopping-cart aiz-side-nav-icon icon1"></i>
                        <span class="aiz-side-nav-text t">{{ translate('Products') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <!--Submenu-->
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('seller.products.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['seller.products.index', 'seller.products.create', 'seller.products.edit']) }}">
                                <span class="aiz-side-nav-text t">{{ translate('Products') }}</span>
                            </a>
                        </li>

                        <li class="aiz-side-nav-item">
                            <a href="{{ route('seller.attributes.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['seller.attributes.index', 'seller.attributes.create', 'seller.attributes.edit']) }}">
                                <span class="aiz-side-nav-text t">{{ translate('Attributes') }}</span>
                            </a>
                        </li>

                       {{-- <li class="aiz-side-nav-item">
                            <a href="{{ route('seller.product_bulk_upload.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['product_bulk_upload.index']) }}">
                                <span class="aiz-side-nav-text t">{{ translate('Product Bulk Upload') }}</span>
                            </a>
                        </li>--}}

                        {{--<li class="aiz-side-nav-item">
                            <a href="{{ route('seller.digitalproducts') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['seller.digitalproducts', 'seller.digitalproducts.create', 'seller.digitalproducts.edit']) }}">
                                <span class="aiz-side-nav-text t">{{ translate('Digital Products') }}</span>
                            </a>
                        </li>--}}
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('seller.reviews') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['seller.reviews']) }}">
                                <span class="aiz-side-nav-text t">{{ translate('Product Reviews') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('seller.uploaded-files.index') }}"
                        class="aiz-side-nav-link {{ areActiveRoutes(['seller.uploaded-files.index', 'seller.uploads.create']) }}">
                        <i class="las la-folder-open aiz-side-nav-icon icon1"></i>
                        <span class="aiz-side-nav-text t">{{ translate('Uploaded Files') }}</span>
                    </a>
                </li>
                @if (addon_is_activated('seller_subscription'))
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-shopping-cart aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text t">{{ translate('Package') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('seller.seller_packages_list') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text t">{{ translate('Packages') }}</span>
                                </a>
                            </li>

                            <li class="aiz-side-nav-item">
                                <a href="{{ route('seller.packages_payment_list') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text t">{{ translate('Purchase Packages') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if (get_setting('coupon_system') == 1)
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('seller.coupon.index') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['seller.coupon.index', 'seller.coupon.create', 'seller.coupon.edit']) }}">
                            <i class="las la-bullhorn aiz-side-nav-icon icon1"></i>
                            <span class="aiz-side-nav-text t">{{ translate('Coupon') }}</span>
                        </a>
                    </li>
                @endif
                <li class="aiz-side-nav-item">
                    <a href="{{ route('seller.offers.index') }}"
                        class="aiz-side-nav-link {{ areActiveRoutes(['seller.offers.index', 'seller.offers.create', 'seller.offers.edit']) }}">
                        <i class="las la-gift aiz-side-nav-icon icon1"></i>
                        <span class="aiz-side-nav-text t">{{ translate('Offers') }}</span>
                    </a>
                </li>
                @if (addon_is_activated('wholesale') && get_setting('seller_wholesale_product') == 1)
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('seller.wholesale_products_list') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['wholesale_product_create.seller', 'wholesale_product_edit.seller']) }}">
                            <i class="las la-luggage-cart aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Wholesale Products') }}</span>
                        </a>
                    </li>
                @endif
                @if (addon_is_activated('auction') && get_setting('seller_auction_product') == 1)
                    <li class="aiz-side-nav-item">
                        <a href="javascript:void(0);" class="aiz-side-nav-link">
                            <i class="las la-gavel aiz-side-nav-icon icon1"></i>
                            <span class="aiz-side-nav-text t">{{ translate('Auction') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('auction_products.seller.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['auction_products.seller.index', 'auction_product_create.seller', 'auction_product_edit.seller', 'product_bids.seller']) }}">
                                    <span class="aiz-side-nav-text t">{{ translate('All Auction Products') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('auction_products_orders.seller') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['auction_products_orders.seller']) }}">
                                    <span class="aiz-side-nav-text t">{{ translate('Auction Product Orders') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (addon_is_activated('pos_system') &&
                        get_setting('pos_activation_for_seller') != null &&
                        get_setting('pos_activation_for_seller') != 0)
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-tasks aiz-side-nav-icon icon1"></i>
                            <span class="aiz-side-nav-text t">{{ translate('POS System') }}</span>
                            @if (env('DEMO_MODE') == 'On')
                                <span class="badge badge-inline badge-danger">Addon</span>
                            @endif
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('poin-of-sales.seller_index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['poin-of-sales.seller_index']) }}">
                                    <i class="las la-fax aiz-side-nav-icon icon1"></i>
                                    <span class="aiz-side-nav-text t">{{ translate('POS Manager') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('pos.configuration') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text t">{{ translate('POS Configuration') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                <li class="aiz-side-nav-item">
                    <a href="{{ route('seller.orders.index') }}"
                        class="aiz-side-nav-link {{ areActiveRoutes(['seller.orders.index', 'seller.orders.show']) }}">
                        <i class="las la-money-bill aiz-side-nav-icon icon1"></i>
                        <span class="aiz-side-nav-text t">{{ translate('Orders') }}</span>
                    </a>
                </li>
                @if (addon_is_activated('refund_request'))
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('seller.vendor_refund_request') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['seller.vendor_refund_request', 'reason_show']) }}">
                            <i class="las la-backward aiz-side-nav-icon icon1"></i>
                            <span class="aiz-side-nav-text t">{{ translate('Received Refund Request') }}</span>
                        </a>
                    </li>
                @endif


                <li class="aiz-side-nav-item">
                    <a href="{{ route('seller.shop.index') }}"
                        class="aiz-side-nav-link {{ areActiveRoutes(['seller.shop.index']) }}">
                        <i class="las la-cog aiz-side-nav-icon icon1"></i>
                        <span class="aiz-side-nav-text t">{{ translate('Shop Setting') }}</span>
                    </a>
                </li>

                <li class="aiz-side-nav-item">
                    <a href="{{ route('seller.payments.index') }}"
                        class="aiz-side-nav-link {{ areActiveRoutes(['seller.payments.index']) }}">
                        <i class="las la-history aiz-side-nav-icon icon1"></i>
                        <span class="aiz-side-nav-text t">{{ translate('Payment History') }}</span>
                    </a>
                </li>

                <li class="aiz-side-nav-item">
                    <a href="{{ route('seller.money_withdraw_requests.index') }}"
                        class="aiz-side-nav-link {{ areActiveRoutes(['seller.money_withdraw_requests.index']) }}">
                        <i class="las la-money-bill-wave-alt aiz-side-nav-icon icon1"></i>
                        <span class="aiz-side-nav-text t">{{ translate('Money Withdraw') }}</span>
                    </a>
                </li>

                <li class="aiz-side-nav-item">
                    <a href="{{ route('seller.commission-history.index') }}" class="aiz-side-nav-link">
                        <i class="las la-file-alt aiz-side-nav-icon icon1"></i>
                        <span class="aiz-side-nav-text t">{{ translate('Commission History') }}</span>
                    </a>
                </li>

               {{-- @if (get_setting('conversation_system') == 1)
                    @php
                        $conversation = \App\Models\Conversation::where('sender_id', Auth::user()->id)
                            ->where('sender_viewed', 0)
                            ->get();
                    @endphp
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('seller.conversations.index') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['seller.conversations.index', 'seller.conversations.show']) }}">
                            <i class="las la-comment aiz-side-nav-icon icon1"></i>
                            <span class="aiz-side-nav-text t">{{ translate('Conversations') }}</span>
                            @if (count($conversation) > 0)
                                <span class="badge badge-success">({{ count($conversation) }})</span>
                            @endif
                        </a>
                    </li>
                @endif--}}

                @if (get_setting('product_query_activation') == 1)
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('seller.product_query.index') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['seller.product_query.index']) }}">
                            <i class="las la-question-circle aiz-side-nav-icon icon1"></i>
                            <span class="aiz-side-nav-text t">{{ translate('Product Queries') }}</span>

                        </a>
                    </li>
                @endif

                @php
                    $support_ticket = DB::table('tickets')
                        ->where('client_viewed', 0)
                        ->where('user_id', Auth::user()->id)
                        ->count();
                @endphp
               {{--<li class="aiz-side-nav-item">
                    <a href="{{ route('seller.support_ticket.index') }}"
                        class="aiz-side-nav-link {{ areActiveRoutes(['seller.support_ticket.index']) }}">
                        <i class="las la-atom aiz-side-nav-icon icon1"></i>
                        <span class="aiz-side-nav-text">{{ translate('Support Ticket') }}</span>
                        @if ($support_ticket > 0)
                            <span class="badge badge-inline badge-success">{{ $support_ticket }}</span>
                        @endif
                    </a>
                </li>--}}
                <li class="aiz-side-nav-item">
    <a href="https://www.youtube.com/@TimeToFurnish"
       target="_blank"
       class="aiz-side-nav-link">
        <i class="las la-play-circle aiz-side-nav-icon icon1"></i>
        <span class="aiz-side-nav-text t">Seller Guide</span>
    </a>
</li>

            </ul><!-- .aiz-side-nav -->
        </div><!-- .aiz-side-nav-wrap -->
    </div><!-- .aiz-sidebar -->
    <div class="aiz-sidebar-overlay"></div>
</div><!-- .aiz-sidebar -->
<script>
    function closeMobileSidebar() {
        if (typeof $ !== 'undefined') {
            $("body").removeClass("side-menu-open").addClass("side-menu-closed");
        } else {
            document.body.classList.remove("side-menu-open");
            document.body.classList.add("side-menu-closed");
        }
    }
</script>
<style>
    .aiz-sidebar.side {
        background: #a89c8f !important;
        border-right: none !important;
        box-shadow: 4px 0 24px rgba(0, 0, 0, 0.03) !important;
    }
    
    .aiz-side-nav-logo-wrap {
        background: #a89c8f !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
    
    .aiz-topbar {
        background: #a89c8f !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15) !important;
    }
    
    .aiz-topbar .aiz-mobile-toggler span, 
    .aiz-topbar .aiz-mobile-toggler span::before, 
    .aiz-topbar .aiz-mobile-toggler span::after {
        background-color: #ffffff !important;
    }
    
    .aiz-topbar .btn-light {
        background-color: rgba(255, 255, 255, 0.15) !important;
        border: 1px solid rgba(255, 255, 255, 0.25) !important;
        color: #ffffff !important;
    }
    
    .aiz-topbar .btn-light:hover {
        background-color: rgba(255, 255, 255, 0.25) !important;
        color: #ffffff !important;
    }
    
    .aiz-topbar .text-dark {
        color: #ffffff !important;
    }
    
    .aiz-topbar .opacity-60 {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    .aiz-sidebar-overlay {
        backdrop-filter: blur(3px);
        background: rgba(57, 50, 42, 0.3) !important;
    }

    .shop-badge {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        backdrop-filter: blur(4px);
    }

    .logo {
        background: transparent !important;
    }

    .logoimg {
        max-height: 48px !important;
        height: auto !important;
        width: auto !important;
        background: #ffffff;
        padding: 6px 12px !important;
        border-radius: 8px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        display: inline-block;
    }

    .aiz-side-nav-list .aiz-side-nav-item {
        margin: 4px 14px;
    }

    .aiz-side-nav-list .aiz-side-nav-link {
        padding: 10px 16px;
        border-radius: 10px;
        color: rgba(255, 255, 255, 0.9) !important;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        background: transparent;
    }

    .aiz-side-nav-list .aiz-side-nav-icon {
        color: rgba(255, 255, 255, 0.9) !important;
        font-size: 1.25rem !important;
        margin-right: 12px;
        transition: all 0.25s ease;
    }

    .aiz-side-nav-list .aiz-side-nav-link:hover {
        background: rgba(255, 255, 255, 0.15) !important;
        color: #ffffff !important;
    }

    .aiz-side-nav-list .aiz-side-nav-link:hover .aiz-side-nav-icon {
        color: #ffffff !important;
        transform: translateX(2px);
    }

    .aiz-side-nav-list .aiz-side-nav-link.active,
    .aiz-side-nav-list .aiz-side-nav-link.active .aiz-side-nav-text,
    .aiz-side-nav-list .aiz-side-nav-link.level-2-active,
    .aiz-side-nav-list .aiz-side-nav-link.level-2-active .aiz-side-nav-text {
        color: #39322a !important;
        background: #ffffff !important;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(57, 50, 42, 0.08) !important;
    }

    .aiz-side-nav-list .aiz-side-nav-link.active .aiz-side-nav-icon,
    .aiz-side-nav-list .aiz-side-nav-link.level-2-active .aiz-side-nav-icon {
        color: #39322a !important;
    }

    /* Submenu nesting indicator */
    .aiz-side-nav-list .level-2 {
        padding-left: 10px;
        margin-top: 4px;
        margin-bottom: 4px;
        border-left: 1px solid rgba(255, 255, 255, 0.25);
    }

    [dir="rtl"] .aiz-side-nav-list .level-2 {
        padding-left: 0;
        padding-right: 10px;
        border-left: none;
        border-right: 1px solid rgba(255, 255, 255, 0.25);
    }

    .aiz-side-nav-list .level-2 .aiz-side-nav-item {
        margin: 2px 0 2px 14px;
    }

    [dir="rtl"] .aiz-side-nav-list .level-2 .aiz-side-nav-item {
        margin: 2px 14px 2px 0;
    }

    .aiz-side-nav-list .level-2 .aiz-side-nav-link {
        padding: 8px 12px;
        font-size: 13px;
        color: rgba(255, 255, 255, 0.8) !important;
        border-radius: 8px;
    }

    .aiz-side-nav-list .level-2 .aiz-side-nav-link:after {
        display: none !important;
    }

    .aiz-side-nav-list .level-2 .aiz-side-nav-link.active {
        color: #39322a !important;
        background: #ffffff !important;
    }

    /* Custom thin scrollbar */
    .aiz-sidebar.c-scrollbar::-webkit-scrollbar {
        width: 5px;
    }
    .aiz-sidebar.c-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .aiz-sidebar.c-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
    }
    .aiz-sidebar.c-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.35);
    }
</style>
