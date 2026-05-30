@extends('frontend.layouts.app')

@section('content')

    {{-- !-- Steps --> --}}
    <section class="pt-5 mb-4  cart_tabs">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="row gutters-5 sm-gutters-10">
                        <div class="col done">
                            <div class="text-center border border-bottom-6px p-2 text-success">
                                <i class="la-3x mb-2 las la-shopping-bag"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block"><a
                                        href="{{ url('cart') }}">{{ translate('1. My Cart') }}</a></h3>
                            </div>
                        </div>
                        <div class="col active">
                            <div class="text-center border border-bottom-6px p-2 text-primary">
                                <i class="la-3x mb-2 las la-map-marker cart-animate"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('2. Shipping info') }}
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


    <!-- Shipping Info Simple Style -->
    <section class="mb-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <form action="{{ route('checkout.store_shipping_infostore') }}" method="POST">
                        @csrf
                        @if (Auth::check())
                            @php
                                $addresses = Auth::user()->addresses;
                                $addresses_count = $addresses->count();
                                $max_addresses = 2;
                            @endphp

                            <div class="bg-white p-2 mb-3 rounded-3 shadow-sm border">
                                <!-- Fallback card for when there are no addresses at all -->
                                @if ($addresses_count == 0)
                                    <div class="address-card border border-dashed rounded-3 p-4 text-center mb-3 cursor-pointer"
                                         onclick="add_new_address();" style="cursor: pointer; background: #faf8f5;">
                                        <div class="action-icon-btn add-btn mb-2">
                                            <i class="las la-plus"></i>
                                        </div>
                                        <div class="fw-semibold text-dark fs-15">{{ translate('No Address Saved') }}</div>
                                        <div class="text-muted fs-12">{{ translate('Click here to add your first shipping address.') }}</div>
                                    </div>
                                @endif

                                <!-- Vertical stacked layout for address cards as shown in the screenshot -->
                                <div class="address-container">
                                    @foreach ($addresses->take($max_addresses) as $key => $address)
                                        <div class="address-card border rounded-3 p-4 mb-3 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between position-relative @if ($address->set_default || $addresses_count == 1 || old('address_id') == $address->id) active-address @endif"
                                             onclick="select_address_card('address_{{ $address->id }}')"
                                             style="cursor: pointer; transition: all 0.25s ease;">
                                            
                                            <div class="d-flex align-items-start flex-grow-1" style="gap: 16px; margin-right: 15px;">
                                                <input type="radio" id="address_{{ $address->id }}" name="address_id" value="{{ $address->id }}"
                                                    @if ($address->set_default || $addresses_count == 1 || old('address_id') == $address->id) checked @endif required
                                                    style="width: 22px; height: 22px; accent-color: #685b4e; cursor: pointer; margin-top: 3px; flex-shrink: 0;">
                                                
                                                <div class="address-info flex-grow-1">
                                                    <div class="d-flex align-items-center flex-wrap gap-2 mb-2" style="gap: 6px;">
                                                        <strong class="fs-16 text-dark" style="font-family: 'Poppins', sans-serif;">{{ optional($address->country)->name }}</strong>
                                                        @if ($address->set_default)
                                                            <span class="badge px-2 py-1 fs-11" style="background: #FAF4EE; color: #8b5e34; border: 1px solid #EADDCF; font-weight: 600; border-radius: 4px;">{{ translate('Default') }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="fs-14 text-muted mb-2 line-height-1_6" style="color: #736d66 !important;">
                                                        {{ $address->flat ? $address->flat . ', ' : '' }}
                                                        {{ $address->street ? $address->street . ', ' : '' }}
                                                        {{ $address->city_id ? $address->city_id . ', ' : '' }}
                                                        {{ $address->postal_code ? $address->postal_code : '' }}
                                                    </div>
                                                    <div class="fs-14 fw-600 text-dark d-flex align-items-center" style="gap: 6px;">
                                                        <i class="las la-phone text-muted fs-16"></i>
                                                        <span>{{ $address->phone ? $address->phone : '' }}</span>
                                                        @if($address->landline_no)
                                                            <span class="text-muted" style="margin: 0 4px;">|</span>
                                                            <span>{{ $address->landline_no }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Action panel: Edit, Plus, Delete Icon buttons in a single row on desktop, wrapping nicely on mobile -->
                                            <div class="d-flex flex-row align-items-center justify-content-end mt-3 mt-sm-0 flex-shrink-0" style="gap: 8px;" onclick="event.stopPropagation();">
                                                <a href="javascript:void(0)" class="action-icon-btn edit-btn"
                                                    onclick="edit_address('{{ $address->id }}')"
                                                    title="{{ translate('Edit') }}">
                                                    <i class="las la-edit"></i>
                                                </a>
                                                @if ($addresses_count < $max_addresses)
                                                    <a href="javascript:void(0)" class="action-icon-btn plus-btn"
                                                        onclick="add_new_address()"
                                                        title="{{ translate('Add Address') }}">
                                                        <i class="las la-plus"></i>
                                                    </a>
                                                @endif
                                                <a href="javascript:void(0)"
                                                    class="action-icon-btn delete-btn"
                                                    onclick="confirm_modal('{{ route('address.delete', $address->id) }}')"
                                                    title="{{ translate('Delete') }}">
                                                    <i class="las la-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <input type="hidden" name="checkout_type" value="logged">

                                <div class="d-flex justify-content-between align-items-center mt-3 gap-2 shipping-info"
                                    style="gap:10px;">
                                    <a href="{{ url('cart') }}"
                                        class="btn borderbtn rounded-2 py-3 px-4 w-50 w-lg-auto custom_checkout_button_design filled">
                                        <i class="las la-arrow-left"></i> {{ translate('Back') }}
                                    </a>
                                    <button type="submit"
                                        class="btn borderbtn py-3 px-5 w-50 w-lg-auto custom_checkout_button_design unfilled rounded-2">
                                        {{ translate('Next') }}
                                    </button>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Custom CSS Styles for address elements -->
    <style type="text/css">
        .border-dashed {
            border-style: dashed !important;
            border-width: 2px !important;
            border-color: #EADDCF !important;
        }

        .address-card {
            border: 1.5px solid #EADDCF;
            background: #ffffff;
            border-radius: 16px !important;
            padding: 24px !important;
        }

        .address-card:hover {
            border-color: #685b4e !important;
            box-shadow: 0 4px 16px rgba(104, 91, 78, 0.05) !important;
        }

        .address-card.active-address {
            border-color: #685b4e !important;
            background-color: #FAF4EE !important;
            box-shadow: 0 6px 20px rgba(104, 91, 78, 0.08) !important;
        }

        /* Styling for edit, delete, and add action buttons matching the screen shot */
        .action-icon-btn {
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #FAF4EE;
            border: 1px solid #EADDCF;
            border-radius: 12px;
            color: #8B5E34;
            font-size: 20px;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
            text-decoration: none !important;
        }

        .action-icon-btn:hover {
            background-color: #8B5E34;
            color: #ffffff !important;
            border-color: #8B5E34;
        }

        .action-icon-btn.add-btn {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background-color: #FAF4EE;
            border-color: #EADDCF;
            font-size: 22px;
            color: #8B5E34;
        }

        .action-icon-btn.add-btn:hover {
            background-color: #8B5E34;
            color: #ffffff !important;
        }

        .modal-dialog{
                top:auto !important;
            }
        @media (max-width: 576px) {
            .address-card {
                padding: 16px !important;
            }

            .action-icon-btn {
                width: 38px;
                height: 38px;
                font-size: 18px;
                border-radius: 10px;
            }

            .action-icon-btn.add-btn {
                width: 42px;
                height: 42px;
                font-size: 20px;
            }
        }

        .cursor-pointer {
            cursor: pointer;
        }
    </style>

    <script type="text/javascript">
        // Helper function to allow selecting a card by clicking on its container
        function select_address_card(radioId) {
            var radio = document.getElementById(radioId);
            if (radio) {
                radio.checked = true;
                
                // Remove active class from all sibling cards, then apply to current card
                $('.address-card').removeClass('active-address');
                $(radio).closest('.address-card').addClass('active-address');
            }
        }
    </script>
@endsection

@section('modal')
    <!-- Address Modal -->
    @include('frontend.' . get_setting('homepage_select') . '.partials.address_modal')
@endsection
