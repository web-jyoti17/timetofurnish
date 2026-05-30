<div class="text-left">
    <style>
        .select2-container .select2-selection--single .select2-selection__rendered {
            width: 100% !important;
        }

        .fabric-img-tooltip {
            pointer-events: none;
        }

        .fabric-color-box {
            cursor: pointer;
            touch-action: manipulation;
        }

        /* Base dropdown */
        .custom-dropdown {
            background-color: #b57a45;
            color: #fff;
            border: 1px solid #b57a45;
            border-radius: 8px;
            padding: 10px 40px 10px 12px;
            font-size: 14px;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            cursor: pointer;
            position: relative;
        }

        /* Closed select (main box) */
        .select2-container--default .select2-selection--single {
            background-color: #fff !important;
            border: 1.5px solid #e5e5e5 !important;
            border-radius: 12px !important;
            height: 52px;
            display: flex;
            align-items: center;
            padding: 0 14px;
            transition: all 0.2s ease;
        }

        /* Text */
        .select2-container--default .select2-selection__rendered {
            color: #333 !important;
            font-weight: 500;
        }

        /* Placeholder */
        .select2-container--default .select2-selection__placeholder {
            color: #999 !important;
        }

        /* Arrow */
        .select2-container--default .select2-selection__arrow b {
            border-color: #b57a45 transparent transparent transparent !important;
        }

        /* Hover + focus */
        .select2-container--default .select2-selection--single:hover,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #b57a45 !important;
            box-shadow: 0 0 0 2px rgba(181, 122, 69, 0.1);
        }

        /* Dropdown */
        .select2-dropdown {
            border-radius: 12px !important;
            border: 1px solid #eee !important;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        /* Options */
        .select2-results__option {
            padding: 12px 14px;
            font-size: 14px;
            color: #333;
        }

        /* Hover option (soft, not heavy) */
        .select2-results__option--highlighted {
            background-color: #f5ede7 !important;
            color: #b57a45 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 26px;
            position: absolute;
            top: 12px;
            right: 11px;
            width: 20px;
        }



        /* Selected option */
        .select2-results__option[aria-selected="true"] {
            background-color: rgba(181, 122, 69, 0.1) !important;
            color: #b57a45 !important;
            font-weight: 500;
        }

        /* Hover + focus */
        .custom-dropdown:hover,
        .custom-dropdown:focus {
            background-color: #f5ede7;
            color: #b57a45;
            border-color: #b57a45;
            outline: none;
        }

        /* Custom arrow icon */
        .custom-dropdown {
            background-image: url("data:image/svg+xml;utf8,<svg fill='%23b57a45' height='20' viewBox='0 0 20 20' width='20' xmlns='http://www.w3.org/2000/svg'><path d='M5 7l5 5 5-5z'/></svg>");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
        }

        /* Option styling (limited support across browsers) */
        .custom-dropdown option {
            background: #fff;
            color: #333;
        }

        /* For multiple select */
        .custom-dropdown[multiple] {
            height: auto;
            padding-right: 12px;
        }

        .addon-option-card {
            cursor: pointer;
            display: block;
            line-height: 23px;
        }

        .addon-option-card .card {
            border: 2px solid #eee;
            transition: 0.3s;
        }

        .option-input:checked+.card {
            border-color: #0d6efd;
            background: #f0f7ff;
        }

        .addon-option-card:hover .card {
            border-color: #999;
        }

        /* Make grid tighter */
        .addon-block .row {
            row-gap: 8px;
        }

        /* SUPER SMALL square */
        .fabric-box {
            position: relative;
        }

        .stylish-addon-dropdown {
            background: #f8f8f9;
            border: 1.5px solid #e2e6ea;
            border-radius: 8px;
            padding: 10px;
            font-size: 15px;
        }

        .fabric-box {
            position: relative;
            cursor: pointer;
            border-radius: 6px;
            overflow: hidden;
        }

        .fabric-box img {
            width: 100%;
            border-radius: 6px;
            transition: 0.3s;
        }

        .fabric-box:hover img {
            transform: scale(1.05);
        }

        /* Hover Preview Popup */
        .fabric-hover {
            position: absolute;
            bottom: 110%;
            left: 50%;
            transform: translateX(-50%);
            width: 160px;
            background: rgba(0, 0, 0, 0.85);
            padding: 8px;
            border-radius: 8px;
            display: none;
            text-align: center;
            z-index: 99;
        }

        .fabric-hover img {
            width: 100%;
            border-radius: 6px;
        }

        .fabric-hover span {
            color: #fff;
            font-size: 13px;
        }

        .fabric-box:hover .fabric-hover {
            display: block;
        }

        /* Active selected */
        .fabric-box.active {
            border: 2px solid #000;
        }

        /* Preview beside dropdown */
        .addon-preview img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 6px;
        }

        /* Preview Box */
        .fabric-preview {
            position: absolute;
            bottom: 120%;
            left: 50%;
            transform: translateX(-50%) scale(0.9);
            width: 120px;
            background: #2b2b2b;
            border-radius: 6px;
            padding: 6px;
            text-align: center;
            opacity: 0;
            pointer-events: none;
            transition: 0.2s ease;
            z-index: 99;
        }

        /* Preview Image */
        .fabric-preview img {
            width: 100%;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
        }

        /* Name */
        .fabric-preview span {
            display: block;
            color: #fff;
            font-size: 12px;
            margin-top: 4px;
        }

        /* Arrow */
        .fabric-preview::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border-width: 6px;
            border-style: solid;
            border-color: #2b2b2b transparent transparent transparent;
        }

        /* SHOW ON HOVER */
        .fabric-box:hover .fabric-preview {
            opacity: 1;
            transform: translateX(-50%) scale(1);
        }

        /* Image fit */
        .fabric-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Hover zoom */
        .fabric-box:hover {
            transform: scale(1.08);
            border-color: #000;
        }

        .fabric-selected-name {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: #000;
            color: #fff;
            font-size: 11px;
            text-align: center;
            padding: 3px;
            opacity: 0;
            transition: 0.2s;
        }

        /* Show on hover (top tooltip already) */
        .fabric-box:hover .fabric-name {
            opacity: 1;
        }

        /* SHOW NAME WHEN SELECTED */
        .option-input:checked+.fabric-box .fabric-selected-name {
            opacity: 1;
        }

        .option-input:checked+.fabric-box {
            border: 1px solid #ccc;
            padding: 5px;
            border-radius: 5px;
        }

        /* Keep border highlight */


        /* Name (tooltip style) */
        .fabric-name {
            position: absolute;
            bottom: 110%;
            /* ABOVE box */
            left: 50%;
            transform: translateX(-50%);
            background: #000;
            color: #fff;
            font-size: 11px;
            padding: 3px 6px;
            border-radius: 4px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: 0.2s;
        }

        /* Show on hover */
        .fabric-box:hover .fabric-name {
            opacity: 1;
        }

        /* Selected */
        .option-input:checked+.fabric-box {
            border: 2px solid #000;
        }

        /* 📱 Responsive (MORE ITEMS PER ROW) */
        .col-md-2 {
            flex: 0 0 12.5%;
            /* 8 items in row */
            max-width: 12.5%;
        }

        @media (max-width: 992px) {
            .col-md-2 {
                flex: 0 0 16.66%;
                /* 6 items */
                max-width: 16.66%;
            }
        }

        @media (max-width: 768px) {
            .col-md-2 {
                flex: 0 0 20%;
                /* 5 items */
                max-width: 20%;
            }
        }

        @media (max-width: 480px) {
            .col-md-2 {
                flex: 0 0 25%;
                /* 4 items */
                max-width: 25%;
            }
        }

        .addon-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin: 20px 0 30px;
            align-items: center;
        }

        .addon-tab-btn {
            background: #fff;
            border: 1px solid #d4d4d4;
            color: #222;
            padding: 10px 22px;
            border-radius: 30px;
            font-size: 15px;
            font-weight: 500;
            transition: all .3s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .addon-tab-btn:hover {
            background: #f5ede7;
            border-color: #b57a45;
            color: #b57a45;
            transform: translateY(-2px);
        }

        .addon-tab-btn.active {
            background: #b57a45;
            color: #fff;
            border-color: #b57a45;
            box-shadow: 0 4px 12px rgba(181, 122, 69, .25);
        }

        .addon-tab-btn:focus {
            outline: none;
            box-shadow: none;
        }
    </style>
    <style>
        /* Ensure buttons are stacked and full-width on small screens */
        @media (max-width: 575.98px) {
            .product-action-buttons .btn {
                margin-bottom: 10px;
                width: 100% !important;
            }
        }

        .product-action-buttons .btn {
            min-height: 46px;
            font-size: 1.05rem;
        }
    </style>
    <style>
        /* Custom Premium Disabled Buttons Style */
        .btn-disabled-custom {
            opacity: 0.55 !important;
            cursor: not-allowed !important;
            pointer-events: none !important;
            filter: grayscale(40%) !important;
            box-shadow: none !important;
        }

        .disabled-wishlist {
            opacity: 0.55 !important;
            cursor: not-allowed !important;
            pointer-events: none !important;
            filter: grayscale(40%) !important;
            box-shadow: none !important;
        }
    </style>
    <style>
        .wishlist-btn:hover i {
            color: white !important;
        }

        .is-invalid-addon {
            border: 2px solid #dc3545 !important;
            border-radius: 10px !important;
        }

        .btn:focus-visible {
            outline: none !important;
        }


        .wishlist-btn:hover {
            background: #b57a45 !important;
            color: white !important;
        }

        .wishlist-btn-wrapper {
            position: relative;
        }

        .wishlist-btn {
            position: relative;
            transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 0px 15px -3px rgba(0, 0, 0, 0.16);
        }

        .wishlist-heart-icon {
            position: relative;
            z-index: 2;
            top: 2px;
        }

        .wishlist-btn .wishlist-tooltip-custom {
            visibility: hidden;
            opacity: 0;
            position: absolute;
            left: -143px;
            top: 50%;
            transform: translateY(-50%);
            background: #f5ede7;
            color: #b57a45;
            border-radius: 10px;
            padding: 10px;
            font-size: 15px;
            font-weight: 600;
            white-space: nowrap;
            z-index: 200;
            transition: opacity 0.2s;
            pointer-events: none;
            border: 1px solid #b57a45;
        }

        .wishlist-btn .wishlist-tooltip-arrow {
            content: '';
            position: absolute;
            right: -8px;
            top: 50%;
            width: 0;
            height: 0;
            border-top: 8px solid transparent;
            border-bottom: 8px solid transparent;
            border-left: 8px solid #b57a45;
            transform: translateY(-50%);
        }

        .wishlist-btn:hover .wishlist-tooltip-custom,
        .wishlist-btn:focus .wishlist-tooltip-custom {
            visibility: visible;
            opacity: 1;
        }
    </style>
    <!-- Product Name -->
    <div class="flex-row d-flex align-items-center justify-content-between">
        <h2 class="mb-2 fs-26 fw-700 text-dark" style="letter-spacing:0.5px !important">
            {{ ucfirst($detailedProduct->getTranslation('name')) }}
        </h2>
        @if ($detailedProduct->auction_product != 1)
            @php
                $isInWishlist =
                    auth()->check() &&
                    \App\Models\Wishlist::where('user_id', auth()->id())
                        ->where('product_id', $detailedProduct->id)
                        ->exists();
            @endphp
            <div class="wishlist-btn-wrapper" style="display: flex; align-items: center;">
                <a href="javascript:void(0)" onclick="addToWishList({{ $detailedProduct->id }});"
                    class="wishlist-btn disabled-wishlist position-relative d-flex align-items-center justify-content-center"
                    style="background: white;border:1px solid #e6e6e6 !important; border-radius: 50%; height: 50px; width: 50px; color: black !important; border: none; margin-left: 16px; margin-right: 0;">
                    <i class="la la-heart{{ $isInWishlist ? '' : '-o' }} wishlist-heart-icon"
                        style="font-size: 24px; color: black"></i>
                    <span class="wishlist-tooltip-custom">
                        Add to wishlist
                        <span class="wishlist-tooltip-arrow"></span>
                    </span>
                </a>
            </div>
        @endif

    </div>

    <div class="mb-3 row align-items-center">
        <!-- Review -->

        <!-- Estimate Shipping Time -->
        @if ($detailedProduct->est_shipping_days)
            <div class="col-auto mt-1 fs-14">
                <small class="mr-1 opacity-50 fs-14">{{ translate('Estimate Shipping Time') }}:</small>
                <span class="fw-500">{{ $detailedProduct->est_shipping_days }} {{ translate('Days') }}</span>
            </div>
        @endif
        <!-- In stock -->
        @if ($detailedProduct->digital == 1)
            <div class="mt-1 col-12">
                <span class="badge badge-md badge-inline badge-pill badge-success">{{ translate('In stock') }}</span>
            </div>
        @endif
    </div>
    <div class="text-center row align-items-center d-flex justify-content-between">
        @if (get_setting('product_query_activation') == 1)
            <!-- Ask about this product -->
            <div class="mb-3 col-xl-5 col-lg-4 col-md-3 col-sm-4">
                <a href="javascript:void();" onclick="goToView('product_query')"
                    class="text-primary fs-14 fw-600 d-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 32 32">
                        <g id="Group_25571" data-name="Group 25571" transform="translate(-975 -411)">
                            <g id="Path_32843" data-name="Path 32843" transform="translate(975 411)" fill="#fff">
                                <path
                                    d="M 16 31 C 11.9933500289917 31 8.226519584655762 29.43972969055176 5.393400192260742 26.60659980773926 C 2.560270071029663 23.77347946166992 1 20.00665092468262 1 16 C 1 11.9933500289917 2.560270071029663 8.226519584655762 5.393400192260742 5.393400192260742 C 8.226519584655762 2.560270071029663 11.9933500289917 1 16 1 C 20.00665092468262 1 23.77347946166992 2.560270071029663 26.60659980773926 5.393400192260742 C 29.43972969055176 8.226519584655762 31 11.9933500289917 31 16 C 31 20.00665092468262 29.43972969055176 23.77347946166992 26.60659980773926 26.60659980773926 C 23.77347946166992 29.43972969055176 20.00665092468262 31 16 31 Z"
                                    stroke="none" />
                                <path
                                    d="M 16 2 C 12.26045989990234 2 8.744749069213867 3.456249237060547 6.100500106811523 6.100500106811523 C 3.456249237060547 8.744749069213867 2 12.26045989990234 2 16 C 2 19.73954010009766 3.456249237060547 23.2552490234375 6.100500106811523 25.89949989318848 C 8.744749069213867 28.54375076293945 12.26045989990234 30 16 30 C 19.73954010009766 30 23.2552490234375 28.54375076293945 25.89949989318848 25.89949989318848 C 28.54375076293945 23.2552490234375 30 19.73954010009766 30 16 C 30 12.26045989990234 28.54375076293945 8.744749069213867 25.89949989318848 6.100500106811523 C 23.2552490234375 3.456249237060547 19.73954010009766 2 16 2 M 16 0 C 24.8365592956543 0 32 7.163440704345703 32 16 C 32 24.8365592956543 24.8365592956543 32 16 32 C 7.163440704345703 32 0 24.8365592956543 0 16 C 0 7.163440704345703 7.163440704345703 0 16 0 Z"
                                    stroke="none" fill="#f3af3d" />
                            </g>
                            <path id="Path_32842" data-name="Path 32842"
                                d="M28.738,30.935a1.185,1.185,0,0,1-1.185-1.185,3.964,3.964,0,0,1,.942-2.613c.089-.095.213-.207.361-.344.735-.658,2.252-2.032,2.252-3.555a2.228,2.228,0,0,0-2.37-2.37,2.228,2.228,0,0,0-2.37,2.37,1.185,1.185,0,1,1-2.37,0,4.592,4.592,0,0,1,4.74-4.74,4.592,4.592,0,0,1,4.74,4.74c0,2.577-2.044,4.432-3.028,5.333l-.284.255a1.89,1.89,0,0,0-.243.948A1.185,1.185,0,0,1,28.738,30.935Zm0,3.561a1.185,1.185,0,0,1-.835-2.026,1.226,1.226,0,0,1,1.671,0,1.061,1.061,0,0,1,.148.184,1.345,1.345,0,0,1,.113.2,1.41,1.41,0,0,1,.065.225,1.138,1.138,0,0,1,0,.462,1.338,1.338,0,0,1-.065.219,1.185,1.185,0,0,1-.113.207,1.06,1.06,0,0,1-.148.184A1.185,1.185,0,0,1,28.738,34.5Z"
                                transform="translate(962.004 400.504)" fill="#f3af3d" />
                        </g>
                    </svg>

                    <span class="ml-2 text-primary animate-underline-blue">{{ translate('Product Inquiry') }}</span>
                </a>
            </div>
        @endif
        @if ($detailedProduct->auction_product != 1)
            <div class="mt-1 col-12 col-md-6 col-lg-6" style="text-align: right;">
                @php
                    $total = 0;
                    $total += $detailedProduct->reviews->count();
                @endphp
                <span class="rating rating-mr-0">
                    {{ renderStarRating($detailedProduct->rating) }}
                </span>
                <span class="ml-1 opacity-50 fs-14">({{ $total }}
                    {{ translate('reviews') }})</span>
            </div>
        @endif

    </div>

    @if ($detailedProduct->auction_product)
        <div class="mb-3 row no-gutters">
            <div class="col-sm-2">
                <div class="mt-1 text-secondary fs-14 fw-400">{{ translate('Auction Will End') }}</div>
            </div>
            <div class="col-sm-10">
                @if ($detailedProduct->auction_end_date > strtotime('now'))
                    <div class="aiz-count-down align-items-center"
                        data-date="{{ date('Y/m/d H:i:s', $detailedProduct->auction_end_date) }}"></div>
                @else
                    <p>{{ translate('Ended') }}</p>
                @endif

            </div>
        </div>

        @if (Auth::check() && Auth::user()->product_bids->where('product_id', $detailedProduct->id)->first() != null)
            <div class="mb-3 row no-gutters">
                <div class="col-sm-2">
                    <div class="mt-1 text-secondary fs-14 fw-400 sads">{{ translate('My Bidded Amount') }}</div>
                </div>
                <div class="col-sm-10">
                    <span class="opacity-50 fs-20">
                        {{ single_price(Auth::user()->product_bids->where('product_id', $detailedProduct->id)->first()->amount) }}
                    </span>
                </div>
            </div>
            <hr>
        @endif

        @php $highest_bid = $detailedProduct->bids->max('amount'); @endphp
        <div class="my-2 mb-3 row no-gutters">
            <div class="col-sm-2">
                <div class="mt-1 text-secondary fs-14 fw-400 asdcs">{{ translate('Highest Bid') }}</div>
            </div>
            <div class="col-sm-10">
                <strong class="h3 fw-600 text-primary">
                    @if ($highest_bid != null)
                        {{ single_price($highest_bid) }}
                    @endif
                </strong>
            </div>
        </div>
    @else
        <!-- Without auction product -->
        @if ($detailedProduct->wholesale_product == 1)
            <!-- Wholesale -->
            <table class="table mb-3">
                <thead>
                    <tr>
                        <th class="border-top-0">{{ translate('Min Qty') }}</th>
                        <th class="border-top-0">{{ translate('Max Qty') }}</th>
                        <th class="border-top-0">{{ translate('Unit Price') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detailedProduct->stocks->first()->wholesalePrices as $wholesalePrice)
                        <tr>
                            <td>{{ $wholesalePrice->min_qty }}</td>
                            <td>{{ $wholesalePrice->max_qty }}</td>
                            <td>{{ single_price($wholesalePrice->price) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <!-- Without Wholesale -->
            @if (home_price($detailedProduct) != home_discounted_price($detailedProduct))
                <div class="mb-3 row no-gutters">
                    <div class="col-sm-2">
                        <div class="mt-1 text-secondary fs-15 fw-500" style="color: #333 !important;">
                            {{ translate('Price') }}</div>
                    </div>
                    <div class="col-sm-10">
                        <div class="flex-wrap d-flex align-items-center yfgyhkj">
                            <!-- Discount Price -->
                            <strong class="fs-20 fw-600 text-primary">
                                {{ home_discounted_price($detailedProduct) }}
                            </strong>
                            <!-- Home Price -->
                            <del class="ml-2 fs-14 opacity-60" style="color:rgb(46, 46, 46);">
                                {{ home_price($detailedProduct) }}
                            </del>
                            @if (discount_in_percentage($detailedProduct) > 0)
                                <div class="px-2 py-1 ml-2"
                                    style="background: rgba(var(--primary-rgb), 0.1);
                                    border-radius: 6px;
                                    display: inline-flex;
                                    align-items: center;">
                                    <span class="fs-13 fw-700" style="color: var(--primary);">
                                        -{{ discount_in_percentage($detailedProduct) }}%
                                    </span>
                                </div>
                            @endif
                            <!-- Club Point -->
                            @if (addon_is_activated('club_point') && $detailedProduct->earn_point > 0)
                                <div class="px-3 py-1 ml-2 d-inline-flex align-items-center"
                                    style="background: #fff3e5;
                                    border-radius: 6px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                        viewBox="0 0 12 12">
                                        <g id="Group_23922" data-name="Group 23922" transform="translate(-973 -633)">
                                            <circle id="Ellipse_39" data-name="Ellipse 39" cx="6" cy="6"
                                                r="6" transform="translate(973 633)" fill="#f3af3d" />
                                            <g id="Group_23920" data-name="Group 23920"
                                                transform="translate(973 633)">
                                                <path id="Path_28698" data-name="Path 28698"
                                                    d="M7.667,3H4.333L3,5,6,9,9,5Z" transform="translate(0 0)"
                                                    fill="#fff" />
                                            </g>
                                        </g>
                                    </svg>
                                    <small class="ml-2 fs-11 fw-500" style="color: #f3af3d;">
                                        {{ translate('Club Point') }}: {{ $detailedProduct->earn_point }}
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="mb-3 row no-gutters">
                    
                    <div class="col-sm-10 col-10 ">
                        <div class="flex-wrap d-flex align-items-center" style="gap:15px;">
                            <div class="text-black fs-18 fw-600" style="color: #333 !important;">{{ translate('Price') }}
                        </div>
                            <div>
                            <!-- Regular Price (with Addon total UI dynamic addition) -->
                            <strong class="fs-18 fw-600 text-primary js-product-total-price"
                                data-default-price-text="{{ home_discounted_base_price($detailedProduct) }}">
                                {{ home_discounted_base_price($detailedProduct) }}
                            </strong>
                            @php
                                $actual_base_price = $detailedProduct->unit_price;
                                $discount_applicable = false;
                                if ($detailedProduct->discount_start_date == null) {
                                    $discount_applicable = true;
                                } elseif (
                                    strtotime(date('d-m-Y H:i:s')) >= $detailedProduct->discount_start_date &&
                                    strtotime(date('d-m-Y H:i:s')) <= $detailedProduct->discount_end_date
                                ) {
                                    $discount_applicable = true;
                                }
                                if ($discount_applicable) {
                                    if ($detailedProduct->discount_type == 'percent') {
                                        $actual_base_price -= ($actual_base_price * $detailedProduct->discount) / 100;
                                    } elseif ($detailedProduct->discount_type == 'amount') {
                                        $actual_base_price -= $detailedProduct->discount;
                                    }
                                }
                                $actual_base_price = max(0, $actual_base_price);
                            @endphp
                            <!-- Hidden span to store the base price -->
                            <span class="d-none js-product-base-price"
                                data-base-price="{{ home_discounted_base_price($detailedProduct, false) }}"
                                data-actual-base-price="{{ $actual_base_price }}">
                                {{ home_discounted_base_price($detailedProduct, false) }}
                            </span>
                            @if (addon_is_activated('club_point') && $detailedProduct->earn_point > 0)
                                <div class="px-3 py-1 ml-2 d-inline-flex align-items-center"
                                    style="background: #fff3e5;
                                    border-radius: 6px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                        viewBox="0 0 12 12">
                                        <g id="Group_23922" data-name="Group 23922" transform="translate(-973 -633)">
                                            <circle id="Ellipse_39" data-name="Ellipse 39" cx="6"
                                                cy="6" r="6" transform="translate(973 633)" fill="#f3af3d" />
                                            <g id="Group_23920" data-name="Group 23920"
                                                transform="translate(973 633)">
                                                <path id="Path_28698" data-name="Path 28698"
                                                    d="M7.667,3H4.333L3,5,6,9,9,5Z" transform="translate(0 0)"
                                                    fill="#fff" />
                                            </g>
                                        </g>
                                    </svg>
                                    <small class="ml-2 fs-11 fw-500" style="color: #f3af3d;">
                                        {{ translate('Club Point') }}: {{ $detailedProduct->earn_point }}
                                    </small>
                                </div>
                            @endif
                            </div>
                        </div>
                    </div>

                </div>
            @endif
        @endif
    @endif

    @include('frontend.product_details.product-options', [
        'detailedProduct' => $detailedProduct,
    ])

    @if ($detailedProduct->auction_product)
        @php
            $highest_bid = $detailedProduct->bids->max('amount');
            $min_bid_amount = $highest_bid != null ? $highest_bid + 1 : $detailedProduct->starting_bid;
        @endphp
        @if ($detailedProduct->auction_end_date >= strtotime('now'))
            <div class="mt-4">
                @if (Auth::check() && $detailedProduct->user_id == Auth::user()->id)
                    <span
                        class="badge badge-inline badge-danger">{{ translate('Seller cannot Place Bid to His Own Product') }}</span>
                @else
                    <button type="button" class="btn btn-primary buy-now fw-600 min-w-150px rounded-0"
                        onclick="bid_modal()">
                        <i class="las la-gavel"></i>
                        @if (Auth::check() && Auth::user()->product_bids->where('product_id', $detailedProduct->id)->first() != null)
                            {{ translate('Change Bid') }}
                        @else
                            {{ translate('Place Bid') }}
                        @endif
                    </button>
                @endif
            </div>
        @endif
    @else
        @if (isset($alreadyInCart) && $alreadyInCart && !isset($cartItem))
            <div class="p-3 mb-3 d-flex align-items-center" style="background-color: #fcf9f5; border: 1px solid #ebdcd0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                <div class="mr-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background-color: #f7ede4; border-radius: 50%; color: #cfa07c; flex-shrink: 0;">
                    <i class="las la-shopping-basket" style="font-size: 20px;"></i>
                </div>
                <div class="flex-grow-1">
                    <span class="d-block fs-14 fw-600 text-dark" style="color: #242121 !important;">{{ translate('This item is already in your basket') }}</span>
                    <span class="d-block fs-12 text-muted mt-0.5" style="color: #888 !important;">{{ translate('You can review your selections or make updates in your cart.') }}</span>
                </div>
                <div class="ml-3" style="flex-shrink: 0;">
                    <a href="{{ route('cart') }}" class="btn btn-sm fw-600" style="background-color: #242121; color: #ffffff !important; border-radius: 6px; padding: 6px 14px; font-size: 12px; transition: all 0.2s;">
                        {{ translate('Review Cart') }}
                    </a>
                </div>
            </div>
        @endif

        <!-- Add to cart & Buy now Buttons -->
        <div class="mt-3 product-action-buttons d-flex justify-content-between flex-row flex-wrap">
            @php
                // Calculate stock availability for physical products
                $in_stock = true;
                if ($detailedProduct->digital == 0) {
                    // If the product uses variants
                    if (isset($detailedProduct->stocks) && count($detailedProduct->stocks)) {
                        $in_stock = false;
                        foreach ($detailedProduct->stocks as $stock) {
                            if ($stock->qty > 0) {
                                $in_stock = true;
                                break;
                            }
                        }
                    } else {
                        // If no variants, use current_stock
                        $in_stock = $detailedProduct->current_stock > 0;
                    }
                }
            @endphp

            @if (isset($cartItem))
                <div class="mb-2 d-flex w-100">
                    <button type="button"
                        class="btn btn-primary buy-now btn-disabled-custom fw-600 add-to-cart w-100 transition-all duration-300"
                        disabled
                        @if (Auth::check()) onclick="validatedBuyNow()" @else onclick="showLoginModal()" @endif
                        style="background-color: #DBCABC; color: #242121; border: 1.5px solid #9b8d81; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); height: 50px;">
                        <i class="las la-edit"></i> {{ translate('Update Cart') }}
                    </button>
                </div>
            @else
                @if ($detailedProduct->digital == 0)
                    @if ($detailedProduct->external_link != null)
                        <div class="mb-2 d-flex w-100">
                            <a type="button" class="btn btn-primary buy-now fw-600 add-to-cart w-100 rounded-0"
                                href="{{ $detailedProduct->external_link }}">
                                <i class="la la-share"></i> {{ translate($detailedProduct->external_link_btn) }}
                            </a>
                        </div>
                    @else
                        @if ($in_stock)
                            <div class="mb-2 d-flex w-100">
                                <button type="button"
                                    class="btn add-to-cart btn-disabled-custom fw-600 w-100 transition-all duration-300"
                                    style="background: #fff; border: 1.5px solid #242121; color: #242121 !important; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); height: 50px;"
                                    disabled
                                    @if (Auth::check()) onclick="validatedAddToCart()" @else onclick="showLoginModal()" @endif>
                                    <i class="las la-shopping-bag"></i> {{ translate('Add to Basket') }}
                                </button>
                            </div>
                            <div class="mb-2 d-flex w-100">
                                <button type="button"
                                    class="btn btn-primary buy-now btn-disabled-custom fw-600 add-to-cart w-100 transition-all duration-300"
                                    disabled
                                    @if (Auth::check()) onclick="validatedBuyNow()" @else onclick="showLoginModal()" @endif
                                    style="background-color: #DBCABC; color: #242121; border: 1.5px solid #9b8d81; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); height: 50px;">
                                    <i class="la la-shopping-cart"></i> {{ translate('Buy Now') }}
                                </button>
                            </div>
                        @else
                            <div class="mb-2 d-flex w-100">
                                <button type="button"
                                    class="btn btn-secondary out-of-stock fw-600 w-100 transition-all duration-300"
                                    style="border-radius: 6px; height: 50px;" disabled>
                                    <i class="la la-cart-arrow-down"></i> {{ translate('Out of Stock') }}
                                </button>
                            </div>
                        @endif
                    @endif
                @elseif ($detailedProduct->digital == 1)
                    <div class="mb-2 d-flex w-100">
                        <button type="button"
                            class="btn btn-secondary-base add-to-cart btn-disabled-custom fw-600 w-100 transition-all duration-300"
                            style="border-radius: 6px; height: 50px;" disabled
                            @if (Auth::check()) onclick="addToCart()" @else onclick="showLoginModal()" @endif>
                            <i class="las la-shopping-bag"></i> {{ translate('Add to Basket') }}
                        </button>
                    </div>
                    <button type="button"
                        class="btn btn-primary buy-now btn-disabled-custom fw-600 add-to-cart w-100 transition-all duration-300"
                        style="border-radius: 6px; height: 50px;" disabled
                        @if (Auth::check()) onclick="buyNow()" @else onclick="showLoginModal()" @endif>
                        <i class="la la-shopping-cart"></i> {{ translate('Buy Now') }}
                    </button>
                @endif
            @endif
        </div>
</div>


<!-- Promote Link -->
<div class="mt-3 d-table width-100">
    <div class="d-table-cell">
        @if (Auth::check() &&
                addon_is_activated('affiliate_system') &&
                get_affliate_option_status() &&
                Auth::user()->affiliate_user != null &&
                Auth::user()->affiliate_user->status)
            @php
                if (Auth::check()) {
                    if (Auth::user()->referral_code == null) {
                        Auth::user()->referral_code = substr(Auth::user()->id . Str::random(10), 0, 10);
                        Auth::user()->save();
                    }
                    $referral_code = Auth::user()->referral_code;
                    $referral_code_url =
                        URL::to('/product') . '/' . $detailedProduct->slug . "?product_referral_code=$referral_code";
                }
            @endphp
            <div>
                <button type="button" id="ref-cpurl-btn" class="btn btn-secondary w-200px rounded-0"
                    data-attrcpy="{{ translate('Copied') }}" onclick="CopyToClipboard(this)"
                    data-url="{{ $referral_code_url }}">{{ translate('Copy the Promote Link') }}</button>
            </div>
        @endif
    </div>
</div>

<!-- Refund -->
@php
    $refund_sticker = get_setting('refund_sticker');
@endphp
@if (addon_is_activated('refund_request'))
    <div class="mt-3 row no-gutters">
        <div class="col-sm-2">
            <div class="mt-2 text-secondary fs-14 fw-400">{{ translate('Refund') }}</div>
        </div>
        <div class="col-sm-10">
            @if ($detailedProduct->refundable == 1)
                <a href="{{ route('returnpolicy') }}" target="_blank">
                    @if ($refund_sticker != null)
                        <img src="{{ uploaded_asset($refund_sticker) }}" height="36">
                    @else
                        <img src="{{ static_asset('assets/img/refund-sticker.jpg') }}" height="36">
                    @endif
                </a>
                <a href="{{ route('returnpolicy') }}" class="ml-3 text-blue hov-text-primary fs-14"
                    target="_blank">{{ translate('View Policy') }}</a>
            @else
                <div class="mt-2 text-dark fs-14 fw-400">{{ translate('Not Applicable') }}</div>
            @endif
        </div>
    </div>
@endif

<!-- Seller Guarantees -->
@if ($detailedProduct->digital == 1)
    @if ($detailedProduct->added_by == 'seller')
        <div class="mt-3 row no-gutters">
            <div class="col-2">
                <div class="text-secondary fs-14 fw-400">{{ translate('Seller Guarantees') }}</div>
            </div>
            <div class="col-10">
                @if ($detailedProduct->user->shop->verification_status == 1)
                    <span class="text-success fs-14 fw-700">{{ translate('Verified seller') }}</span>
                @else
                    <span class="text-danger fs-14 fw-700">{{ translate('Non verified seller') }}</span>
                @endif
            </div>
        </div>
    @endif
@endif
@endif

<!-- Share -->
<div class="mt-4 row no-gutters">
    <div class="col-sm-2">
        <div class="mt-2 text-secondary fs-14 fw-500" style="color:#333 !important">{{ translate('Share') }}
        </div>
    </div>
    <div class="col-sm-10">
        <div class="aiz-share"></div>
    </div>
</div>

</div>
<script>
    function change_qty() {
        var ths = $("#quantity");
        var cur_val = parseInt($(ths).val());
        var qty1 = parseInt($("#qty1").val());
        if (isNaN(cur_val)) {
            $(ths).val(1);
        } else if (cur_val > qty1) {
            $("#quantity").val(qty1);
        } else if (cur_val < 1) {
            $("#quantity").val(1);
        }
        // Recalculate price when quantity changes
        getVariantPrice();
    }
</script>

<script>
    document.querySelectorAll('.fabric-dropdown').forEach(dropdown => {
        dropdown.addEventListener('change', function() {
            let parent = this.closest('.addon-block');
            let filter = this.value.toLowerCase();
            parent.querySelectorAll('.fabric-item').forEach(item => {
                let name = item.dataset.name.toLowerCase();
                if (filter === "") {
                    item.style.display = 'none'; // hide all if nothing selected
                } else {
                    if (name.startsWith(filter)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                }
            });
        });
    });
    document.querySelectorAll('.option-input').forEach(input => {
        input.addEventListener('change', function() {
            document.querySelectorAll('.fabric-box').forEach(box => {
                box.classList.remove('active-preview');
            });
            this.nextElementSibling.classList.add('active-preview');
        });
    });
</script>
