@extends('frontend.layouts.app')

@section('content')
 <!-- Breadcrumb -->
    <section class="pt-4 mb-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 text-center text-lg-left">
                    <h1 class="fw-700 fs-20 fs-md-24 text-dark">{{ translate("Today's Deals") }}</h1>
                </div>
                <div class="col-lg-6">
                    <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end">
                        <li class="breadcrumb-item has-transition opacity-60 hov-opacity-100">
                            <a class="text-reset" href="{{ route('home') }}">{{ translate('Home')}}</a>
                        </li>
                        <li class="text-dark fw-600 breadcrumb-item">
                            "{{ translate("Today's Deals") }}"
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- All Sellers -->
    <section class="mb-5" style="margin-top: 2rem;">
        <div class="container">
            <!-- Banner -->
            @if (get_setting('todays_deal_banner') != null || get_setting('todays_deal_banner_small') != null)
                <div class="mb-4 overflow-hidden hov-scale-img d-none d-md-block">
                    <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" 
                        data-src="{{ uploaded_asset(get_setting('todays_deal_banner')) }}" 
                        alt="{{ env('APP_NAME') }} promo" class="lazyload img-fit h-100 has-transition" 
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                </div>
                <div class="mb-4 overflow-hidden hov-scale-img d-md-none">
                    <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" 
                        data-src="{{ get_setting('todays_deal_banner_small') != null ? uploaded_asset(get_setting('todays_deal_banner_small')) : uploaded_asset(get_setting('todays_deal_banner')) }}" 
                        alt="{{ env('APP_NAME') }} promo" class="lazyload img-fit h-100 has-transition" 
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                </div>
            @endif
            <!-- Products Section -->
            <div class="px-3">
                <div class="row row-cols-xxl-6 row-cols-xl-5 row-cols-lg-4 row-cols-md-3 row-cols-sm-2 row-cols-2 gutters-16 border-top border-left">
                    @foreach ($todays_deal_products as $key => $product)
                        <div class="col text-center border-right border-bottom has-transition hov-shadow-out z-1 custom-profuct-image-style">
                            @include('frontend.'.get_setting('homepage_select').'.partials.product_box_1',['product' => $product])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
