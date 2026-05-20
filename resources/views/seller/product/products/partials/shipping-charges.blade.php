@if($shippingCharges->count() > 0)
    @foreach($shippingCharges as $charge)
        <div class="col-md-6 mb-2">
            <div class="border rounded p-3 h-100 bg-soft-light">
                <div class="fw-700 text-dark">{{ $charge->name }}</div>
                @if(!empty($charge->description))
                    <div class="fs-12 text-muted mt-1">{{ $charge->description }}</div>
                @endif
                <div class="fw-700 mt-2">{{ single_price($charge->price) }}</div>
            </div>
        </div>
    @endforeach
@else
    <div class="col-12">
        <div class="alert alert-info mb-0">
            {{ translate('No shipping charges available for selected category.') }}
        </div>
    </div>
@endif
