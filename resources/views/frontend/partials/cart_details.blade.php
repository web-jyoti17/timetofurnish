<div class="container ">
    @if( $carts && count($carts) > 0 )
        <div class="row ">
            <div class="col-xxl-8 col-xl-10 mx-auto ">
                <div class="border  p-3 p-lg-4 text-left maincontainer">
                    <div class="mb-4 ">
                        <!-- Headers -->
                        <div class="row gutters-5 d-none d-lg-flex border-bottom  px-3 py-3 text-white fs-12 cartbackground pt-2">
                            <div class="col-md-1 fw-600 ">#</div>
                            <div class="col-md-5 fw-600 ">{{ translate('Product')}}</div>
                            <div class="col fw-600">{{ translate('Price')}}</div>
                           <!--<div class="col fw-600">{{ translate('Tax')}}</div> -->
                           <div class="col  fw-600">{{ translate('Quantity')}}</div>
                            <div class="col fw-600">{{ translate('Total')}}</div>
                            <div class="col-auto fw-600">{{ translate('Remove')}}</div>
                        </div>
                        <!-- Cart Items -->
                        <ul class="list-group list-group-flush ms-5">
                            @php
                                $total = 0;
                                $i = 1;
                            @endphp
                            @foreach ($carts as $key => $cartItem)
                                @php
                                    $product = get_single_product($cartItem['product_id']);
                                    $product_stock = $product->stocks->where('variant', $cartItem['variation'])->first();
                                    // $total = $total + ($cartItem['price']  + $cartItem['tax']) * $cartItem['quantity'];
                                    $total = $total + cart_product_price($cartItem, $product, false) * $cartItem['quantity'];
                                    $product_name_with_choice = $product->getTranslation('name');
                                    if ($cartItem['variation'] != null) {
                                        $product_name_with_choice = $product->getTranslation('name').' - '.$cartItem['variation'];
                                    }
                                    $total_addon = $cartItem['addon_price'] * $cartItem['quantity'];
                                    $total = $total + $total_addon;
                                @endphp
                                <li class="list-group-item px-0">
                                    <div class="row gutters-5 align-items-center  ">
                                       <div class="col-md-1 fw-600 list ">{{$i}}</div>
                                        <!-- Product Image & name -->
                                        <div class="col-md-5 d-flex align-items-center  mb-md-0" style="">
                                            <span class="mr-2 ml-0">
                                                <img src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                    class="img-fit size-70px"
                                                    alt="{{ $product->getTranslation('name')  }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                            </span>
                                            <span class="fs-14">{{ $product_name_with_choice }}</span>
											<!-- addon code -->
										{{--	@if(!empty($cartItem->addons))
												@foreach($cartItem->addons as $addon)
													<div class="addon-item ">
														<strong>{{ $addon['addon_name'] }}:</strong>
														{{ $addon['name'] }}
														(+₹{{ $addon['price'] }})

														@if($addon['image'])
															<img src="{{ asset('uploads/addons/'.$addon['image']) }}" width="40">
														@endif
													</div>
												@endforeach
											@endif--}}
											<!-- addon code-->
                                            <!--<div>{{$product->dispatch_time}}</div>-->
                                        </div>
                                        <!-- Price -->
                                        <div class="col-md col-4 order-2 order-md-0 my-3 my-md-0">
                                            <span class="opacity-60 fs-12 d-block d-md-none">{{ translate('Price')}}</span>
                                            <span class="fw-700 fs-14">{{ cart_product_price($cartItem, $product, true, false) }}</span>
                                        </div>
                                        <!-- Tax -->
                                        {{--<div class="col-md col-4 order-3 order-md-0 my-3 my-md-0">
                                            <span class="opacity-60 fs-12 d-block d-md-none">{{ translate('Tax')}}</span>
                                            <span class="fw-700 fs-14">{{ cart_product_tax($cartItem, $product) }}</span>
                                        </div> --}}
                                         <!-- Quantity -->
                                        <div class="col-md-1 col order-1 order-md-0  remove">
                                            @if ($cartItem['digital'] != 1 && $product->auction_product == 0)
                                                <div class="d-flex flex-column align-items-start aiz-plus-minus mr-2 ml-0  ">
                                                    <button
                                                        class="btn col-auto btn-icon btn-sm btn-circle borderbt"
                                                        type="button" data-type="plus"
                                                        data-field="quantity[{{ $cartItem['id'] }}]">
                                                        <i class="las la-plus " style="color:black;"></i>
                                                    </button>
                                                    <input type="number" name="quantity[{{ $cartItem['id'] }}]"
                                                        class="col border-0 text-left px-0 flex-grow-1 fs-14 input-number text-black"
                                                        placeholder="1" value="{{ $cartItem['quantity'] }}"
                                                        min="{{ $product->min_qty }}"
                                                       max="{{ $product_stock->qty ?? 1 }}"
                                                        onchange="updateQuantity({{ $cartItem['id'] }}, this)" style="padding-left:0.75rem !important;">
                                                    <button
                                                        class="btn col-auto btn-icon btn-sm btn-circle borderbt"
                                                        type="button" data-type="minus"
                                                        data-field="quantity[{{ $cartItem['id'] }}]">
                                                        <i class="las la-minus iconcolor"></i>
                                                    </button>
                                                </div>
                                            @elseif($product->auction_product == 1)
                                                <span class="fw-700 fs-14">1</span>
                                            @endif
                                        </div>
                                        <!-- Total -->
                                        <div class="col-md col-5 order-4 order-md-0 my-3 my-md-0 ">
                                            <span class="opacity-60 fs-12 d-block d-md-none">{{ translate('Total')}}</span>
                                            <span class="fw-700 fs-16 text-primary">{{ single_price(cart_product_price($cartItem, $product, false) * $cartItem['quantity'] + $total_addon) }}</span>
                                        </div>
                                        <!-- Remove From Cart -->
                                        <div class="col-md-auto col-6 order-5 order-md-0 text-right remove">
                                            <a href="javascript:void(0)" onclick="removeFromCartView(event, {{ $cartItem['id'] }})" class="btn btn-icon btn-sm borderbtn  btn-circle me-5">
                                                <i class="las la-trash fs-16"></i>
                                            </a>
                                        </div>
                                    </div>
									<!--fabric section-->
									{{-- <div class="row gutters-5 align-items-center  " style="margin-left:72px">
                                       <div class="col-md-1 fw-600 list "></div>
                                        <!-- Product Image & name -->
                                        <div class="col-md-5 d-flex align-items-center mb-2 mb-md-0">
                                            <span class="mr-2 ml-0">
                                              @php $cartItem_addons = []; @endphp
                                                @if(!empty($cartItem->addons))
                                                    @php
                                                        $cartItem_addons = json_decode($cartItem->addons, true);
                                                    @endphp

                                                    @foreach($cartItem_addons as $addon)
                                                        <div class="addon-item">
                                                            <strong>{{ $addon['addon_name'] ?? '' }}:</strong>
                                                            {{ $addon['name'] ?? '' }} &nbsp;

                                                            <strong >£{{ number_format($addon['price'] ?? 0, 2) }}</strong>

                                                        </div>
                                                    @endforeach
                                                @endif
											</span>
                                    </div> --}}
									<!--fabric section -->

                                </li>
                                @php $i++; @endphp
                            @endforeach
                        </ul>
                    </div>

             <!--demo -->

					<!--mobile-->
	<div class="col-12 mt-2 d-block d-md-none">

    @php
        $cartItem_addons = [];
    @endphp

    @if(!empty($cartItem->addons))

        @php
            $cartItem_addons = json_decode($cartItem->addons, true);
        @endphp

        @foreach($cartItem_addons as $addon)

            <div class="addon-row">

                <div class="addon-left">
                    <strong>{{ $addon['addon_name'] ?? '' }}:</strong>
                    {{ $addon['name'] ?? '' }}
                </div>

                <div class="addon-right">
                    <strong>
                        £{{ number_format($addon['price'] ?? 0, 2) }}
                    </strong>
                </div>

            </div>

        @endforeach

    @endif

