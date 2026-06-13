@extends('frontend.layouts.app')

@section('meta_title'){{ $detailedProduct->meta_title }}@stop

@section('meta_description'){{ $detailedProduct->meta_description }}@stop

@section('meta_keywords'){{ $detailedProduct->tags }}@stop

@section('meta')
    @php
        $availability = 'out of stock';
        $qty = 0;
        $merchantImageId = $detailedProduct->thumbnail_img;
        if (empty($merchantImageId) && !empty($detailedProduct->photos)) {
            $merchantImageId = collect(explode(',', $detailedProduct->photos))->filter()->first();
        }
        if (empty($merchantImageId)) {
            $merchantImageId = optional($detailedProduct->stocks->firstWhere('image', '!=', null))->image;
        }
        if (empty($merchantImageId)) {
            $merchantImageId = $detailedProduct->meta_img;
        }
        $merchantImage = uploaded_asset($merchantImageId);
        $merchantPrice = number_format((float) home_discounted_base_price($detailedProduct, false), 2, '.', '');
        $merchantCurrency = get_system_default_currency()->code;
        if ($detailedProduct->variant_product) {
            foreach ($detailedProduct->stocks as $key => $stock) {
                $qty += $stock->qty;
            }
        } else {
            $qty = optional($detailedProduct->stocks->first())->qty;
        }
        if ($qty > 0) {
            $availability = 'in stock';
        }
    @endphp
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $detailedProduct->meta_title }}">
    <meta itemprop="description" content="{{ $detailedProduct->meta_description }}">
    <meta itemprop="image" content="{{ $merchantImage }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $detailedProduct->meta_title }}">
    <meta name="twitter:description" content="{{ $detailedProduct->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ $merchantImage }}">
    <meta name="twitter:data1" content="{{ single_price($merchantPrice) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $detailedProduct->meta_title }}" />
    <meta property="og:type" content="og:product" />
    <meta property="og:url" content="{{ route('product', $detailedProduct->slug) }}" />
    <meta property="og:image" content="{{ $merchantImage }}" />
    <meta property="og:description" content="{{ $detailedProduct->meta_description }}" />
    <meta property="og:site_name" content="{{ get_setting('meta_title') }}" />
    <meta property="og:price:amount" content="{{ $merchantPrice }}" />
    <meta property="product:brand" content="{{ $detailedProduct->brand ? $detailedProduct->brand->name : env('APP_NAME') }}">
    <meta property="product:availability" content="{{ $availability }}">
    <meta property="product:condition" content="new">
    <meta property="product:price:amount" content="{{ $merchantPrice }}">
    <meta property="product:retailer_item_id" content="{{ $detailedProduct->slug }}">
    <meta property="product:price:currency"
        content="{{ $merchantCurrency }}" />
    <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $detailedProduct->meta_title ?: $detailedProduct->getTranslation('name'),
            'description' => $detailedProduct->meta_description,
            'image' => [$merchantImage],
            'sku' => $detailedProduct->slug,
            'brand' => [
                '@type' => 'Brand',
                'name' => $detailedProduct->brand ? $detailedProduct->brand->name : env('APP_NAME'),
            ],
            'offers' => [
                '@type' => 'Offer',
                'url' => route('product', $detailedProduct->slug),
                'priceCurrency' => $merchantCurrency,
                'price' => $merchantPrice,
                'availability' => $availability === 'in stock' ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'itemCondition' => 'https://schema.org/NewCondition',
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endsection

@section('content')
<style>
    /* ── Breadcrumb row: always single line on all screen sizes ── */
    .responsive-breadcrumb-row {
        flex-wrap: nowrap !important;
        align-items: center !important;
    }
    .responsive-breadcrumb-row nav {
        min-width: 0;
        flex: 1 1 0%;
        overflow: hidden;
    }
    /* Breadcrumb list stays on one line */
    .responsive-breadcrumb-row .breadcrumb {
        flex-wrap: nowrap !important;
        overflow: hidden;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    /* Each item shrinks but never hides Home/Category */
    .responsive-breadcrumb-row .breadcrumb-item {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        flex-shrink: 1;
    }
    .responsive-breadcrumb-row .breadcrumb-item:first-child,
    .responsive-breadcrumb-row .breadcrumb-item:nth-child(2) {
        flex-shrink: 0; /* Home & Category never shrink */
    }

    /* Category item style */
    .breadcrumb-category-item {
        flex-shrink: 0;
        white-space: nowrap;
    }

    /* Product name styling */
    .breadcrumb-product-item {
        min-width: 0;
        overflow: hidden;
        flex-shrink: 1;
    }
    .breadcrumb-product-name {
        display: inline-block;
        vertical-align: bottom;
        white-space: nowrap;
        max-width: none;
    }
    .breadcrumb-product-name.expanded {
        white-space: normal;
        overflow: visible;
        max-width: none;
    }

    /* Sleek Mobile Breadcrumb Tooltip */
    .breadcrumb-tooltip {
        display: none;
        position: absolute;
        top: 110%;
        left: 0;
        z-index: 1070;
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12), 0 1px 4px rgba(0, 0, 0, 0.05);
        padding: 12px 16px;
        width: 100%;
        max-width: 320px;
        border: 1px solid #eaeaea;
        animation: fadeIn 0.20s ease-out;
    }
    .breadcrumb-tooltip.active {
        display: block !important;
    }
    .breadcrumb-tooltip-item {
        font-size: 0.85rem;
        color: #212529;
        line-height: 1.4;
    }
    .breadcrumb-tooltip-label {
        font-weight: 700;
        color: #888;
        display: block;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }
    .breadcrumb-tooltip-value {
        color: #212529;
        word-break: break-word;
        font-weight: 500;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-4px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Seller button: never grows, never wraps */
    #viewSellerInfoMenuWrapper {
        flex-shrink: 0;
    }
    #viewSellerInfoBtn {
        white-space: nowrap;
    }

    /* Desktop view: ensure everything shows fully */
    @media (min-width: 768px) {
        .responsive-breadcrumb-row .breadcrumb-item {
            overflow: visible;
            text-overflow: clip;
        }
        .breadcrumb-category-item {
            max-width: none;
            overflow: visible;
            text-overflow: clip;
        }
        .breadcrumb-product-item {
            overflow: visible;
            text-overflow: clip;
        }
        .breadcrumb-product-name {
            max-width: none;
            overflow: visible;
            text-overflow: clip;
            white-space: normal;
        }
    }

    /* Mobile view specific rules */
    @media (max-width: 767.98px) {
        .breadcrumb-category-item {
            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .breadcrumb-category-item:hover {
            opacity: 0.7;
        }
        .breadcrumb-product-name {
            max-width: 140px;
            overflow: hidden;
            text-overflow: ellipsis; /* Native ellipsis, single set of three dots */
            cursor: pointer; /* Indication that it can be clicked/tapped */
            transition: opacity 0.2s;
        }
        .breadcrumb-product-name:hover {
            opacity: 0.7;
        }
    }

    @media (max-width: 400px) {
        #viewSellerInfoBtn {
            padding-left: 0.7rem !important;
            padding-right: 0.7rem !important;
            font-size: 0.95rem;
        }
        .breadcrumb-category-item {
            max-width: 80px;
        }
        .breadcrumb-product-name {
            max-width: 80px;
        }
    }
