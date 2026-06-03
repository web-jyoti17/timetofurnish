@if(count($todays_deal_products) > 0)
    @php
        $todaysDealCount = count($todays_deal_products);
    @endphp
    <section class="mb-4 mt-4 home-mobile-product-section home-todays-deal-section" id="section_todays_deals_home">
        <div class="container">
            <div class="todays-deal-card-wrap">
                <!-- Section Header - Centered with Theme Color -->
                <div class="modern-section-header home-section-heading-with-arrows mb-4">
                    <div class="home-section-heading-copy">
                        <h3 class="modern-section-title">
                            {{ translate("Today's Deals") }}
                        </h3>
                        <div class="modern-section-subtitle">
                            {{ translate('Unbeatable offers await, ensuring maximum savings') }}
                        </div>
                    </div>
                    <div class="home-section-arrow-group @if($todaysDealCount <= 5) home-arrows-desktop-disabled @endif @if($todaysDealCount <= 2) home-arrows-mobile-disabled @endif">
                        <a href="{{ route('todays-deal') }}" class="modern-view-all-link">{{ translate('View All') }} &rarr;</a>
                        <span class="home-section-arrows-only">
                            <button type="button" class="home-section-arrow is-prev" aria-label="{{ translate('Previous') }}" onclick="homeSectionSlide('prev','section_todays_deals_home')">
                                <i class="las la-angle-left"></i>
                            </button>
                            <button type="button" class="home-section-arrow is-next" aria-label="{{ translate('Next') }}" onclick="homeSectionSlide('next','section_todays_deals_home')">
                                <i class="las la-angle-right"></i>
                            </button>
                        </span>
                    </div>
                </div>

                <!-- Banner -->
                @if (get_setting('todays_deal_banner') != null || get_setting('todays_deal_banner_small') != null)
                    <div class="mb-4 overflow-hidden rounded-12 shadow-sm d-none d-md-block">
                        <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                            data-src="{{ uploaded_asset(get_setting('todays_deal_banner')) }}"
                            alt="{{ env('APP_NAME') }} promo" class="lazyload img-fit w-100 has-transition"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                    </div>
                    <div class="mb-4 overflow-hidden rounded-12 shadow-sm d-md-none">
                        <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                            data-src="{{ get_setting('todays_deal_banner_small') != null ? uploaded_asset(get_setting('todays_deal_banner_small')) : uploaded_asset(get_setting('todays_deal_banner')) }}"
                            alt="{{ env('APP_NAME') }} promo" class="lazyload img-fit w-100 has-transition"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                    </div>
                @endif

                <!-- Products -->
                <div class="px-sm-3">
                    <div class="aiz-carousel sm-gutters-16 arrow-none home-mobile-product-carousel" data-items="5" data-xxl-items="5" data-xl-items="5" data-lg-items="4" data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows="true" data-dots="false" data-infinite="false" data-autoplay="false">
                        @foreach ($todays_deal_products as $key => $product)
                            <div class="carousel-box px-2 position-relative">
                                @include('frontend.'.get_setting('homepage_select').'.partials.product_box_1', ['product' => $product])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
