@if (count(get_featured_products()) > 0)
    <section class="mb-4 mt-4">
        <div class="container">
            <div class="modern-section-bordered-wrap">
                <!-- Section Header - Centered with Theme Color -->
                <div class="modern-section-header text-center mb-4">
                    <h3 class="modern-section-title">
                        {{ translate('Featured Products') }}
                    </h3>
                    <div class="modern-section-subtitle">
                        {{ translate('Handpicked outstanding items selected for you') }}
                    </div>
                </div>

                <!-- Products Section -->
                <div class="px-sm-3">
                    <div class="aiz-carousel sm-gutters-16 arrow-none" data-items="5" data-xxl-items="5" data-xl-items="5" data-lg-items="4" data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows="false" data-dots="true" data-infinite="false">
                        @foreach (get_featured_products() as $key => $product)
                            <div class="carousel-box px-2 position-relative">
                                @include('frontend.'.get_setting('homepage_select').'.partials.product_box_1', ['product' => $product])
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="text-center mt-4 pt-2">
                    <a href="{{ route('search') }}" class="modern-view-all-link">{{ translate('View All') }} &rarr;</a>
                </div>
            </div>
        </div>
    </section>
@endif