</style>

<style>
    #viewSellerInfoBtn{
        background: linear-gradient(90deg, #deb887 0%, #c59259 100%);
        color: #212529 !important;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        transition: box-shadow .2s, background .2s;
        padding: 6px 10px !important;

    }
    #viewSellerInfoBtn:hover{
        background: linear-gradient(90deg, #c59259 0%, #deb887 100%);
        color: #212529 !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.10);
    }
</style>
   <section class="pt-0 mb-4">
    <div class="container">
        <div class="py-0 bg-white">

            <div class="row">
                <div class="pt-5 pb-5 col-12 d-flex flex-wrap justify-content-between flex-column image_gallery_section_shadow">
                    <div class="d-flex flex-row align-items-center w-100 responsive-breadcrumb-row" style="gap: 0.75rem; position: relative;">
                        <nav aria-label="breadcrumb" class="flex-grow-1 min-width-0" style="overflow:hidden;">
                            @php
                                $breadcrumbCategory = $detailedProduct->main_category;
                                $productFullName    = $detailedProduct->getTranslation('name');
                            @endphp
                            <ol class="breadcrumb bg-white pl-0 p-0 m-0 justify-content-start mb-0" style="flex-wrap:nowrap;overflow:hidden;">
                                <li class="breadcrumb-item" style="flex-shrink:0;white-space:nowrap;">
                                    <a class="text-dark-50" href="{{ route('home') }}">
                                        <i class="las la-home"></i> {{ translate('Home') }}
                                    </a>
                                </li>
                                @if($breadcrumbCategory)
                                <li class="breadcrumb-item breadcrumb-category-item" id="breadcrumbCategoryItem">
                                    <a class="text-dark-50" href="{{ route('products.category', $breadcrumbCategory->slug) }}" title="{{ $breadcrumbCategory->getTranslation('name') }}">
                                        {{ $breadcrumbCategory->getTranslation('name') }}
                                    </a>
                                </li>
                                @endif
                                <li class="breadcrumb-item active text-primary fw-700 breadcrumb-product-item" aria-current="page">
                                    <span class="breadcrumb-product-name" id="breadcrumbProductName" title="{{ $productFullName }}">{{ $productFullName }}</span>
                                </li>
                            </ol>
                        </nav>

                        <!-- Sleek Breadcrumb Tooltip Popover for mobile view -->
                        <div id="breadcrumbTooltip" class="breadcrumb-tooltip shadow">
                            <div class="breadcrumb-tooltip-item">
                                <span class="breadcrumb-tooltip-label">{{ translate('Category') }}</span>
                                <span class="breadcrumb-tooltip-value">
                                    @if($breadcrumbCategory)
                                        <a href="{{ route('products.category', $breadcrumbCategory->slug) }}" class="text-primary font-weight-bold" style="text-decoration: underline;">
                                            {{ $breadcrumbCategory->getTranslation('name') }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                            <div class="breadcrumb-tooltip-item mt-3">
                                <span class="breadcrumb-tooltip-label">{{ translate('Product') }}</span>
                                <span class="breadcrumb-tooltip-value">{{ $productFullName }}</span>
                            </div>
                        </div>
                        <div class="position-relative" id="viewSellerInfoMenuWrapper" style="display:inline-block;">
                            <button type="button"
                                class="btn shadow-sm px-4 py-2 d-flex align-items-center ms-0 ms-md-3"
                                id="viewSellerInfoBtn"
                                title="{{ translate('View seller information') }}">
                                <span style="font-size:20px;">
                                    <i class="las la-info-circle"></i>
                                </span>
                                <!-- Mobile: Show "Seller Info" next to icon (larger button text) -->
                                <span class="d-inline-block d-sm-none ml-1 mobile_info_btn_text">{{ translate('Seller') }}</span>
                                <!-- Desktop: Show standard text -->
                                <span class="d-none d-sm-inline-block ml-1 desktop_info_btn_text">{{ translate('View Seller Info') }}</span>
                            </button>
                            <!-- Popover Seller Info Panel (hidden by default, toggled by JS, shown near button) -->
                            <div id="sellerInfoPopover" class="seller-info-popover shadow" style="display:none;">
                                <div class="seller-info-popover-content">
                                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                                        <h5 class="mb-0" style="font-weight:600;" id="sellerInfoPopoverLabel">Seller Information</h5>
                                        <i class="las la-times" style="font-size: 22px; cursor: pointer;" id="sellerInfoPopoverClose"></i>
                                    </div>
                                    <div>
                                        @include('frontend.product_details.seller_info')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="sellerInfoPopoverBackdrop" class="seller-info-popover-backdrop"></div>
                    </div>
                </div>
            </div>

            <style>
                /* Seller Info Popover Styles */
                .seller-info-popover {
                    position: absolute;
                    top: 110%;
                    right: 0;
                    z-index: 1081;
                    background: transparent;
                    pointer-events: none;
                }
                .seller-info-popover.active {
                    display: block !important;
                    pointer-events: auto;
                }
                .seller-info-popover-content {
                    background: #fff;
                    max-width: 430px;
                    min-width: 300px;
                    border-radius: 14px;
                    box-shadow: 0 14px 34px rgba(60,60,60,0.15), 0 1.5px 8px #eaeaea;
                    padding: 2.2rem 1.3rem 1.2rem 1.3rem;
                    min-height: 180px;
                }

                @media (max-width: 575px) {
                    .seller-info-popover-content {
                        width: 92vw;
                        max-width: 97vw;
                        min-width: 0;
                        left: 50%;
                        transform: translateX(0%) !important;
                        padding-left: 15px;
                        padding-right: 15px;
                    }
                }
                .seller-info-popover-backdrop {
                    display: none;
                    position: fixed;
                    left: 0; right: 0; top: 0; bottom: 0;
                    z-index: 1080;
                    background: rgba(33,33,41,0.12);
                    transition: opacity .25s;
                    opacity: 0;
                }
                .seller-info-popover-backdrop.active {
                    display: block;
                    opacity: 1;
                    pointer-events: auto;
                }
                body.seller-info-popover-open {
                    overflow: hidden;
                }
            </style>

            <script>
                document.addEventListener('DOMContentLoaded', function() {

                    /* ── Breadcrumb product name expand/collapse tooltip ── */
                    (function() {
                        var tooltip = document.getElementById('breadcrumbTooltip');
                        var categoryBtn = document.getElementById('breadcrumbCategoryItem');
                        var productBtn = document.getElementById('breadcrumbProductName');
                        var wrapper = document.querySelector('.responsive-breadcrumb-row');

                        if (!tooltip || !wrapper) return;

                        function toggleTooltip(e) {
                            if (window.innerWidth >= 768) return; // Only active on mobile
                            
                            e.preventDefault();
                            e.stopPropagation();
                            tooltip.classList.toggle('active');
                        }

                        if (categoryBtn) {
                            categoryBtn.addEventListener('click', toggleTooltip);
                        }
                        if (productBtn) {
                            productBtn.addEventListener('click', toggleTooltip);
                        }

                        // Close tooltip on click outside
                        document.addEventListener('click', function(e) {
                            if (tooltip.classList.contains('active')) {
                                if (!tooltip.contains(e.target) && !wrapper.contains(e.target)) {
                                    tooltip.classList.remove('active');
                                }
                            }
                        });

                        // Close tooltip on resize to desktop
                        window.addEventListener('resize', function() {
                            if (window.innerWidth >= 768) {
                                tooltip.classList.remove('active');
                            }
                        });
                    })();

                    const btn = document.getElementById('viewSellerInfoBtn');
                    const popover = document.getElementById('sellerInfoPopover');
                    const popoverClose = document.getElementById('sellerInfoPopoverClose');
                    const backdrop = document.getElementById('sellerInfoPopoverBackdrop');
                    const wrapper = document.getElementById('viewSellerInfoMenuWrapper');

                    function openPopover() {
                        popover.classList.add('active');
                        popover.style.display = 'block';
                        backdrop.classList.add('active');
                        document.body.classList.add('seller-info-popover-open');
                    }

                    function closePopover() {
                        popover.classList.remove('active');
                        popover.style.display = 'none';
                        backdrop.classList.remove('active');
                        document.body.classList.remove('seller-info-popover-open');
                    }

                    if (btn && popover && popoverClose && backdrop) {
                        btn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            // Position popover under button, prefer right-aligned
                            setTimeout(function() {
                                openPopover();
                                // Responsive position
                                if(window.innerWidth < 576) {
                                    const popoverContent = popover.querySelector('.seller-info-popover-content');
                                    popoverContent.style.left = '50%';
                                    popoverContent.style.transform = 'translateX(-50%)';
                                } else {
                                    const rect = btn.getBoundingClientRect();
                                    const popoverEl = popover;
                                    // Reset popover styling
                                    popoverEl.style.left = '';
                                    popoverEl.style.right = '0';
                                    popoverEl.style.top = '';
                                    // Handle viewport overflow (right)
                                    const popoverRect = popoverEl.getBoundingClientRect();
                                    let overflowRight = (rect.right + (popoverRect.width || 360)) - window.innerWidth;
                                    if (overflowRight > 0) {
                                        popoverEl.style.right = '0';
                                    } else {
                                        popoverEl.style.right = '0';
                                    }
                                    // Align with button bottom
                                    popoverEl.style.top = (btn.offsetHeight + 8) + "px";
                                }
                            }, 10);
                        });
                        popoverClose.addEventListener('click', closePopover);
                        backdrop.addEventListener('click', closePopover);

                        // ESC closes popover
                        document.addEventListener('keydown', function(e) {
                            if (popover.classList.contains('active') && e.key === "Escape") {
                                closePopover();
                            }
                        });

                        // Click outside closes popover
                        document.addEventListener('mousedown', function(e) {
                            if (popover.classList.contains('active')) {
                                // Check click outside popover content and button
                                if (
                                    !popover.contains(e.target) &&
                                    !btn.contains(e.target)
                                ) {
                                    closePopover();
                                }
                            }
                        });
                    }
                });
            </script>


            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="row product-main-row gutters-10 flex-column flex-lg-row" style="margin-bottom: 50px;">
                        <!-- Product Image Gallery -->
                        <div
                            class="mb-4 col-xl-6 col-lg-6 product-gallery-col sticky-gallery"
                            id="imageGalleryCol">
                            <div>
                                @include('frontend.product_details.image_gallery')
                            </div>
                        </div>

                        <!-- Product Details -->
                        <div
                            class="col-xl-6 col-lg-6 product-details-col scroll-details"
                            id="productDetailsCol">
                            <div>
                                @include('frontend.product_details.details')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <style>
                /* Premium Sticky Gallery & Natural Scrolling Details Layout */
                @media (min-width: 992px) {
                    .product-main-row {
                        min-height: unset !important;
                        max-height: unset !important;
                        display: flex !important;
                        flex-direction: row !important;
                        align-items: flex-start !important;
                    }
                    .product-gallery-col.sticky-gallery {
                        position: -webkit-sticky !important;
                        position: sticky !important;
                        top: 130px !important; /* Elegant offset for header navigation */
                        z-index: 10;
                        align-self: flex-start !important;
                        height: auto !important;
                    }
                    .product-details-col.scroll-details {
                        height: auto !important;
                        overflow-y: visible !important;
                        overflow-x: visible !important;
                    }
                }

                @media (max-width: 991.98px) {
                    .product-main-row {
                        flex-direction: column !important;
                        min-height: unset !important;
                        max-height: unset !important;
                    }
                    .product-gallery-col,
                    .product-details-col {
                        width: 100% !important;
                        max-width: 100% !important;
                        flex: unset !important;
                        height: auto !important;
                        min-height: unset !important;
                        max-height: unset !important;
                        overflow: visible !important;
                        position: static !important;
                    }
                }

                @media only screen and (max-width: 1500px) {
                    .aiz-carousel .slick-arrow {
                        top: 50% !important;
                        background: transparent !important;
                    }
                }
            </style>

            <div class="row">
                <div class="col-md-12">
                    @include('frontend.product_details.description')
                </div>
            </div>

        </div>
    </div>