</div>
						<!--mobile-->
					<!--demo-->
					 <div class="row gutters-5 align-items-center desktop-addon-section">
                                       <div class="col-md-1 fw-600 list "></div>
                                        <!-- Product Image & name -->
                                       <div class="col-md-5 mb-2 mb-md-0 " >

											@php
												$cartItem_addons = [];
											@endphp

											@if(!empty($cartItem->addons))

												@php
													$cartItem_addons = json_decode($cartItem->addons, true);
												@endphp

												@foreach($cartItem_addons as $addon)

													<div class="addon-name mb-1">
														<strong>{{ $addon['addon_name'] ?? '' }}:</strong>
														{{ $addon['name'] ?? '' }}
													</div>

												@endforeach

											@endif

										</div>
                                        <!-- addon Price -->
                                       <div class="col-md col-4 order-2 order-md-0 my-3 my-md-0">

    <!-- Main Product Price -->


						<!-- Addon Prices -->
						@php
							$cartItem_addons = [];
						@endphp

						@if(!empty($cartItem->addons))

							@php
								$cartItem_addons = json_decode(	$cartItem->addons, true);
							@endphp

							@foreach($cartItem_addons as $addon)

								<div class="addon-price">
									<strong>
										£{{ number_format($addon['price'] ?? 0, 2) }}
									</strong>
								</div>

							@endforeach

						@endif

					</div>

                                         <!-- Quantity -->
                                        <div class="col-md-1 col order-1 order-md-0  remove">


                                        </div>
                                        <!-- Total -->
                                        <div class="col-md col-5 order-4 order-md-0 my-3 my-md-0 ">

                                        </div>
                                        <!-- Remove From Cart -->
                                        <div class="col-md-auto col-6 order-5 order-md-0 text-right remove">

                                        </div>
                                    </div>
					<!--demo-->

                    <!-- Subtotal -->
                    <div class="px-0 py-2 mb-4 border-top d-flex justify-content-between">
                        <span class="opacity-60 fs-14 text-black">{{translate('Subtotal')}}</span>
                        <span class="fw-700 fs-16 text-black">{{ single_price($total) }}</span>
                    </div>


   <div class="row">

    <!-- Return to shop -->
    <div class="col-6 col-md-6 text-center text-md-left">
        <a href="{{ route('home') }}"
           class="btn borderbtn fs-14 fw-700 rounded-0 w-100 w-md-auto py-3">
            <i class="las la-arrow-left fs-16"></i>
            {{ translate('Return to shop')}}
        </a>
    </div>

    <!-- Continue to Shipping -->
    <div class="col-6 col-md-6 text-center text-md-right">
        @if(Auth::check())
            <a href="{{ route('checkout.shipping_info') }}"
               class="btn borderbtn fs-14 fw-700 rounded-0 w-100 w-md-auto py-3">
                {{ ('Complete Order')}}
            </a>
        @else
            <button onclick="showLoginModal()"
                class="btn borderbtn fs-14 fw-700 rounded-0 w-100 w-md-auto py-3">
                {{ ('Conmpete Order')}}
            </button>
        @endif
    </div>

