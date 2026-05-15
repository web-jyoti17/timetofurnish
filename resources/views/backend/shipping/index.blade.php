@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Shipping Charges')}}</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" method="POST" action="{{route('add.shipping.cost')}}">
                	@csrf 
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Order Total')}}</label>
                        <div class="col-md-9">
                            @if(isset($shipping->id))
                            <input type="hidden" name="id" class="form-control" value="{{$shipping->id}}" required>
                            @endif
                            <input type="text" placeholder="{{translate('Order total')}}" value="{{$shipping->order_total}}"  id="category_name" name="order_total" class="form-control" required>
                            <small><i>Please add the price below which shipping charges are applied.</i></small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Shipping Charges 1')}}</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{translate('Shipping cost for same city')}}" value="{{$shipping->shipping_1}}" id="shipping_1" name="shipping_1" class="form-control" required>
                            <small><i>Shipping charges when customer and seller have same city</i></small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Shipping Charges 2')}}</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{translate('Shipping cost for same state')}}" value="{{$shipping->shipping_2}}" id="shipping_2" name="shipping_2" class="form-control" required>
                            <small><i>Shipping charges when customer and seller have same state</i></small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Shipping Charges 3')}}</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{translate('Shipping cost for different state')}}" value="{{$shipping->shipping_3}}" id="shipping_3" name="shipping_3" class="form-control" required>
                            <small><i>Shipping charges when customer and seller have different state</i></small>
                        </div>
                    </div>
                    
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn ">
                            {{translate('Save')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