</section>

    <section class="mb-4">
        <div class="container">
            @if (isset($detailedProduct) &&
                    property_exists($detailedProduct, 'auction_product') &&
                    $detailedProduct->auction_product)
                <!-- Reviews & Ratings -->
                {{-- @include('frontend.product_details.review_section') --}}

                <!-- Description, Video, Downloads -->
                {{-- @include('frontend.product_details.description') --}}

                <!-- Product Query -->
                @include('frontend.product_details.product_queries')
            @else
                <div class="row gutters-16">
                    <!-- Left side -->
                    {{-- <div class="col-lg-3">
                        <!-- Seller Info -->
                        @include('frontend.product_details.seller_info')

                        <!-- Top Selling Products -->
                       <div class="d-none d-lg-block">
                            @include('frontend.product_details.top_selling_products')
                       </div>
                    </div> --}}

                    <!-- Right side -->
                    <div class="col-lg-12">

                        <!-- Reviews & Ratings -->
                        {{-- @include('frontend.product_details.review_section') --}}

                        <!-- Description, Video, Downloads -->
                       {{-- @include('frontend.product_details.description') --}}

                        <!-- Related products -->
                        @include('frontend.product_details.related_products')

                        <!-- Product Query -->
                        @if (!empty(\Illuminate\Support\Facades\Auth::id()))
                            @include('frontend.product_details.product_queries')
                        @endif
                        <!-- Top Selling Products -->
                        <div class="d-lg-none">
                             @include('frontend.product_details.top_selling_products')
                        </div>

                    </div>
                </div>
            @endif
        </div>
    </section>

