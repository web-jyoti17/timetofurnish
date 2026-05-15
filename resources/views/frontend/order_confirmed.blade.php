@extends('frontend.layouts.app')

@section('content')

    <!-- Steps -->
    <section class="pt-5 mb-0">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="row gutters-5 sm-gutters-10">
                        <div class="col done">
                            <div class="text-center border border-bottom-6px p-2 text-success">
                                <i class="la-3x mb-2 las la-shopping-cart"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('1. My Cart') }}</h3>
                            </div>
                        </div>
                        <div class="col done">
                            <div class="text-center border border-bottom-6px p-2 text-success">
                                <i class="la-3x mb-2 las la-map"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('2. Shipping info') }}
                                </h3>
                            </div>
                        </div>
                        <div class="col done">
                            <div class="text-center border border-bottom-6px p-2 text-success">
                                <i class="la-3x mb-2 las la-truck"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('3. Delivery info') }}
                                </h3>
                            </div>
                        </div>
                        <div class="col done">
                            <div class="text-center border border-bottom-6px p-2 text-success">
                                <i class="la-3x mb-2 las la-credit-card"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('4. Payment') }}</h3>
                            </div>
                        </div>
                        <div class="col active">
                            <div class="text-center border border-bottom-6px p-2 text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32.001" viewBox="0 0 32 32.001" class="cart-rotate mb-3 mt-1">
                                    <g id="Group_23976" data-name="Group 23976" transform="translate(-282 -404.889)">
                                      <path class="cart-ok has-transition" id="Path_28723" data-name="Path 28723" d="M313.283,409.469a1,1,0,0,0-1.414,0l-14.85,14.85-5.657-5.657a1,1,0,1,0-1.414,1.414l6.364,6.364a1,1,0,0,0,1.414,0l.707-.707,14.85-14.849A1,1,0,0,0,313.283,409.469Z" fill="#ffffff"/>
                                      <g id="LWPOLYLINE">
                                        <path id="Path_28724" data-name="Path 28724" d="M313.372,416.451,311.72,418.1a14,14,0,1,1-5.556-8.586l1.431-1.431a16,16,0,1,0,5.777,8.365Z" fill="#d43533"/>
                                      </g>
                                    </g>
                                </svg>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('5. Confirmation') }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Order Confirmation -->
    <section class="py-4">
        <div class="container text-left">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    @php
                        $first_order = $combined_order->orders->first();
                    @endphp
                    <!-- Order Confirmation Text-->
                    <div class="text-center py-1 mb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" class=" mb-3">
                            <g id="Group_23983" data-name="Group 23983" transform="translate(-978 -481)">
                              <circle id="Ellipse_44" data-name="Ellipse 44" cx="18" cy="18" r="18" transform="translate(978 481)" fill="#85b567"/>
                              <g id="Group_23982" data-name="Group 23982" transform="translate(32.439 8.975)">
                                <rect id="Rectangle_18135" data-name="Rectangle 18135" width="11" height="3" rx="1.5" transform="translate(955.43 487.707) rotate(45)" fill="#fff"/>
                                <rect id="Rectangle_18136" data-name="Rectangle 18136" width="3" height="18" rx="1.5" transform="translate(971.692 482.757) rotate(45)" fill="#fff"/>
                              </g>
                            </g>
                        </svg>
                        <h1 class="mb-2 fs-28 fw-500 text-success">{{ translate('Thank You for Your Order!')}}</h1>
                        <p class="fs-13 text-soft-dark">{{  translate('A copy of your order summary has been sent to') }} <strong>{{ json_decode($first_order->shipping_address)->email }}</strong></p>
                    </div>
                    <!-- Order Summary -->
                    <div class="mb-4 bg-white p-4 border">
                        <h5 class="fw-600 mb-3 fs-16 text-soft-dark pb-2 border-bottom">{{ translate('Order Summary')}}</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table fs-14 text-soft-dark">
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 pl-0 py-2">{{ ('Order Date')}} &nbsp;&nbsp;:</td>
                                        <td class="border-top-0 py-2">{{ date('d-m-Y h:i A', $first_order->date) }}</td>
                                    </tr>
                                    @php 
                                        $shipping_address = "";
                                        if(!empty($first_order->shipping_address)) $shipping_address = json_decode($first_order->shipping_address);
                                    @endphp
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 pl-0 py-2">{{ translate('Name')}}&nbsp;&nbsp;:</td>
                                        <td class="border-top-0 py-2">@if(!empty($shipping_address->name)) {{ $shipping_address->name }} @endif </td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 pl-0 py-2">{{ translate('Email')}}&nbsp;&nbsp;:</td>
                                        <td class="border-top-0 py-2">@if(!empty($shipping_address->email)) {{ $shipping_address->email }}  @endif </td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 pl-0 py-2">{{ ('Shipping Address')}}&nbsp;&nbsp;:</td>
                                        <td class="border-top-0 py-2">

     @php
    $addressParts = [];

    if(!empty($shipping_address->flat)) {
        $addressParts[] = $shipping_address->flat;
    }

    if(!empty($shipping_address->address)) {
        $addressParts[] = $shipping_address->address;
    }

    if(!empty($shipping_address->street)) {
        $addressParts[] = $shipping_address->street;
    }

     if(!empty($shipping_address->postal_code)) {
        $addressParts[] = $shipping_address->postal_code;
    }
      if(!empty($shipping_address->city_id)) {
        $addressParts[] = $shipping_address->city_id;
    }

   

    if(!empty($shipping_address->country)) {
        $addressParts[] = $shipping_address->country;
    }

   
