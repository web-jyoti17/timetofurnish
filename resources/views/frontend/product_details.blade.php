@extends('frontend.layouts.app')

@section('meta_title'){{ $detailedProduct->meta_title }}@stop

@section('meta_description'){{ $detailedProduct->meta_description }}@stop

@section('meta_keywords'){{ $detailedProduct->tags }}@stop

@section('meta')
    @php
        $availability = "out of stock";
        $qty = 0;
        if($detailedProduct->variant_product) {
            foreach ($detailedProduct->stocks as $key => $stock) {
                $qty += $stock->qty;
            }
        }
        else {
            $qty = optional($detailedProduct->stocks->first())->qty;
        }
        if($qty > 0){
            $availability = "in stock";
        }
    @endphp
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $detailedProduct->meta_title }}">
    <meta itemprop="description" content="{{ $detailedProduct->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $detailedProduct->meta_title }}">
    <meta name="twitter:description" content="{{ $detailedProduct->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">
    <meta name="twitter:data1" content="{{ single_price($detailedProduct->unit_price) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $detailedProduct->meta_title }}" />
    <meta property="og:type" content="og:product" />
    <meta property="og:url" content="{{ route('product', $detailedProduct->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}" />
    <meta property="og:description" content="{{ $detailedProduct->meta_description }}" />
    <meta property="og:site_name" content="{{ get_setting('meta_title') }}" />
    <meta property="og:price:amount" content="{{ single_price($detailedProduct->unit_price) }}" />
    <meta property="product:brand" content="{{ $detailedProduct->brand ? $detailedProduct->brand->name : env('APP_NAME') }}">
    <meta property="product:availability" content="{{ $availability }}">
    <meta property="product:condition" content="new">
    <meta property="product:price:amount" content="{{ number_format($detailedProduct->unit_price, 2) }}">
    <meta property="product:retailer_item_id" content="{{ $detailedProduct->slug }}">
    <meta property="product:price:currency"
        content="{{ get_system_default_currency()->code }}" />
    <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
@endsection

