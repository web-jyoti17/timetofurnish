@extends('frontend.layouts.app')

@section('content')
    <style>
        .order-confirmed-page {
            color: #20202c;
        }

        .order-confirmed-page .order-success-icon {
            width: 42px;
            height: 42px;
            margin: 0 auto 18px;
            border-radius: 50%;
            background: #85b567;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            font-weight: 700;
            line-height: 1;
        }

        .order-confirmed-page .order-thank-title {
            display: inline-block;
            padding: 8px 14px;
            background: #dedede;
            color: #85b567;
            font-size: 38px;
            font-weight: 700;
            line-height: 1.15;
            margin-bottom: 12px;
        }

        .order-confirmed-page .order-copy-note {
            color: #20202c;
            font-size: 17px;
            margin-bottom: 34px;
        }

        .order-confirmed-page .order-panel {
            background: #fff;
            border: 1px solid #e6e8ef;
            padding: 34px 38px;
            margin-bottom: 30px;
        }

        .order-confirmed-page .order-panel-title {
            color: #20202c;
            font-size: 22px;
            font-weight: 700;
            padding-bottom: 18px;
            margin-bottom: 30px;
            border-bottom: 1px solid #e6e8ef;
        }

        .order-confirmed-page .summary-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 44px;
        }

        .order-confirmed-page .summary-row {
            display: grid;
            grid-template-columns: 190px minmax(0, 1fr);
            gap: 18px;
            align-items: start;
            margin-bottom: 22px;
            font-size: 18px;
            line-height: 1.45;
        }

        .order-confirmed-page .summary-label {
            color: #20202c;
            font-weight: 700;
            white-space: nowrap;
        }

        .order-confirmed-page .summary-value {
            color: #20202c;
            overflow-wrap: anywhere;
        }

        .order-confirmed-page .order-code-title {
            color: #20202c;
            font-size: 28px;
            font-weight: 500;
            margin-bottom: 40px;
        }

        .order-confirmed-page .order-code-title span {
            color: #000;
            font-weight: 800;
        }

        @media (max-width: 767px) {
            .order-confirmed-page .order-thank-title {
                font-size: 28px;
            }

            .order-confirmed-page .order-panel {
                padding: 24px 18px;
            }

            .order-confirmed-page .summary-grid {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .order-confirmed-page .summary-row {
                grid-template-columns: 1fr;
                gap: 4px;
                font-size: 16px;
            }
        }
    </style>

    <!-- Steps -->
    <section class="pt-5 mb-0 cart_tabs">
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
    <section class="py-4 order-confirmed-page">
        <div class="container text-left">
            <div class="row">
                <div class="col-xl-11 mx-auto">
                    @php
                        $first_order = $combined_order->orders->first();
                        $shipping_address = null;
                        if (!empty($first_order->shipping_address)) {
                            $shipping_address = json_decode($first_order->shipping_address);
                        }

                        $addressParts = [];
                        foreach (['flat', 'address', 'street', 'postal_code', 'city', 'country'] as $field) {
                            if (!empty($shipping_address->{$field})) {
                                $addressParts[] = $shipping_address->{$field};
                            }
                        }

                        if (empty($addressParts) && !empty($shipping_address->city_id)) {
                            $addressParts[] = $shipping_address->city_id;
                        }

                        $paymentDetails = json_decode($first_order->payment_details ?? '');
                        $paymentMethod = ucfirst(str_replace('_', ' ', $first_order->payment_type));
                        if (!empty($paymentDetails->mode) && strtolower((string) $paymentDetails->mode) === 'mock') {
                            $paymentMethod = 'Mock';
                        } elseif (strtolower((string) $first_order->payment_type) === 'stripe') {
                            $paymentMethod = 'Stripe';
                        }
                    @endphp
                    <!-- Order Confirmation Text-->
                    <div class="text-center py-1 mb-0">
                        <div class="order-success-icon">✓</div>
                        <h1 class="order-thank-title">{{ translate('Thank You for Your Order!') }}</h1>
                        <p class="order-copy-note">{{ translate('A copy of your order summary has been sent to') }} <strong>{{ $shipping_address->email ?? '' }}</strong></p>
                    </div>
                    <!-- Order Summary -->
                    <div class="order-panel">
                        <h5 class="order-panel-title">{{ translate('Order Summary') }}</h5>
                        <div class="summary-grid">
                            <div>
                                <div class="summary-row">
                                    <div class="summary-label">{{ translate('Order Date') }} &nbsp;:</div>
                                    <div class="summary-value">{{ date('d-m-Y h:i A', $first_order->date) }}</div>
                                </div>
                                <div class="summary-row">
                                    <div class="summary-label">{{ translate('Name') }} &nbsp;:</div>
                                    <div class="summary-value">{{ $shipping_address->name ?? '' }}</div>
                                </div>
                                <div class="summary-row">
                                    <div class="summary-label">{{ translate('Email') }} &nbsp;:</div>
                                    <div class="summary-value">{{ $shipping_address->email ?? '' }}</div>
                                </div>
                                <div class="summary-row">
                                    <div class="summary-label">{{ translate('Shipping Address') }} &nbsp;:</div>
                                    <div class="summary-value">{{ implode(', ', $addressParts) }}</div>
                                </div>
                            </div>
                            <div>
                                <div class="summary-row">
                                    <div class="summary-label">{{ translate('Order Status') }} &nbsp;:</div>
                                    <div class="summary-value">{{ translate(ucfirst(str_replace('_', ' ', $first_order->payment_status))) }}</div>
                                </div>
                                <div class="summary-row">
                                    <div class="summary-label">{{ translate('Payment Method') }} &nbsp;:</div>
                                    <div class="summary-value">{{ translate($paymentMethod) }}</div>
                                </div>
                                <div class="summary-row">
                                    <div class="summary-label">{{ translate('Mobile Number') }} &nbsp;:</div>
                                    <div class="summary-value">{{ $shipping_address->phone ?? '' }}</div>
                                </div>
                                <div class="summary-row">
                                    <div class="summary-label">{{ translate('Landline Number') }} &nbsp;:</div>
                                    <div class="summary-value">{{ $shipping_address->landline_no ?? '' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Info -->
                    @foreach ($combined_order->orders as $order)
                        <div class="order-panel">
                            <div class="card-body">
                                <!-- Order Code -->
                                <div class="text-center py-1 mb-4">
                                    <h2 class="order-code-title">{{ translate('Order Id') }} <span>{{ $order->code }}</span></h2>
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
