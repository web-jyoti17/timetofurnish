@extends('seller.layouts.app')
@section('panel_content')

<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Add Your Coupon') }}</h1>
        </div>
    </div>
</div>

<div class="row gutters-5">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Coupon Information Adding')}}</h5>
            </div>
            
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('seller.coupon.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mt-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Coupon Type (Hidden) --}}
                    <div class="form-group row" style="display:none;">
                        <label class="col-lg-3 col-from-label" for="name">{{translate('Coupon Type')}}</label>
                        <div class="col-lg-9">
                            <select name="type" id="coupon_type" class="form-control aiz-selectpicker" onchange="coupon_form()" required>
                                <option value="">{{translate('Select One') }}</option>
                                <option selected value="product_base">{{translate('For Products')}}</option> 
                                <option value="cart_base">{{translate('For Total Orders')}}</option>
                            </select>
                        </div>
                    </div>

                    {{-- AJAX Coupon Form --}}
                    <div id="coupon_form"></div>

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection


@section('script')

<script type="text/javascript">

function disablePastDates() {

    var today = moment().startOf('day');

    $('.aiz-date-range').each(function () {

        if ($(this).data('daterangepicker')) {
            $(this).data('daterangepicker').minDate = today;
            $(this).data('daterangepicker').setStartDate(today);
        }

    });
}

function coupon_form(){
    var coupon_type = $('#coupon_type').val();

    $.post('{{ route('seller.coupon.get_coupon_form') }}',
    {
        _token:'{{ csrf_token() }}',
        coupon_type:coupon_type
    }, 
    function(data){
        $('#coupon_form').html(data);

        // AJAX load hone ke baad
        setTimeout(function(){
            disablePastDates();
        }, 500);
    });
}

$(document).ready(function(){
    coupon_form();
});

</script>

@endsection