@endphp



{{ implode(', ', $addressParts) }}


    </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <td class="fw-600 border-top-0 " style="white-space:nowrap;">{{ ('Order Status')}} &nbsp;&nbsp;:</td>
                                        <td class="border-top-0 ">{{ translate(ucfirst(str_replace('_', ' ', $first_order->delivery_status))) }}</td>
                                    </tr>
                                {{--   <tr>
                                        <td class="w-50 fw-600 border-top-0 py-2">{{ translate('Total order amount')}} &nbsp;&nbsp;:</td>
                                        <td class="border-top-0 pr-0 py-2">{{ single_price($combined_order->grand_total) }}</td>
                                    </tr>--}}
                                  {{--  <tr>
                                        <td class="w-50 fw-600 border-top-0 py-2">{{ translate('Shipping')}} &nbsp;&nbsp;:</td>
                                        <td class="border-top-0 pr-0 py-2">{{ translate('Flat shipping rate')}}</td>
                                    </tr>--}}
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 py-2">{{ ('Payment Method')}}&nbsp;&nbsp;:</td>
                                        <td class="border-top-0 pr-0 py-2">{{ translate(ucfirst(str_replace('_', ' ', $first_order->payment_type))) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 py-2"> {{translate ('Mobile Number')}}&nbsp;&nbsp;: </td> 
                                        <td  class="border-top-0 pr-0 py-2"> @if(!empty($shipping_address->phone)) {{ $shipping_address->phone }}  @endif</td>
                                    </tr>
                                     <tr>
                                        <td class="w-50 fw-600 border-top-0 py-2"> {{translate ('Landline Number')}} &nbsp;&nbsp;: </td> 
                                        <td  class="border-top-0 pr-0 py-2"> @if(!empty($shipping_address->landline_no)) {{ $shipping_address->landline_no }}  @endif</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Info -->
                    @foreach ($combined_order->orders as $order)
                        <div class="card shadow-none border rounded-0">
                            <div class="card-body">
                                <!-- Order Code -->
                                <div class="text-center py-1 mb-4">
                                    <h2 class="h5 fs-20">{{ translate('Order Id')}} <span class="fw-700 text-primary">{{ $order->code }}</span></h2>
                                </div>
                                <!-- Order Details -->
                                <div>
                                    <h5 class="fw-600 text-soft-dark mb-3 fs-16 pb-2">{{ translate('Order Details')}}</h5>
                                    <!-- Product Details -->
                                    <div>
                                        <table class="table table-responsive-md text-soft-dark fs-14">
                                            <thead>
                                                <tr>
                                                    <th class="opacity-60 border-top-0 pl-0">#</th>
                                                    <th class="opacity-60 border-top-0" width="30%">{{ translate('Product')}}</th>
                                                    <th class="opacity-60 border-top-0">{{ translate('Variation')}}</th>
                                                    <th class="opacity-60 border-top-0">{{ translate('Quantity')}}</th>
                                                    <th class="opacity-60 border-top-0">{{ translate('Delivery Type')}}</th>
                                                    <th class="text-center opacity-60 border-top-0 pr-0">{{ translate('Price')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($order->orderDetails as $key => $orderDetail)
                                                    <tr>
                                                        <td class="border-top-0 border-bottom pl-0">{{ $key+1 }}</td>
                                                        <td class="border-top-0 border-bottom">
                                                            @if ($orderDetail->product != null)
                                                                <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank" class="text-reset">
                                                                    {{ $orderDetail->product->getTranslation('name') }}
                                                                    @php
                                                                        if($orderDetail->combo_id != null) {
                                                                            $combo = \App\ComboProduct::findOrFail($orderDetail->combo_id);

                                                                            echo '('.$combo->combo_title.')';
                                                                        }
                                                                    @endphp
                                                                </a>
															
															   {{-- 🔥 ADDONS SHOW --}}
        @if(!empty($orderDetail->addons))
            @php
                $addons = json_decode($orderDetail->addons, true);
            @endphp

            @if(!empty($addons))
                <div class="mt-1">
                    <small class="text-black">
                      
                        <ul class="mb-0 pl-3">
                            @foreach($addons as $addon)
                                <li>
                                    {{ $addon['name'] ?? '' }}
                                    @if(!empty($addon['price']))
                                        {{ single_price($addon['price']) }}
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </small>
                </div>
            @endif
        @endif
                                                            @else
                                                                <strong>{{  translate('Product Unavailable') }}</strong>
                                                            @endif
                                                        </td>
                                                        <td class="border-top-0 border-bottom">
                                                            {{ $orderDetail->variation }}
                                                        </td>
                                                        <td class="border-top-0 border-bottom text-center ">
                                                            {{ $orderDetail->quantity }}
                                                        </td>
                                                        <td class="border-top-0 border-bottom ">  {{  translate('Home Delivery') }}
                                                         {{--   @if ($order->shipping_type != null && $order->shipping_type == 'home_delivery')
                                                                {{  translate('Home Delivery') }}
                                                            @elseif ($order->shipping_type != null && $order->shipping_type == 'carrier')
                                                                {{  translate('Carrier') }}
                                                            @elseif ($order->shipping_type == 'pickup_point')
                                                                @if ($order->pickup_point != null)
                                                                    {{ $order->pickup_point->getTranslation('name') }} ({{ translate('Pickip Point') }})
                                                                @endif
                                                            @endif --}}
                                                        </td>
                                                        <td class="border-top-0 border-bottom pr-0 text-right">{{ single_price($orderDetail->price) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Order Amounts -->
                                    <div class="row">
                                        <div class="col-xl-5 col-md-6 ml-auto mr-0">
                                            <table class="table ">
                                                <tbody>
                                                    <!-- Subtotal -->
                                                    <tr>
                                                        <th class="border-top-0 py-2">{{ translate('Subtotal')}}</small></th>
                                                        <td class="text-right border-top-0 pr-0 py-2">
                                                            <span class="fw-600">{{ single_price($order->grand_total) }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
    <th class="border-top-0 py-2">
        {{ translate('VAT') }} <small></small>
    </th>
    <td class="text-right border-top-0 pr-0 py-2">
        <span>
            {{ single_price($order->orderDetails->sum('tax')) }}
        </span>
    </td>
</tr>
                                                  {{--  <!-- Tax -->
                                                    <!--@if($order->igst)-->
                                                    <!--<tr>-->
                                                    <!--    <th class="border-top-0 py-2">{{ translate('IGST')}} <small>(18%)</small></th>-->
                                                    <!--    <td class="text-right border-top-0 pr-0 py-2">-->
                                                    <!--        <span>{{ single_price($order->orderDetails->sum('tax')) }}</span>-->
                                                    <!--    </td>-->
                                                    <!--</tr>-->
                                                    <!--@else-->
                                                    <!--@php -->
                                                    <!--    $newTax = $order->orderDetails->sum('tax')/2;-->
                                                    <!--@endphp--> 
                                                    <tr>
                                                        <th class="border-top-0 py-2">{{ translate('SGST')}}  <small>(9%)</small></th>
                                                        <td class="text-right border-top-0 pr-0 py-2">
                                                            <span>{{ single_price($newTax) }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="border-top-0 py-2">{{ translate('CGST')}} <small>(9%)</small></th>
                                                        <td class="text-right border-top-0 pr-0 py-2">
                                                            <span>{{ single_price($newTax) }}</span>
                                                        </td>
                                                    </tr>
                                                    @endif --}}
                                                    <!-- Shipping -->
                                                    <tr>
                                                        <th class="border-top-0 py-2">{{ translate('Shipping')}}</th>
                                                        <td class="text-right border-top-0 pr-0 py-2">
                                                            <span>{{ single_price($order->orderDetails->sum('shipping_cost')) }}</span>
                                                        </td>
                                                    </tr>
                                                    <!-- Coupon Discount -->
                                                    <tr>
                                                        <th class="border-top-0 py-2">{{ ('Coupon Discount')}}</th>
                                                        <td class="text-right border-top-0 pr-0 py-2">
                                                            <span>{{ single_price($order->coupon_discount) }}</span>
                                                        </td>
                                                    </tr>
                                                    
                                                    <!-- Total -->
                                                    <tr>
                                                        <th class="py-2"><span class="fw-600">{{ translate('Total')}} <small> </small></span></th>
                                                        <td class="text-right pr-0">
                                                            <strong><span>{{ single_price($order->grand_total) }}</span></strong>
                                                        </td>
                                                    </tr>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
