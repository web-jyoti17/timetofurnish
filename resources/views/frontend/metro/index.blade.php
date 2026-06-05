@extends('frontend.layouts.app')

@section('content')
    <style>
        #section_featured .slick-slider .slick-list {
            /* background: ; */
            /* custom change */
        }

        #flash_deal .slick-slider .slick-list .slick-slide,
        #section_featured .slick-slider .slick-list .slick-slide {
            margin-bottom: 0px;
        }

        .home_slider_img {
            /* max-height: 500px; */
            background-position: center center;
        }

        .home_slider_img img {
            background-position: center center;
        }

        @media (max-width: 991px) {
            #flash_deal .slick-slider .slick-list .slick-slide {
                margin-bottom: 0px;
            }
        }

        @media (max-width: 575px) {
            #section_featured .slick-slider .slick-list .slick-slide {
                margin-bottom: 0px;
            }
        }
    </style>


    <div class="home-banner-area">
        <div class="p-0">
            <!-- Sliders -->
            <div class="home-slider slider-full">
                {{-- <div class="flex-grow-1  banner_search d-flex align-items-center mx-xl-5">
                <div class="position-relative home-banner_content flex-grow-1 px-3 px-lg-0">
                    <h4>Expect The Unexpected</h4>
                    <form action="{{ route('search') }}" method="GET" class="stop-propagation">
                        <div class="justify-content-center d-flex position-relative align-items-center">
                            <div class="d-lg-none" data-toggle="class-toggle"
                                data-target=".front-header-search">
                                <button class="btn px-2" type="button"><i
                                        class="la la-2x la-long-arrow-left"></i></button>
                            </div>
                            <div class="search-input-box">
                                <input type="text"
                                    class="border border-soft-light form-control fs-14 hov-animate-outline"
                                    id="search" name="keyword"
                                    @isset($query)
                                    value="{{ $query }}"
                                @endisset
                                    placeholder="{{ translate('I am shopping for...') }}" autocomplete="off">

                                <svg id="Group_723" data-name="Group 723" xmlns="http://www.w3.org/2000/svg"
                                    width="20.001" height="20" viewBox="0 0 20.001 20">
                                    <path id="Path_3090" data-name="Path 3090"
                                        d="M9.847,17.839a7.993,7.993,0,1,1,7.993-7.993A8,8,0,0,1,9.847,17.839Zm0-14.387a6.394,6.394,0,1,0,6.394,6.394A6.4,6.4,0,0,0,9.847,3.453Z"
                                        transform="translate(-1.854 -1.854)" fill="#b5b5bf" />
                                    <path id="Path_3091" data-name="Path 3091"
                                        d="M24.4,25.2a.8.8,0,0,1-.565-.234l-6.15-6.15a.8.8,0,0,1,1.13-1.13l6.15,6.15A.8.8,0,0,1,24.4,25.2Z"
                                        transform="translate(-5.2 -5.2)" fill="#b5b5bf" />
                                </svg>
                            </div>
                        </div>
                    </form>
                    <div class="typed-search-box stop-propagation document-click-d-none d-none bg-white rounded shadow-lg position-absolute left-0 top-100 w-100"
                        style="min-height: 200px">
                        <div class="search-preloader absolute-top-center">
                            <div class="dot-loader">
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                        </div>
                        <div class="search-nothing d-none p-3 text-center fs-16">

                        </div>
                        <div id="search-content" class="text-left">

                        </div>
                    </div>
                </div>
            </div> --}}
                {{-- @if (count($featured_categories) > 0)
            <div class="banner-category">
                <ul style="position: relative;">
                    @foreach ($featured_categories as $key => $category)
                    @if ($key < 5)
                    @php
                    $category_name = $category->getTranslation('name');
                    @endphp
                    <li>
                        <a href="{{ route('products.category', $category->slug) }}">
                            <img src="{{ isset($category->coverImage->file_name) ? my_asset($category->coverImage->file_name) : static_asset('assets/img/placeholder.jpg') }}" alt="{{$category_name}}" class="img-fluid">
                        </a>
                        <a href="{{ route('products.category', $category->slug) }}" class="category_a">
                            <span>{{$category_name}}</span>
                        </a>
                    </li>
                    @endif
                    @endforeach
                </ul>
            </div>
            @endif --}}
                @if (get_setting('home_slider_images') != null)
                    <div class="aiz-carousel home_banner_img dots-inside-bottom mobile-img-auto-height" data-dots="true"
                        data-autoplay="false" data-infinite="true">
                        @php
                            $decoded_slider_images = json_decode(get_setting('home_slider_images'), true);
                            $sliders = get_slider_images($decoded_slider_images);
                        @endphp
                        @foreach ($sliders as $key => $slider)
                            <div class="carousel-box">
                                <a href="{{ json_decode(get_setting('home_slider_links'), true)[$key] }}">
                                    <!-- Image -->
                                    <div class="d-block mw-100 img-fit overflow-hidden home_slider_img overflow-hidden">
                                        <img class="img-fit h-100 m-auto has-transition ls-is-cached lazyloaded"
                                            src="{{ $slider ? my_asset($slider->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                            alt="{{ env('APP_NAME') }} promo"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Dynamic Offers & Hot Deals Section -->
    @include('frontend.partials.homepage_offers')

    <!-- <div class="slider-container">
                                                <div class="slider-control left inactive"></div>
                                                <div class="slider-control right"></div>
                                                <ul class="slider-pagi"></ul>
                                                <div class="slider">
                                                    <div class="slide slide-0 active">
                                                        <div class="slide__bg"></div>
                                                        <div class="slide__content">
                                                            <svg class="slide__overlay" viewBox="0 0 720 405" preserveAspectRatio="xMaxYMax slice">
                                                                <path class="slide__overlay-path" d="M0,0 150,0 500,405 0,405" />
                                                            </svg>
                                                            <div class="slide__text">
                                                                <h5 class="slide__text-heading">Pi Kart</h5>
                                                                <p class="slide__text-desc">Radiant Confidence with Our. Exclusive Beauty Collection</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="slide slide-1 ">
                                                        <div class="slide__bg"></div>
                                                        <div class="slide__content">
                                                            <svg class="slide__overlay" viewBox="0 0 720 405" preserveAspectRatio="xMaxYMax slice">
                                                                <path class="slide__overlay-path" d="M0,0 150,0 500,405 0,405" />
                                                            </svg>
                                                            <div class="slide__text">
                                                                <h5 class="slide__text-heading">Pi Kart</h5>
                                                                <p class="slide__text-desc">Unveil Your Glow with. Our Exclusive Collection</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->

    <!-- Flash Deal -->
    <!-- <section class="mb-2 mt-5">
                                                <div class="container">
                                                    Categories
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                        @if (count($featured_categories) > 0)
    <div class="banner-category">
                                                            <ul>
                                                                @foreach ($featured_categories as $key => $category)
    @if ($key < 8)
    @php
        $category_name = $category->getTranslation('name');
    @endphp
                                                                <li style="position: relative;">
                                                                    <a href="{{ route('products.category', $category->slug) }}">
                                                                        <img src="{{ isset($category->coverImage->file_name) ? my_asset($category->coverImage->file_name) : static_asset('assets/img/placeholder.jpg') }}" alt="{{ $category_name }}" class="img-fluid">
                                                                    </a>
                                                                    <a href="{{ route('products.category', $category->slug) }}" class="">
                                                                        <span>{{ $category_name }}</span>
                                                                    </a>
                                                                </li>
    @endif
    @endforeach
                                                            </ul>
                                                        </div>
    @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </section> -->


    @php
        $flash_deal = get_featured_flash_deal();
        $flash_deal_bg = get_setting('flash_deal_bg_color');
        $flash_deal_bg_full_width = get_setting('flash_deal_bg_full_width') == 1 ? true : false;
        $flash_deal_banner_menu_text =
            get_setting('flash_deal_banner_menu_text') == 'dark' || get_setting('flash_deal_banner_menu_text') == null
                ? 'text-dark'
                : 'text-white';
    @endphp
    @if ($flash_deal != null)
        <section class="mb-2 mb-md-3 mt-2 mt-md-3"
            style="background: {{ $flash_deal_bg_full_width && $flash_deal_bg != null ? $flash_deal_bg : '' }};"
            id="flash_deal">
            <div class="container">
                <!-- Top Section sm to lg -->
                <div
                    class="d-flex d-lg-none flex-wrap mb-2 mb-md-3 @if ($flash_deal_bg_full_width && $flash_deal_bg != null) pt-2 pt-md-3 @endif align-items-baseline justify-content-between">
                    <!-- Title -->
                    <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                        <span
                            class="d-inline-block {{ $flash_deal_banner_menu_text }}">{{ translate('Flash Sale') }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="24" viewBox="0 0 16 24"
                            class="ml-3">
                            <path id="Path_28795" data-name="Path 28795"
                                d="M30.953,13.695a.474.474,0,0,0-.424-.25h-4.9l3.917-7.81a.423.423,0,0,0-.028-.428.477.477,0,0,0-.4-.207H21.588a.473.473,0,0,0-.429.263L15.041,18.151a.423.423,0,0,0,.034.423.478.478,0,0,0,.4.2h4.593l-2.229,9.683a.438.438,0,0,0,.259.5.489.489,0,0,0,.571-.127L30.9,14.164a.425.425,0,0,0,.054-.469Z"
                                transform="translate(-15 -5)" fill="#fcc201" />
                        </svg>
                    </h3>
                    <!-- Links -->
                    <div>
                        <div class="text-dark d-flex align-items-center mb-0">
                            <a href="{{ route('flash-deals') }}"
                                class="fs-10 fs-md-12 fw-700 has-transition {{ $flash_deal_banner_menu_text }} @if (get_setting('flash_deal_banner_menu_text') == 'light') text-white opacity-60 hov-opacity-100 animate-underline-white @else text-reset opacity-60 hov-opacity-100 hov-text-primary animate-underline-primary @endif mr-3">{{ translate('View All Flash Sale') }}</a>
                            <span class=" border-left border-soft-light border-width-2 pl-3">
                                <a href="{{ route('flash-deal-details', $flash_deal->slug) }}"
                                    class="fs-10 fs-md-12 fw-700 has-transition {{ $flash_deal_banner_menu_text }} @if (get_setting('flash_deal_banner_menu_text') == 'light') text-white opacity-60 hov-opacity-100 animate-underline-white @else text-reset opacity-60 hov-opacity-100 hov-text-primary animate-underline-primary @endif">{{ translate('View All Products from This Flash Sale') }}</a>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Countdown for small device -->
                <div class="bg-white mb-3 d-md-none">
                    <div class="aiz-count-down-circle" end-date="{{ date('Y/m/d H:i:s', $flash_deal->end_date) }}"></div>
                </div>

                <div class="row no-gutters align-items-center" style="background: {{ $flash_deal_bg }};">
                    <!-- Flash Deals Baner & Countdown -->
                    <div class="col-xxl-4 col-lg-5 col-6 h-200px h-md-400px h-lg-475px">
                        <div class="h-100 w-100 w-xl-auto"
                            style="background-image: url('{{ uploaded_asset($flash_deal->banner) }}'); background-size: cover; background-position: center center;">
                            <div class="py-5 px-md-3 px-xl-5 d-none d-md-block">
                                <div class="bg-white">
                                    <div class="aiz-count-down-circle"
                                        end-date="{{ date('Y/m/d H:i:s', $flash_deal->end_date) }}"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-8 col-lg-7 col-6">
                        <div class="pl-3 pr-lg-3 pl-xl-2rem pr-xl-2rem">
                            <!-- Top Section from lg device -->
                            <div
                                class="d-none d-lg-flex flex-wrap mb-2 mb-md-3 align-items-baseline justify-content-between">
                                <!-- Title -->
                                <h3 class="fs-16 fs-md-20 fw-700 mb-2">
                                    <span
                                        class="d-inline-block {{ $flash_deal_banner_menu_text }}">{{ translate('Flash Sale') }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="24"
                                        viewBox="0 0 16 24" class="ml-3">
                                        <path id="Path_28795" data-name="Path 28795"
                                            d="M30.953,13.695a.474.474,0,0,0-.424-.25h-4.9l3.917-7.81a.423.423,0,0,0-.028-.428.477.477,0,0,0-.4-.207H21.588a.473.473,0,0,0-.429.263L15.041,18.151a.423.423,0,0,0,.034.423.478.478,0,0,0,.4.2h4.593l-2.229,9.683a.438.438,0,0,0,.259.5.489.489,0,0,0,.571-.127L30.9,14.164a.425.425,0,0,0,.054-.469Z"
                                            transform="translate(-15 -5)" fill="#fcc201" />
                                    </svg>
                                </h3>
                                <!-- Links -->
                                <div>
                                    <div class="text-dark d-flex align-items-center mb-0">
                                        <a href="{{ route('flash-deals') }}"
                                            class="fs-10 fs-md-12 fw-700 has-transition {{ $flash_deal_banner_menu_text }} @if (get_setting('flash_deal_banner_menu_text') == 'light') text-white opacity-60 hov-opacity-100 animate-underline-white @else text-reset opacity-60 hov-opacity-100 hov-text-primary animate-underline-primary @endif mr-3">
                                            {{ translate('View All Flash Sale') }}
                                        </a>
                                        <span class=" border-left border-soft-light border-width-2 pl-3">
                                            <a href="{{ route('flash-deal-details', $flash_deal->slug) }}"
                                                class="fs-10 fs-md-12 fw-700 has-transition {{ $flash_deal_banner_menu_text }} @if (get_setting('flash_deal_banner_menu_text') == 'light') text-white opacity-60 hov-opacity-100 animate-underline-white @else text-reset opacity-60 hov-opacity-100 hov-text-primary animate-underline-primary @endif">{{ translate('View All Products from This Flash Sale') }}</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!-- Flash Deals Products -->
                            @php
                                $flash_deal_products = get_flash_deal_products($flash_deal->id);
                            @endphp
                            <div class="aiz-carousel border-top @if (count($flash_deal_products) > 8) border-right @endif arrow-inactive-none arrow-x-0"
                                data-items="5" data-xxl-items="5" data-xl-items="3.5" data-lg-items="3"
                                data-md-items="2" data-sm-items="2.5" data-xs-items="2" data-arrows="true"
                                data-dots="false">
                                @php
                                    $init = 0;
                                    $end = 1;
                                @endphp
                                @for ($i = 0; $i < 5; $i++)
                                    <div
                                        class="carousel-box bg-white @if ($i == 0) border-left @endif">
                                        @foreach ($flash_deal_products as $key => $flash_deal_product)
                                            @if ($key >= $init && $key <= $end)
                                                @if ($flash_deal_product->product != null && $flash_deal_product->product->published != 0)
                                                    @php
                                                        $product_url = route(
                                                            'product',
                                                            $flash_deal_product->product->slug,
                                                        );
                                                        if ($flash_deal_product->product->auction_product == 1) {
                                                            $product_url = route(
                                                                'auction-product',
                                                                $flash_deal_product->product->slug,
                                                            );
                                                        }
                                                    @endphp
                                                    <div
                                                        class="h-100px h-md-200px h-lg-auto flash-deal-item position-relative text-center border-bottom @if ($i != 4) border-right @endif has-transition hov-shadow-out z-1">
                                                        <a href="{{ $product_url }}"
                                                            class="d-block py-md-2 overflow-hidden hov-scale-img"
                                                            title="{{ $flash_deal_product->product->getTranslation('name') }}">
                                                            <!-- Image -->
                                                            <img src="{{ get_image($flash_deal_product->product->thumbnail) }}"
                                                                class="lazyload h-60px h-md-100px h-lg-120px mw-100 mx-auto has-transition"
                                                                alt="{{ $flash_deal_product->product->getTranslation('name') }}"
                                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                            <!-- Price -->
                                                            <div
                                                                class="fs-10 fs-md-14 mt-md-2 text-center h-md-48px has-transition overflow-hidden pt-md-4 flash-deal-price">
                                                                <span
                                                                    class="d-block text-primary fw-700">{{ home_discounted_base_price($flash_deal_product->product) }}</span>
                                                                @if (home_base_price($flash_deal_product->product) != home_discounted_base_price($flash_deal_product->product))
                                                                    <del
                                                                        class="d-block fw-400 text-secondary">{{ home_base_price($flash_deal_product->product) }}</del>
                                                                @endif
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach

                                        @php
                                            $init += 2;
                                            $end += 2;
                                        @endphp
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Today's deal -->
    @php
        $todays_deal_section_bg = get_setting('todays_deal_section_bg_color');
    @endphp
    <div id="todays_deal" class="mb-2rem mt-4 "
        @if (get_setting('todays_deal_section_bg') == 1) style="background: {{ $todays_deal_section_bg }};" @endif>

    </div>

    <!-- Banner section 1 -->
    @if (get_setting('home_banner1_images') != null)
        <div class="section-padding" style="background: #fff;">
            <div class="container mb-2 mb-md-3">
                @php
                    $banner_1_imags = json_decode(get_setting('home_banner1_images'));
                    $data_md = count($banner_1_imags) >= 2 ? 2 : 1;
                @endphp
                <div class="w-100">
                    <div class="aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
                        data-items="{{ count($banner_1_imags) }}" data-xxl-items="{{ count($banner_1_imags) }}"
                        data-xl-items="{{ count($banner_1_imags) }}" data-lg-items="{{ $data_md }}"
                        data-md-items="{{ $data_md }}" data-sm-items="1" data-xs-items="1" data-arrows="true"
                        data-dots="true">
                        @foreach ($banner_1_imags as $key => $value)
                            <div class="carousel-box overflow-hidden hov-scale-img">
                                <a href="{{ json_decode(get_setting('home_banner1_links'), true)[$key] }}"
                                    class="d-block text-reset overflow-hidden">
                                    <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                        data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                        class="img-fluid lazyload w-100 has-transition"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                </a>
                                <!-- style="max-height: 300px !important; height: 100% !important; object-fit: cover !important;" -->
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Featured Products -->
    <div id="section_featured" style="display: none;"></div>

    <!-- Banner Section 2 -->
    @if (get_setting('home_banner2_images') != null)
        <div class="mb-2 mb-md-3 mt-2 mt-md-3">
            <div class="container">
                @php
                    $banner_2_imags = json_decode(get_setting('home_banner2_images'));
                    $data_md = count($banner_2_imags) >= 2 ? 2 : 1;
                @endphp
                <div class="aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
                    data-items="{{ count($banner_2_imags) }}" data-xxl-items="{{ count($banner_2_imags) }}"
                    data-xl-items="{{ count($banner_2_imags) }}" data-lg-items="{{ $data_md }}"
                    data-md-items="{{ $data_md }}" data-sm-items="1" data-xs-items="1" data-arrows="true"
                    data-dots="false">
                    @foreach ($banner_2_imags as $key => $value)
                        <div class="carousel-box overflow-hidden hov-scale-img">
                            <a href="{{ json_decode(get_setting('home_banner2_links'), true)[$key] }}"
                                class="d-block text-reset overflow-hidden">
                                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                    data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                    class="img-fluid  lazyload w-100 has-transition"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Best Selling  -->
    <div id="section_best_selling" class="pt-3 pb-3">

    </div>
    <!-- New Products -->
    <div id="section_newest" class="pt-3 pb-3">

    </div>
    <!-- Category wise Products -->

    <!-- Top Sellers -->

    <!-- Banner Section 3 -->
    @if (get_setting('home_banner3_images') != null)
        <div class="mb-2 mb-md-3 mt-2 mt-md-3">
            <div class="container">
                @php
                    $banner_3_imags = json_decode(get_setting('home_banner3_images'));
                    $data_md = count($banner_3_imags) >= 2 ? 2 : 1;
                @endphp
                <div class="aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
                    data-items="{{ count($banner_3_imags) }}" data-xxl-items="{{ count($banner_3_imags) }}"
                    data-xl-items="{{ count($banner_3_imags) }}" data-lg-items="{{ $data_md }}"
                    data-md-items="{{ $data_md }}" data-sm-items="1" data-xs-items="1" data-arrows="true"
                    data-dots="false">
                    @foreach ($banner_3_imags as $key => $value)
                        <div class="carousel-box overflow-hidden hov-scale-img">
                            <a href="{{ json_decode(get_setting('home_banner3_links'), true)[$key] }}"
                                class="d-block text-reset overflow-hidden">
                                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                    data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                    class="img-fluid lazyload w-100 has-transition"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Auction Product -->
    @if (addon_is_activated('auction'))
        <div id="auction_products">

        </div>
    @endif

    {{-- <!-- Cupon -->
@if (get_setting('coupon_system') == 1)
<div class=" mt-2 mt-md-3" style="background-color: {{ get_setting('cupon_background_color', '#292933') }}">
    <div class="container">
        <div class="position-relative py-5">
            <div class="text-center text-xl-left z-5">
                <div class="d-lg-flex">
                    <div class="mb-3 mb-lg-0">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            width="109.602" height="93.34" viewBox="0 0 109.602 93.34">
                            <defs>
                                <clipPath id="clip-pathcup">
                                    <path id="Union_10" data-name="Union 10" d="M12263,13778v-15h64v-41h12v56Z"
                                        transform="translate(-11966 -8442.865)" fill="none" stroke="#fff"
                                        stroke-width="2" />
                                </clipPath>
                            </defs>
                            <g id="Group_24326" data-name="Group 24326" transform="translate(-274.201 -5254.611)">
                                <g id="Mask_Group_23" data-name="Mask Group 23"
                                    transform="translate(-3652.459 1785.452) rotate(-45)"
                                    clip-path="url(#clip-pathcup)">
                                    <g id="Group_24322" data-name="Group 24322" transform="translate(207 18.136)">
                                        <g id="Subtraction_167" data-name="Subtraction 167"
                                            transform="translate(-12177 -8458)" fill="none">
                                            <path
                                                d="M12335,13770h-56a8.009,8.009,0,0,1-8-8v-8a8,8,0,0,0,0-16v-8a8.009,8.009,0,0,1,8-8h56a8.009,8.009,0,0,1,8,8v8a8,8,0,0,0,0,16v8A8.009,8.009,0,0,1,12335,13770Z"
                                                stroke="none" />
                                            <path
                                                d="M 12335.0009765625 13768.0009765625 C 12338.3095703125 13768.0009765625 12341.0009765625 13765.30859375 12341.0009765625 13762 L 12341.0009765625 13755.798828125 C 12336.4423828125 13754.8701171875 12333.0009765625 13750.8291015625 12333.0009765625 13746 C 12333.0009765625 13741.171875 12336.4423828125 13737.130859375 12341.0009765625 13736.201171875 L 12341.0009765625 13729.9990234375 C 12341.0009765625 13726.6904296875 12338.3095703125 13723.9990234375 12335.0009765625 13723.9990234375 L 12278.9990234375 13723.9990234375 C 12275.6904296875 13723.9990234375 12272.9990234375 13726.6904296875 12272.9990234375 13729.9990234375 L 12272.9990234375 13736.201171875 C 12277.5576171875 13737.1298828125 12280.9990234375 13741.1708984375 12280.9990234375 13746 C 12280.9990234375 13750.828125 12277.5576171875 13754.869140625 12272.9990234375 13755.798828125 L 12272.9990234375 13762 C 12272.9990234375 13765.30859375 12275.6904296875 13768.0009765625 12278.9990234375 13768.0009765625 L 12335.0009765625 13768.0009765625 M 12335.0009765625 13770.0009765625 L 12278.9990234375 13770.0009765625 C 12274.587890625 13770.0009765625 12270.9990234375 13766.412109375 12270.9990234375 13762 L 12270.9990234375 13754 C 12275.4111328125 13753.9990234375 12278.9990234375 13750.4111328125 12278.9990234375 13746 C 12278.9990234375 13741.5888671875 12275.41015625 13738 12270.9990234375 13738 L 12270.9990234375 13729.9990234375 C 12270.9990234375 13725.587890625 12274.587890625 13721.9990234375 12278.9990234375 13721.9990234375 L 12335.0009765625 13721.9990234375 C 12339.412109375 13721.9990234375 12343.0009765625 13725.587890625 12343.0009765625 13729.9990234375 L 12343.0009765625 13738 C 12338.5888671875 13738.0009765625 12335.0009765625 13741.5888671875 12335.0009765625 13746 C 12335.0009765625 13750.4111328125 12338.58984375 13754 12343.0009765625 13754 L 12343.0009765625 13762 C 12343.0009765625 13766.412109375 12339.412109375 13770.0009765625 12335.0009765625 13770.0009765625 Z"
                                                stroke="none" fill="#fff" />
                                        </g>
                                    </g>
                                </g>
                                <g id="Group_24321" data-name="Group 24321"
                                    transform="translate(-3514.477 1653.317) rotate(-45)">
                                    <g id="Subtraction_167-2" data-name="Subtraction 167"
                                        transform="translate(-12177 -8458)" fill="none">
                                        <path
                                            d="M12335,13770h-56a8.009,8.009,0,0,1-8-8v-8a8,8,0,0,0,0-16v-8a8.009,8.009,0,0,1,8-8h56a8.009,8.009,0,0,1,8,8v8a8,8,0,0,0,0,16v8A8.009,8.009,0,0,1,12335,13770Z"
                                            stroke="none" />
                                        <path
                                            d="M 12335.0009765625 13768.0009765625 C 12338.3095703125 13768.0009765625 12341.0009765625 13765.30859375 12341.0009765625 13762 L 12341.0009765625 13755.798828125 C 12336.4423828125 13754.8701171875 12333.0009765625 13750.8291015625 12333.0009765625 13746 C 12333.0009765625 13741.171875 12336.4423828125 13737.130859375 12341.0009765625 13736.201171875 L 12341.0009765625 13729.9990234375 C 12341.0009765625 13726.6904296875 12338.3095703125 13723.9990234375 12335.0009765625 13723.9990234375 L 12278.9990234375 13723.9990234375 C 12275.6904296875 13723.9990234375 12272.9990234375 13726.6904296875 12272.9990234375 13729.9990234375 L 12272.9990234375 13736.201171875 C 12277.5576171875 13737.1298828125 12280.9990234375 13741.1708984375 12280.9990234375 13746 C 12280.9990234375 13750.828125 12277.5576171875 13754.869140625 12272.9990234375 13755.798828125 L 12272.9990234375 13762 C 12272.9990234375 13765.30859375 12275.6904296875 13768.0009765625 12278.9990234375 13768.0009765625 L 12335.0009765625 13768.0009765625 M 12335.0009765625 13770.0009765625 L 12278.9990234375 13770.0009765625 C 12274.587890625 13770.0009765625 12270.9990234375 13766.412109375 12270.9990234375 13762 L 12270.9990234375 13754 C 12275.4111328125 13753.9990234375 12278.9990234375 13750.4111328125 12278.9990234375 13746 C 12278.9990234375 13741.5888671875 12275.41015625 13738 12270.9990234375 13738 L 12270.9990234375 13729.9990234375 C 12270.9990234375 13725.587890625 12274.587890625 13721.9990234375 12278.9990234375 13721.9990234375 L 12335.0009765625 13721.9990234375 C 12339.412109375 13721.9990234375 12343.0009765625 13725.587890625 12343.0009765625 13729.9990234375 L 12343.0009765625 13738 C 12338.5888671875 13738.0009765625 12335.0009765625 13741.5888671875 12335.0009765625 13746 C 12335.0009765625 13750.4111328125 12338.58984375 13754 12343.0009765625 13754 L 12343.0009765625 13762 C 12343.0009765625 13766.412109375 12339.412109375 13770.0009765625 12335.0009765625 13770.0009765625 Z"
                                            stroke="none" fill="#fff" />
                                    </g>
                                    <g id="Group_24325" data-name="Group 24325">
                                        <rect id="Rectangle_18578" data-name="Rectangle 18578" width="8" height="2"
                                            transform="translate(120 5287)" fill="#fff" />
                                        <rect id="Rectangle_18579" data-name="Rectangle 18579" width="8" height="2"
                                            transform="translate(132 5287)" fill="#fff" />
                                        <rect id="Rectangle_18581" data-name="Rectangle 18581" width="8" height="2"
                                            transform="translate(144 5287)" fill="#fff" />
                                        <rect id="Rectangle_18580" data-name="Rectangle 18580" width="8" height="2"
                                            transform="translate(108 5287)" fill="#fff" />
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <div class="ml-lg-3">
                        <h5 class="fs-36 fw-400 text-white mb-3">{{ translate(get_setting('cupon_title')) }}</h5>
                        <h5 class="fs-20 fw-400 text-gray">{{ translate(get_setting('cupon_subtitle')) }}</h5>
                        <div class="mt-5 pt-5">
                            <a href="{{ route('coupons.all') }}"
                                class="btn text-white hov-bg-white hov-text-dark border border-width-2 fs-16 px-5"
                                style="border-radius: 28px;background: rgba(255, 255, 255, 0.2);box-shadow: 0px 20px 30px rgba(0, 0, 0, 0.16);">{{ translate('View All Coupons') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="position-absolute right-0 bottom-0 h-100">
                <img class="h-100" src="{{ uploaded_asset(get_setting('coupon_background_image')) }}"
                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/coupon.svg') }}';"
                    alt="{{ env('APP_NAME') }} promo">
            </div>
        </div>
    </div>
</div>
@endif
--}}


    <!-- Classified Product -->
    @if (get_setting('classified_product') == 1)
        @php
            $classified_products = get_home_page_classified_products(6);
        @endphp
        @if (count($classified_products) > 0)
            <section class="mb-2 mb-md-3 mt-3 mt-md-5">
                <div class="container">
                    <!-- Top Section -->
                    <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
                        <!-- Title -->
                        <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                            <span class="">{{ translate('Classified Ads') }}</span>
                        </h3>
                        <!-- Links -->
                        <div class="d-flex">
                            <a class="text-dark fs-10 fs-md-12 fw-700 hov-text-primary animate-underline-primary"
                                href="{{ route('customer.products') }}">{{ translate('View All Products') }}</a>
                        </div>
                    </div>
                    <!-- Banner -->
                    @if (get_setting('classified_banner_image') != null || get_setting('classified_banner_image_small') != null)
                        <div class="mb-3 overflow-hidden hov-scale-img d-none d-md-block">
                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                data-src="{{ uploaded_asset(get_setting('classified_banner_image')) }}"
                                alt="{{ env('APP_NAME') }} promo" class="lazyload img-fit h-100 has-transition"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                        </div>
                        <div class="mb-3 overflow-hidden hov-scale-img d-md-none">
                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                data-src="{{ get_setting('classified_banner_image_small') != null ? uploaded_asset(get_setting('classified_banner_image_small')) : uploaded_asset(get_setting('classified_banner_image')) }}"
                                alt="{{ env('APP_NAME') }} promo" class="lazyload img-fit h-100 has-transition"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                        </div>
                    @endif
                    <!-- Products Section -->
                    <div class="bg-white pt-3">
                        <div class="row no-gutters border-top border-left">
                            @foreach ($classified_products as $key => $classified_product)
                                <div
                                    class="col-xl-4 col-md-6 border-right border-bottom has-transition hov-shadow-out z-1">
                                    <div class="aiz-card-box p-2 has-transition bg-white">
                                        <div class="row hov-scale-img">
                                            <div class="col-4 col-md-5 mb-3 mb-md-0">
                                                <a href="{{ route('customer.product', $classified_product->slug) }}"
                                                    class="d-block overflow-hidden h-auto h-md-150px text-center">
                                                    <img class="img-fluid lazyload mx-auto has-transition"
                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        data-src="{{ isset($classified_product->thumbnail->file_name) ? my_asset($classified_product->thumbnail->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                                        alt="{{ $classified_product->getTranslation('name') }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                </a>
                                            </div>
                                            <div class="col">
                                                <h3
                                                    class="fw-400 fs-14 text-dark text-truncate-2 lh-1-4 mb-3 h-35px d-none d-sm-block">
                                                    <a href="{{ route('customer.product', $classified_product->slug) }}"
                                                        class="d-block text-reset hov-text-primary">{{ $classified_product->getTranslation('name') }}</a>
                                                </h3>
                                                <div class="fs-14 mb-3">
                                                    <span
                                                        class="text-secondary">{{ $classified_product->user ? $classified_product->user->name : '' }}</span><br>
                                                    <span
                                                        class="fw-700 text-primary">{{ single_price($classified_product->unit_price) }}</span>
                                                </div>
                                                @if ($classified_product->conditon == 'new')
                                                    <span
                                                        class="badge badge-inline badge-soft-info fs-13 fw-700 px-3 py-2 text-info"
                                                        style="border-radius: 20px;">{{ translate('New') }}</span>
                                                @elseif($classified_product->conditon == 'used')
                                                    <span
                                                        class="badge badge-inline badge-soft-secondary-base fs-13 fw-700 px-3 py-2 text-danger"
                                                        style="border-radius: 20px;">{{ translate('Used') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @endif


@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var $slider = $(".slider"),
                $slideBGs = $(".slide__bg"),
                diff = 0,
                curSlide = 0,
                numOfSlides = $(".slide").length - 1,
                animating = false,
                animTime = 500,
                autoSlideTimeout,
                autoSlideDelay = 6000,
                $pagination = $(".slider-pagi");

            function createBullets() {
                for (var i = 0; i < numOfSlides + 1; i++) {
                    var $li = $("<li class='slider-pagi__elem'></li>");
                    $li.addClass("slider-pagi__elem-" + i).data("page", i);
                    if (!i) $li.addClass("active");
                    $pagination.append($li);
                }
            }

            createBullets();

            function manageControls() {
                $(".slider-control").removeClass("inactive");
                if (!curSlide) $(".slider-control.left").addClass("inactive");
                if (curSlide === numOfSlides)
                    $(".slider-control.right").addClass("inactive");
            }

            function autoSlide() {
                autoSlideTimeout = setTimeout(function() {
                    curSlide++;
                    if (curSlide > numOfSlides) curSlide = 0;
                    changeSlides();
                }, autoSlideDelay);
            }

            autoSlide();

            function changeSlides(instant) {
                if (!instant) {
                    animating = true;
                    manageControls();
                    $slider.addClass("animating");
                    $slider.css("top");
                    $(".slide").removeClass("active");
                    $(".slide-" + curSlide).addClass("active");
                    setTimeout(function() {
                        $slider.removeClass("animating");
                        animating = false;
                    }, animTime);
                }
                window.clearTimeout(autoSlideTimeout);
                $(".slider-pagi__elem").removeClass("active");
                $(".slider-pagi__elem-" + curSlide).addClass("active");
                $slider.css("transform", "translate3d(" + -curSlide * 100 + "%,0,0)");
                $slideBGs.css("transform", "translate3d(" + curSlide * 50 + "%,0,0)");
                diff = 0;
                autoSlide();
            }

            function navigateLeft() {
                if (animating) return;
                if (curSlide > 0) curSlide--;
                changeSlides();
            }

            function navigateRight() {
                if (animating) return;
                if (curSlide < numOfSlides) curSlide++;
                changeSlides();
            }

            $(document).on("mousedown touchstart", ".slider", function(e) {
                if (animating) return;
                window.clearTimeout(autoSlideTimeout);
                var startX = e.pageX || e.originalEvent.touches[0].pageX,
                    winW = $(window).width();
                diff = 0;

                $(document).on("mousemove touchmove", function(e) {
                    var x = e.pageX || e.originalEvent.touches[0].pageX;
                    diff = ((startX - x) / winW) * 70;
                    if ((!curSlide && diff < 0) || (curSlide === numOfSlides && diff > 0))
                        diff /= 2;
                    $slider.css(
                        "transform",
                        "translate3d(" + (-curSlide * 100 - diff) + "%,0,0)"
                    );
                    $slideBGs.css(
                        "transform",
                        "translate3d(" + (curSlide * 50 + diff / 2) + "%,0,0)"
                    );
                });
            });

            $(document).on("mouseup touchend", function(e) {
                $(document).off("mousemove touchmove");
                if (animating) return;
                if (!diff) {
                    changeSlides(true);
                    return;
                }
                if (diff > -8 && diff < 8) {
                    changeSlides();
                    return;
                }
                if (diff <= -8) {
                    navigateLeft();
                }
                if (diff >= 8) {
                    navigateRight();
                }
            });

            $(document).on("click", ".slider-control", function() {
                if ($(this).hasClass("left")) {
                    navigateLeft();
                } else {
                    navigateRight();
                }
            });

            $(document).on("click", ".slider-pagi__elem", function() {
                curSlide = $(this).data("page");
                changeSlides();
            });
        });
    </script>
@endsection