@endsection

@section('modal')
    <!-- Image Modal -->
    <div class="modal fade" id="image_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="p-4">
                    <div class="size-300px size-lg-450px">
                        <img class="img-fit h-100 lazyload"
                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                            data-src=""
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Modal -->
    <div class="modal fade" id="chat_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title fw-600 h5">{{ translate('Any query about this product') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="{{ route('conversations.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                    <div class="px-3 pt-3 modal-body gry-bg">
                        <div class="form-group">
                            <input type="text" class="mb-3 form-control rounded-0" name="title"
                                value="{{ $detailedProduct->name }}" placeholder="{{ translate('Product Name') }}"
                                required>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control rounded-0" rows="8" name="message" required
                                placeholder="{{ translate('Your Question') }}">{{ route('product', $detailedProduct->slug) }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary fw-600 rounded-0"
                            data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary fw-600 rounded-0 w-100px">{{ translate('Send') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bid Modal -->
    @if ($detailedProduct->auction_product == 1)
        @php
            $highest_bid = $detailedProduct->bids->max('amount');
            $min_bid_amount = $highest_bid != null ? $highest_bid+1 : $detailedProduct->starting_bid;
        @endphp
        <div class="modal fade" id="bid_for_detail_product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ translate('Bid For Product') }} <small>({{ translate('Min Bid Amount: ') . $min_bid_amount }})</small> </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" action="{{ route('auction_product_bids.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                            <div class="form-group">
                                <label class="form-label">
                                    {{ translate('Place Bid Price') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="form-group">
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="amount" min="{{ $min_bid_amount }}" placeholder="{{ translate('Enter Amount') }}" required>
                                </div>
                            </div>
                            <div class="text-right form-group">
                                <button type="submit" class="mr-1 btn btn-sm btn-primary transition-3d-hover">{{ translate('Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Product Review Modal -->
    <div class="modal fade" id="product-review-modal">
        <div class="modal-dialog">
            <div class="modal-content" id="product-review-modal-content">

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            getVariantPrice();
        });

        function CopyToClipboard(e) {
            var url = $(e).data('url');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(url).select();
            try {
                document.execCommand("copy");
                AIZ.plugins.notify('success', '{{ translate('Link copied to clipboard') }}');
            } catch (err) {
                AIZ.plugins.notify('danger', '{{ translate('Oops, unable to copy') }}');
            }
            $temp.remove();
            // if (document.selection) {
            //     var range = document.body.createTextRange();
            //     range.moveToElementText(document.getElementById(containerid));
            //     range.select().createTextRange();
            //     document.execCommand("Copy");

            // } else if (window.getSelection) {
            //     var range = document.createRange();
            //     document.getElementById(containerid).style.display = "block";
            //     range.selectNode(document.getElementById(containerid));
            //     window.getSelection().addRange(range);
            //     document.execCommand("Copy");
            //     document.getElementById(containerid).style.display = "none";

            // }
            // AIZ.plugins.notify('success', 'Copied');
        }

        function show_chat_modal() {
            @if (Auth::check())
                $('#chat_modal').modal('show');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        // Pagination using ajax
        $(window).on('hashchange', function() {
            if(window.history.pushState) {
                window.history.pushState('', '/', window.location.pathname);
            } else {
                window.location.hash = '';
            }
        });

        $(document).ready(function() {
            $(document).on('click', '.product-queries-pagination .pagination a', function(e) {
                getPaginateData($(this).attr('href').split('page=')[1], 'query', 'queries-area');
                e.preventDefault();
            });
        });

        $(document).ready(function() {
            $(document).on('click', '.product-reviews-pagination .pagination a', function(e) {
                getPaginateData($(this).attr('href').split('page=')[1], 'review', 'reviews-area');
                e.preventDefault();
            });
        });

        function getPaginateData(page, type, section) {
            $.ajax({
                url: '?page=' + page,
                dataType: 'json',
                data: {type: type},
            }).done(function(data) {
                $('.'+section).html(data);
                location.hash = page;
            }).fail(function() {
                alert('Something went worng! Data could not be loaded.');
            });
        }
        // Pagination end

        function showImage(photo) {
            $('#image_modal img').attr('src', photo);
            $('#image_modal img').attr('data-src', photo);
            $('#image_modal').modal('show');
        }

        function bid_modal(){
            @if (isCustomer() || isSeller())
                $('#bid_for_detail_product').modal('show');
          	@elseif (isAdmin())
                AIZ.plugins.notify('warning', '{{ translate('Sorry, Only customers & Sellers can Bid.') }}');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        function product_review(product_id) {
            @if (isCustomer())
                @if ($review_status == 1)
                    $.post('{{ route('product_review_modal') }}', {
                        _token: '{{ @csrf_token() }}',
                        product_id: product_id
                    }, function(data) {
                        $('#product-review-modal-content').html(data);
                        $('#product-review-modal').modal('show', {
                            backdrop: 'static'
                        });
                        AIZ.extra.inputRating();
                    });
                @else
                    AIZ.plugins.notify('warning', '{{ translate('Sorry, You need to buy this product to give review.') }}');
                @endif
            @elseif (Auth::check() && !isCustomer())
                AIZ.plugins.notify('warning', '{{ translate('Sorry, Only customers can give review.') }}');
            @else
                $('#login_modal').modal('show'); @endif
                                                                                                                        }
                                                                                                                    </script>
@endsection
