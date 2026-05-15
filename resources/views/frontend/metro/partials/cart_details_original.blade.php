<div class="container">
    @if($carts && count($carts) > 0)
        <div class="row">
            <div class="col-xxl-8 col-xl-10 mx-auto">
                <div class="border bg-white p-3 p-lg-4 text-left">
                    <div class="mb-4">

                        <!-- Headers -->
                        <div class="row gutters-5 d-none d-lg-flex border-bottom mb-3 pb-3 text-secondary fs-12">
                            <div class="col-md-1 fw-600">#</div>
                            <div class="col-md-5 fw-600 text-black">{{ translate('Product') }}</div>
                            <div class="col fw-600">{{ translate('Price') }}</div>
                            <div class="col col-md-1 fw-600">{{ translate('Qty') }}</div>
                            <div class="col fw-600">{{ translate('Total') }}</div>
                            <div class="col-auto fw-600">{{ translate('Remove') }}</div>
                        </div>

                        <!-- Cart Items -->
                        <ul class="list-group list-group-flush">
                            @php $total = 0; $i = 1; @endphp

                            @foreach ($carts as $key => $cartItem)
                                @php
                                    $product = get_single_product($cartItem['product_id']);
                                @endphp

                                {{-- Skip cart item if product not found --}}
                                @if(!$product)
                                    @continue
                                @endif

                                @php
                                    $product_stock = null;

                                    if($cartItem['variation'] != null){
                                        $product_stock = $product->stocks
                                            ->where('variant', $cartItem['variation'])
                                            ->first();
                                    }

                                    $price = cart_product_price($cartItem, $product, false);
                                    $total += $price * $cartItem['quantity'];

                                    $product_name = $product->getTranslation('name');
                                    if ($cartItem['variation']) {
                                        $product_name .= ' - ' . $cartItem['variation'];
                                    }
                                @endphp

                                <li class="list-group-item px-0">
                                    <div class="row gutters-5 align-items-center">
                                        <div class="col-md-1 fw-600">{{$i}}</div>
                                       

                                        <!-- Product -->
                                        <div class="col-md-5 d-flex align-items-center mb-2 mb-md-0">
                                            <span class="mr-2 ml-0">
                                                <img src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                    class="img-fit size-70px"
                                                    alt="{{ $product->getTranslation('name') }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                            </span>
                                            <span class="fs-14">{{ $product_name }}</span>
                                        </div>

                                        <!-- Price -->
                                        <div class="col-md col-4 order-2 order-md-0 my-3 my-md-0">
                                            <span class="opacity-60 fs-12 d-block d-md-none">{{ translate('Price') }}</span>
                                            <span class="fw-700 fs-14">
                                                {{ cart_product_price($cartItem, $product, true, false) }}
                                            </span>
                                        </div>
                                         <!-- Quantity -->
                                        <div class="col-md-1 col order-1 order-md-0">
                                            @if ($cartItem['digital'] != 1 && $product->auction_product == 0)
                                                <div class="d-flex flex-column align-items-start aiz-plus-minus mr-2 ml-0">
                                                    <button class="btn col-auto btn-icon btn-sm btn-circle btn-light"
                                                        type="button" data-type="plus"
                                                        data-field="quantity[{{ $cartItem['id'] }}]">
                                                        <i class="las la-plus"></i>
                                                    </button>

                                                    <input type="number"
                                                        name="quantity[{{ $cartItem['id'] }}]"
                                                        class="col border-0 text-left px-0 flex-grow-1 fs-14 input-number"
                                                        value="{{ $cartItem['quantity'] }}"
                                                        min="{{ $product->min_qty ?? 1 }}"
                                                        max="{{ $product_stock?->qty ?? 1 }}"
                                                        onchange="updateQuantity({{ $cartItem['id'] }}, this)"
                                                        style="padding-left:0.75rem !important;">

                                                    <button class="btn col-auto btn-icon btn-sm btn-circle btn-light"
                                                        type="button" data-type="minus"
                                                        data-field="quantity[{{ $cartItem['id'] }}]">
                                                        <i class="las la-minus"></i>
                                                    </button>
                                                </div>
                                            @elseif($product->auction_product == 1)
                                                <span class="fw-700 fs-14">1</span>
                                            @endif
                                        </div>
                                        <!-- Total -->
                                        <div class="col-md col-5 order-4 order-md-0 my-3 my-md-0">
                                            <span class="opacity-60 fs-12 d-block d-md-none">{{ translate('Total') }}</span>
                                            <span class="fw-700 fs-16 text-primary">
                                                {{ single_price($price * $cartItem['quantity']) }}
                                            </span>
                                        </div>

                                        <!-- Remove -->
                                        <div class="col-md-auto col-6 order-5 order-md-0 text-right">
                                            <a href="javascript:void(0)"
                                                onclick="removeFromCartView(event, {{ $cartItem['id'] }})"
                                                class="btn btn-icon btn-sm btn-soft-primary bg-soft-secondary-base hov-bg-primary btn-circle">
                                                <i class="las la-trash fs-16"></i>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                @php $i++; @endphp
                            @endforeach
                        </ul>
                    </div>

                    <!-- Subtotal -->
                    <div class="px-0 py-2 mb-4 border-top d-flex justify-content-between">
                        <span class="opacity-60 fs-14">{{ translate('Subtotal') }}</span>
                        <span class="fw-700 fs-16">{{ single_price($total) }}</span>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-md-6 text-center text-md-left">
                            <a href="{{ route('home') }}" class="btn btn-link fs-14 fw-700 px-0">
                                <i class="las la-arrow-left fs-16"></i>
                                {{ translate('Return to shop') }}
                            </a>
                        </div>

                        <div class="col-md-6 text-center text-md-right">
                            @if(Auth::check())
                                <a href="{{ route('checkout.shipping_info') }}"
                                    class="btn borderbtn fs-14 fw-700 rounded-0 px-4">
                                    {{ translate('Continue to Shipping') }}
                                </a>
                            @else
                                <button class="btn borderbtn fs-14 fw-700 rounded-0 px-4"
                                    onclick="showLoginModal()">
                                    {{ translate('Continue to Shipping') }}
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
                <div class="border bg-white p-4 text-center">
                    <i class="las la-frown la-3x opacity-60 mb-3"></i>
                    <h3 class="h4 fw-700">{{ translate('Your Cart is empty') }}</h3>
                </div>
            </div>
        </div>
    @endif
</div>

<script type="text/javascript">
    AIZ.extra.plusMinus();
</script>
