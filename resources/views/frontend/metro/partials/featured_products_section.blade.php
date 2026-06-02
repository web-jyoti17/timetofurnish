@php
    $featuredProducts = get_featured_products();
    $featuredProductCount = count($featuredProducts);
@endphp
@if ($featuredProductCount > 0)
    <section class="mb-4 mt-4 home-mobile-product-section home-featured-products-section"
        id="section_featured_products_home">
        <div class="container">
            <div class="modern-section-bordered-wrap">
                <!-- Section Header - Centered with Theme Color -->
                <div class="modern-section-header home-section-heading-with-arrows mb-4">
                    <div class="home-section-heading-copy">
                        <h3 class="modern-section-title">
                            {{ translate('Featured Products') }}
                        </h3>
                        <div class="modern-section-subtitle">
                            {{ translate('Handpicked outstanding items selected for you') }}
                        </div>
                    </div>
                    <div
                        class="home-section-arrow-group @if ($featuredProductCount <= 5) home-arrows-desktop-disabled @endif @if ($featuredProductCount <= 2) home-arrows-mobile-disabled @endif">
                        <a href="{{ route('search') }}" class="modern-view-all-link">{{ translate('View All') }}
                            &rarr;</a>
                        <span class="home-section-arrows-only">
                            <button type="button" class="home-section-arrow is-prev"
                                aria-label="{{ translate('Previous') }}"
                                onclick="homeSectionSlide('prev','section_featured_products_home')">
                                <i class="las la-angle-left"></i>
                            </button>
                            <button type="button" class="home-section-arrow is-next"
                                aria-label="{{ translate('Next') }}"
                                onclick="homeSectionSlide('next','section_featured_products_home')">
                                <i class="las la-angle-right"></i>
                            </button>
                        </span>
                    </div>
                </div>

                <!-- Products Section -->
                <div class="px-sm-3">
                    <div class="aiz-carousel  sm-gutters-16 arrow-none home-mobile-product-carousel" data-items="5"
                        data-xxl-items="5" data-xl-items="5" data-lg-items="4" data-md-items="3" data-sm-items="2.35"
                        data-xs-items="2.35" data-arrows="true" data-dots="false" data-infinite="false"
                        data-autoplay="false">
                        @foreach ($featuredProducts as $key => $product)
                            <div class="carousel-box px-2 position-relative">
                                @include(
                                    'frontend.' . get_setting('homepage_select') . '.partials.product_box_1',
                                    ['product' => $product]
                                )
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
