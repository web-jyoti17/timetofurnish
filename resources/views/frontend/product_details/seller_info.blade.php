@if ($detailedProduct->added_by == 'seller' && $detailedProduct->user->shop != null)
    <div class="border mb-4" style="background: #FAF6F3;">

        <div class="p-3 p-sm-4 d-flex flex-column align-items-center">

            <!-- Seller Info: Logo, Name, Address -->
            <div class="d-flex flex-column align-items-center w-100 mb-3">

                <a href="{{ route('shop.visit', $detailedProduct->user->shop->slug) }}"
                    class="avatar avatar-md mb-2 overflow-hidden border">
                    <img class="lazyload seller_img" src="{{ static_asset('assets/img/placeholder.jpg') }}"
                        data-src="{{ uploaded_asset($detailedProduct->user->shop->logo) }}">
                </a>

                <div class="text-center">
                    <div class="fw-700">
                        {{ $detailedProduct->user->name }}
                    </div>
                    <div class="opacity-70 fs-12">
                        {{ $detailedProduct->user->shop->address }}
                    </div>
                </div>
            </div>

            <!-- Rating -->
            <div class="mb-3 text-center">
                <div class="rating rating-mr-1">
                    {{ renderStarRating($detailedProduct->user->shop->rating) }}
                </div>
                <div class="opacity-60 fs-12">
                    ({{ $detailedProduct->user->shop->num_of_reviews }} {{ translate('customer reviews') }})
                </div>
            </div>

            <!-- Visit Store Button -->
            <div class="w-100 text-center">
                <a href="{{ route('shop.visit', $detailedProduct->user->shop->slug) }}"
                    class="btn btn-slide-secondary-base fs-14 fw-700 rounded-0 w-100 visit-store-btn"
                    style="background: #fff; border:1px solid #eaeaea;">
                    {{ 'Visit Seller Store' }}
                </a>
            </div>

        </div>

    </div>
@endif
<style>
    .visit-store-btn {
        padding-left: 0;
        padding-right: 0;
        white-space: normal;
        max-width: 400px;
        margin: 0 auto;
    }

    /* Optionally enlarge the button and avatar for visual balance in vertical mode */
    .avatar.avatar-md {
        width: 80px;
        height: 80px;
    }
</style>