@section('content')
   <section class="pt-0 mb-4">
    <div class="container">
        <div class="py-0 bg-white">

            <div class="row">
                <div class="pt-5 pb-5 col-12 d-flex flex-wrap justify-content-between flex-row image_gallery_section_shadow">
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb" class="flex-grow-1">
                            <ol class="breadcrumb bg-white pl-0 m-0 justify-content-start">
                                <li class="breadcrumb-item">
                                    <a class="text-dark-50" href="{{ route('home') }}">
                                        <i class="las la-home"></i> {{ translate('Home') }}
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a class="text-dark-50" href="{{ url()->current() }}">
                                        {{ translate('Product') }}
                                    </a>
                                </li>
                                <li class="breadcrumb-item active text-primary fw-700" aria-current="page" style="max-width:220px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $detailedProduct->getTranslation('name') }}
                                </li>
                            </ol>
                        </nav>
                    </div>

                    <div class=" d-flex justify-content-lg-end align-items-center mt-0 mt-lg-0">
                        <button type="button"
                            class="btn shadow-sm px-4 py-2 d-flex align-items-center"
                            id="viewSellerInfoBtn"
                            title="{{ translate('View seller information') }}">
                            <span style="font-size:20px;">
                                <i class="las la-info-circle"></i>

                            </span>
                            <span class="d-inline-block ml-1">{{ translate('View Seller Info') }}</span>
                        </button>
                        <style>
                            #viewSellerInfoBtn{
                                background: linear-gradient(90deg, #deb887 0%, #c59259 100%);
                                color: #212529 !important;
                                border: none;
                                border-radius: 25px;
                                font-weight: 600;
                                box-shadow: 0 2px 6px rgba(0,0,0,0.08);
                                transition: box-shadow .2s, background .2s;
                            }
                            #viewSellerInfoBtn:hover{
                                background: linear-gradient(90deg, #c59259 0%, #deb887 100%);
                                color: #212529 !important;
                                box-shadow: 0 4px 12px rgba(0,0,0,0.10);
                            }
                        </style>
                    </div>

                    <!-- Seller Info Modal (initially hidden, JS will toggle) -->
                    <div class="modal fade" id="sellerInfoModal" tabindex="-1" aria-labelledby="sellerInfoModalLabel" aria-hidden="true" style="display:none;">
                        <div class="modal-dialog modal-dialog-centered" style="max-width: 1000px;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="sellerInfoModalLabel">Seller Information</h5>
                                    <i class="las la-times" style="font-size: 20px; cursor: pointer;"  id="sellerInfoModalClose"></i>
                                </div>
                                <div class="modal-body">
                                    @include('frontend.product_details.seller_info')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const sellerInfoBtn = document.getElementById('viewSellerInfoBtn');
                    const sellerInfoModal = document.getElementById('sellerInfoModal');
                    const sellerInfoModalClose = document.getElementById('sellerInfoModalClose');

                    if (sellerInfoBtn && sellerInfoModal && sellerInfoModalClose) {
                        function showModal() {
                            sellerInfoModal.classList.add('show');
                            sellerInfoModal.style.display = 'block';
                            sellerInfoModal.removeAttribute('aria-hidden');
                            document.body.classList.add('modal-open');

                            // Check if backdrop already exists to avoid duplicating
                            if (!document.getElementById('sellerInfoModalBackdrop')) {
                                let backdrop = document.createElement('div');
                                backdrop.className = 'modal-backdrop fade show';
                                backdrop.id = 'sellerInfoModalBackdrop';
                                document.body.appendChild(backdrop);
                            }
                        }

                        function hideModal() {
                            sellerInfoModal.classList.remove('show');
                            sellerInfoModal.style.display = 'none';
                            sellerInfoModal.setAttribute('aria-hidden', 'true');
                            document.body.classList.remove('modal-open');
                            // Remove backdrop
                            const backdrop = document.getElementById('sellerInfoModalBackdrop');
                            if (backdrop) document.body.removeChild(backdrop);
                        }

                        sellerInfoBtn.addEventListener('click', showModal);
                        sellerInfoModalClose.addEventListener('click', hideModal);

                        // Click outside modal-content to close
                        sellerInfoModal.addEventListener('mousedown', function(e) {
                            if (e.target === sellerInfoModal) {
                                hideModal();
                            }
                        });

                        // ESC key closes modal
                        document.addEventListener('keydown', function(e) {
                            if (sellerInfoModal.classList.contains('show') && e.key === "Escape") {
                                hideModal();
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
            @if (isset($detailedProduct) && property_exists($detailedProduct, 'auction_product') && $detailedProduct->auction_product)
                <!-- Reviews & Ratings -->
                {{-- @include('frontend.product_details.review_section') --}}

                <!-- Description, Video, Downloads -->
                {{--@include('frontend.product_details.description')--}}

                <!-- Product Query -->
                @include('frontend.product_details.product_queries')
            @else
                <div class="row gutters-16">
                    <!-- Left side -->
                    {{--<div class="col-lg-3">
                        <!-- Seller Info -->
                        @include('frontend.product_details.seller_info')

                        <!-- Top Selling Products -->
                       <div class="d-none d-lg-block">
                            @include('frontend.product_details.top_selling_products')
                       </div>
                    </div>--}}

                    <!-- Right side -->
                    <div class="col-lg-12">

                        <!-- Reviews & Ratings -->
                        {{-- @include('frontend.product_details.review_section') --}}

                        <!-- Description, Video, Downloads -->
                       {{-- @include('frontend.product_details.description')--}}

                        <!-- Related products -->
                        @include('frontend.product_details.related_products')

                        <!-- Product Query -->
                        @if(!empty(\Illuminate\Support\Facades\Auth::id()))
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
    @if($detailedProduct->auction_product == 1)
        @php
            $highest_bid = $detailedProduct->bids->max('amount');
            $min_bid_amount = $highest_bid != null ? $highest_bid+1 : $detailedProduct->starting_bid;
        @endphp
        <div class="modal fade" id="bid_for_detail_product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ translate('Bid For Product') }} <small>({{ translate('Min Bid Amount: ').$min_bid_amount }})</small> </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" action="{{ route('auction_product_bids.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                            <div class="form-group">
                                <label class="form-label">
                                    {{translate('Place Bid Price')}}
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
                AIZ.plugins.notify('warning', '{{ translate("Sorry, Only customers & Sellers can Bid.") }}');
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
                    AIZ.plugins.notify('warning', '{{ translate("Sorry, You need to buy this product to give review.") }}');
                @endif
            @elseif (Auth::check() && !isCustomer())
                AIZ.plugins.notify('warning', '{{ translate("Sorry, Only customers can give review.") }}');
            @else
                $('#login_modal').modal('show');
            @endif
        }
    </script>
@endsection
