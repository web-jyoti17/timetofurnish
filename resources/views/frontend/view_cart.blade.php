@extends('frontend.layouts.app')

@section('content')
    <!-- Steps -->
    <section class="pt-5 mb-4 cart_tabs">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="row gutters-5 sm-gutters-10">
                        <div class="col active">
                            <div class="text-center border border-bottom-6px p-2 text-primary">
                                <i class="la-3x mb-2 las la-shopping-bag cart-animate"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block"><a href="{{url('cart')}}">{{ translate('1. My Cart') }}</a></h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center border border-bottom-6px p-2">
                                <i class="la-3x mb-2 opacity-50 las la-map-marker"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('2. Shipping info') }}
                                </h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center border border-bottom-6px p-2">
                                <i class="la-3x mb-2 opacity-50 las la-shipping-fast"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('3. Delivery info') }}
                                </h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center border border-bottom-6px p-2">
                                <i class="la-3x mb-2 opacity-50 las la-wallet"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('4. Payment') }}</h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center border border-bottom-6px p-2">
                                <i class="la-3x mb-2 opacity-50 las la-clipboard-check"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('5. Confirmation') }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cart Details -->
    <section class="mb-4" id="cart-summary">
        @include('frontend.'.get_setting('homepage_select').'.partials.cart_details', ['carts' => $carts])
    </section>

@endsection

@section('script')
    <script type="text/javascript">
        function removeFromCartView(e, key) {
            e.preventDefault();

            // Create modal if it does not exist
            if ($('#remove-cart-modal').length === 0) {
                $('body').append(`
                    <div class="modal fade" id="remove-cart-modal" tabindex="-1" role="dialog" aria-labelledby="removeCartModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="removeCartModalLabel">{{ translate("Confirmation") }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            {{ translate("Are you sure you want to remove this item from your cart?") }}
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-dismiss="modal">{{ translate("Cancel") }}</button>
                            <button type="button" class="btn btn-primary" id="remove-cart-modal-confirm">{{ translate("Remove") }}</button>
                          </div>
                        </div>
                      </div>
                    </div>
                `);
            }

            // Set the key to a data attribute
            $('#remove-cart-modal').data('cart-key', key);

            // Prevent duplicate handlers
            $('#remove-cart-modal-confirm').off('click').on('click', function(){
                var cartKey = $('#remove-cart-modal').data('cart-key');
                $('#remove-cart-modal').modal('hide');
                removeFromCart(cartKey);
            });

            $('#remove-cart-modal').modal('show');
        }

        function updateQuantity(key, element) {
            $.post('{{ route('cart.updateQuantity') }}', {
                _token: AIZ.data.csrf,
                id: key,
                quantity: element.value
            }, function(data) {
                updateNavCart(data.nav_cart_view, data.cart_count);
                $('#cart-summary').html(data.cart_view);
            });
        }

    </script>
@endsection
