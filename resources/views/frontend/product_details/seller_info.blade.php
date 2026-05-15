@if ($detailedProduct->added_by == 'seller' && $detailedProduct->user->shop != null)
<div class="border mb-4" style="background: #FAF6F3;">
    
    <div class="p-3 p-sm-4 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">

        <!-- LEFT SIDE -->
        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center w-100">

            <!-- Seller Text -->
            {{--<div class="opacity-60 fs-20 fw-bold mr-sm-3 mb-2 mb-sm-0 heading">
                {{ translate('Seller') }}
            </div>--}}

            <!-- Image + Name -->
            <div class="d-flex align-items-center mr-sm-4 mb-2 mb-sm-0">
                
                <a href="{{ route('shop.visit', $detailedProduct->user->shop->slug) }}"
                   class="avatar avatar-md mr-2 overflow-hidden border">
                    <img class="lazyload seller_img"
                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                        data-src="{{ uploaded_asset($detailedProduct->user->shop->logo) }}">
                </a>

                <div>
                    <div class="fw-700">
                        {{ $detailedProduct->user->name }}
                    </div>
                    <div class="opacity-70 fs-12">
                        {{ $detailedProduct->user->shop->address }}
                    </div>
                </div>
            </div>

            <!-- Rating -->
            <div class="mb-2 mb-sm-0">
                <div class="rating rating-mr-1">
                    {{ renderStarRating($detailedProduct->user->shop->rating) }}
                </div>
                <div class="opacity-60 fs-12">
                    ({{ $detailedProduct->user->shop->num_of_reviews }} {{ translate('customer reviews') }})
                </div>
            </div>

        </div>

        <!-- RIGHT SIDE BUTTON -->
        <div class="mt-2 mt-md-0 w-100 w-md-auto text-left text-md-right">
            <a href="{{ route('shop.visit', $detailedProduct->user->shop->slug) }}"
                class="btn btn-slide-secondary-base fs-14 fw-700 rounded-0 w-100 visit-store-btn"
                style="background: #fff; border:1px solid #eaeaea;">
                {{ ('Visit Seller Store') }}
            </a>
        </div>

    </div>

</div>
@endif
<style>
@media (min-width: 768px) {
    .visit-store-btn {
        padding-left: 80px;
        padding-right: 80px;
		 white-space: nowrap;
    }
}
</style>