</div>

                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-xl-8 mx-auto">
                <div class="border bg-white p-4">
                    <!-- Empty cart -->
                    <div class="text-center p-3">
                        <i class="las la-frown la-3x opacity-60 mb-3"></i>
                        <h3 class="h4 fw-700">{{translate('Your Cart is empty')}}</h3>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
<style>
    .remove{
        margin-right:20px;
    }
    .list{
        margin-left:10px;
    }
    @media (max-width: 767px) {
    .list {
        display: none;
    }
}
@media (max-width: 767px) {
    .borderbtn {
        font-size: 15px;
    }
}
.row > div {
    padding-left: 6px;
    padding-right: 6px;
}
@media (min-width: 768px) {
    .desktop-auto {
        width: auto !important;
    }
}
.addon-name{
    display:block;
    line-height:1.5;
}
	.addon-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    width:100%;
    margin-bottom:12px;
}

.addon-left{
    width:75%;
    font-size:16px;
    line-height:1.5;
}

.addon-right{
    width:25%;
    text-align:right;
    font-size:16px;
}

@media(max-width:767px){

    .addon-row{
        align-items:flex-start;
    }

    .addon-left{
        width:70%;
        font-size:14px;
    }

    .addon-right{
        width:30%;
        font-size:14px;
    }
}
	@media(max-width:767px){
    .desktop-addon-section{
        display:none !important;
    }
}

@media(min-width:768px){
    .desktop-addon-section{
        display:flex !important;
    }
}
</style>
<script type="text/javascript">
    AIZ.extra.plusMinus();
</script>
