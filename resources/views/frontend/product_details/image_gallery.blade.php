<style>
        div#imageGalleryCol {
        touch-action: auto !important;
    }
    @media only screen and (min-width: 992px) {
        .carousal_image_custom_height {
                height: 600px !important;
            }
    }
    @media only screen and (max-width: 991px) {
        .arrow-lg-none .slick-arrow {
            display: block !important;
        }

        .product-gallery,
        .product-gallery .carousel-box {
            height: 655px;
        }

        .product-gallery .slick-list,
        .product-gallery .slick-track,
        .product-gallery .slick-slide,
        .product-gallery .carousel-box {
            height: 100%;
        }

        .product-gallery .carousel-box {
            height: 309px !important;
            overflow: scroll !important;
        }

        .product-gallery img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* 🔥 THIS is key */
        }

    }
    .arrow-lg-none .slick-arrow {
            display: block !important;
        }
    @media only screen and (max-width: 767px) {
        .aiz-carousel .slick-next {
            right: 0 !important;
        }

        .aiz-carousel .slick-prev {
            left: 0 !important;
        }

        .aiz-carousel .slick-arrow {
            top: 50% !important;
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            font-size: 20px !important;
            color: #000 !important;
            cursor: pointer !important;
            z-index: 1000 !important;
            width: 40px !important;
            height: 40px !important;
        }
    }

    //shivani
    @media (max-width: 768px) {

        .product-gallery {
            overflow: scroll;
        }

        .aiz-carousel {
            width: 100% !important;
        }

        .carousel-box {
            width: 100% !important;
        }

        .carousel-box img {
            width: 100%;
            height: auto;
            object-fit: contain;
        }

        @media (max-width: 768px) {
            body {
                overflow-x: hidden;
            }
        }

        @media (max-width: 768px) {

            .product-gallery,
            .aiz-carousel,
            .carousel-box {
                height: auto !important;
                max-height: none !important;
            }
        }

        .carousel-box img {
            height: auto !important;
            width: 100%;
            object-fit: contain;
        }

        @media (max-width: 768px) {
            .col-12 {
                height: auto !important;
            }
        }

    }

    @media (max-width: 768px) {

        .product-gallery {
            height: 300px;
            /* 👈 yahan height control karo */

        }

        .aiz-carousel,
        .carousel-box {
            height: 100%;
        }

        .aiz-carousel .slick-next {
            top: 50% !important;
        }

        .aiz-carousel .slick-prev {
            top: 50% !important;
        }

        .carousel-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* 👈 fill karega nicely */
        }
    }
    .image_gallery_section_shadow{
    box-shadow: 0 0 16px 5px #7c7c7c1c;
    padding: 15px 10px !important;
    margin-bottom: 30px;
    margin-top: 30px;
    }
</style>
<div class="sticky-top z-3 row gutters-10">
    @php
        $photos = [];
    @endphp
    @if ($detailedProduct->photos != null)
        @php
            $photos = explode(',', $detailedProduct->photos);
        @endphp
    @endif
    <!-- Gallery Images -->
    <div class="col-12 position-relative" style="position: relative;">
        @if ($detailedProduct->auction_product != 1)
            @php
                $isInWishlist =
                    auth()->check() &&
                    \App\Models\Wishlist::where('user_id', auth()->id())
                        ->where('product_id', $detailedProduct->id)
                        ->exists();
            @endphp
            <div class="wishlist-btn-wrapper" style="position: absolute; top: 15px; right: 20px; z-index: 11;">
                <a href="javascript:void(0)" onclick="addToWishList({{ $detailedProduct->id }});"
                    class="wishlist-btn d-flex align-items-center justify-content-center"
                    style="background: white; border: 1px solid #e6e6e6 !important; border-radius: 50%; height: 48px; width: 48px; border: none; margin: 0; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12); transition: all 0.3s ease;">
                    <i class="la la-heart{{ $isInWishlist ? '' : '-o' }} wishlist-heart-icon"
                        style="font-size: 24px; color: #dc3545 !important;"></i>
                    <span class="wishlist-tooltip-custom">
                        Add to wishlist
                        <span class="wishlist-tooltip-arrow"></span>
                    </span>
                </a>
            </div>
        @endif

        <div class="aiz-carousel product-gallery arrow-inactive-transparent arrow-lg-none"
            data-nav-for='.product-gallery-thumb' data-fade='true' data-auto-height='false' data-arrows='true'>
            @if (empty($photos))
                <img src="{{ uploaded_asset($detailedProduct->thumbnail_img) }}" alt="Image" class=" img-fit"
                    height="700px" width="100%">
            @else
                @if ($detailedProduct->digital == 0)
                    @foreach ($detailedProduct->stocks as $key => $stock)
                        @if ($stock->image != null)
                            <div class="carousel-box img-zoom rounded-0">
                                <img class="img-fluid lazyload w-100 h-100 carousal_image_custom_height"
                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                    data-src="{{ uploaded_asset($stock->image) }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            </div>
                        @endif
                    @endforeach
                @endif

                @foreach ($photos as $key => $photo)
                    <div class="carousel-box img-zoom rounded-0 ">
                        <img class="img-fluid lazyload w-100 h-100 carousal_image_custom_height"
                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                            data-src="{{ uploaded_asset($photo) }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <!-- Thumbnail Images -->
    <div class="col-12 mt-3 d-none d-lg-block">
        <div class="aiz-carousel half-outside-arrow product-gallery-thumb" data-items='7'
            data-nav-for='.product-gallery' data-focus-select='true' data-arrows='true' data-vertical='false'
            data-auto-height='false'>

            @if ($detailedProduct->digital == 0)
                @foreach ($detailedProduct->stocks as $key => $stock)
                    @if ($stock->image != null)
                        <div class="carousel-box c-pointer rounded-0" data-variation="{{ $stock->variant }}">
                            <img class="lazyload mw-100 size-60px mx-auto border p-1"
                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                data-src="{{ uploaded_asset($stock->image) }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                        </div>
                    @endif
                @endforeach
            @endif

            @foreach ($photos as $key => $photo)
                <div class="carousel-box c-pointer rounded-0">
                    <img class="lazyload mw-100 size-60px mx-auto border p-1"
                        src="{{ static_asset('assets/img/placeholder.jpg') }}" data-src="{{ uploaded_asset($photo) }}"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                </div>
            @endforeach

        </div>
    </div>


</div>
