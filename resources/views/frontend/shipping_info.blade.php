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
                            <i class="la-3x mb-2 las la-map-marker cart-animate"
                                style="margin-right: -100px; transition: 2s;"></i>
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
            <div class="col-lg-8">
                <form action="{{ route('checkout.store_shipping_infostore') }}" method="POST">
                    @csrf
                    @if (Auth::check())
                    @php
                    $addresses = Auth::user()->addresses;
                    $addresses_count = $addresses->count();
                    $max_addresses = 2;
                    @endphp

                    <div class="bg-white p-3 mb-3 rounded shadow-sm">
                        @foreach ($addresses->take($max_addresses) as $key => $address)
                        <div class="border rounded p-3 mb-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                            <div class="d-flex align-items-center mb-2 mb-md-0" style="gap:12px;">
                                <input type="radio" name="address_id" value="{{ $address->id }}"
                                    @if ($address->set_default || $addresses_count == 1 || old('address_id') == $address->id) checked @endif required style="width:18px;height:18px;">
                                <div>
                                    <div><strong>{{ optional($address->country)->name }}</strong>
                                        @if ($address->set_default)
                                        <span class="badge bg-primary ms-2">{{ translate('Default') }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        {{ $address->flat ? $address->flat . ', ' : '' }}
                                        {{ $address->street ? $address->street . ', ' : '' }}
                                        {{ $address->city_id ? $address->city_id . ', ' : '' }}
                                        {{ $address->postal_code ? $address->postal_code : '' }}
                                    </div>
                                    <div>
                                        {{ $address->phone ? $address->phone : '' }}
                                        {{ $address->landline_no ? ', ' . $address->landline_no : '' }}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-row gap-2" style="gap:5px;">
                                <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary"
                                    onclick="edit_address('{{ $address->id }}')" title="{{ translate('Edit') }}">
                                    {{ translate('Edit') }}
                                </a>
                                <a href="{{ route('address.delete', $address->id) }}"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('{{ translate('Are you sure to delete this address?') }}')">
                                    {{ translate('Delete') }}
                                </a>
                            </div>
                        </div>
                        @endforeach

                        @if ($addresses_count < $max_addresses)
                            <div class="border border-dashed rounded bg-light p-3 text-center cursor-pointer mb-3" onclick="add_new_address();" style="cursor:pointer;">
                            <div class="text-primary"><i class="las la-plus la-2x"></i></div>
                            <div class="fw-semibold mb-1">{{ translate('Add New Address') }}</div>
                            <div class="text-muted" style="font-size:12px;">
                                {{ $max_addresses - $addresses_count }} {{ translate('address slots left') }}
                            </div>
                    </div>
                    @endif

                    <input type="hidden" name="checkout_type" value="logged">

                    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2" style="gap:10px;">
                        <a href="{{ route('home') }}" class="btn borderbtn py-3 px-4">
                            <i class="las la-arrow-left"></i> {{ translate('Return to shop') }}
                        </a>
                        <button type="submit" class="btn borderbtn py-3 px-5 ">
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

<style type="text/css">
    .border-dashed {
        border-style: dashed !important;
    }

    @media (max-width: 576px) {
        .d-flex.flex-md-row {
            flex-direction: column !important;
        }

        .d-flex.flex-row {
            flex-direction: row !important;
        }
    }

    .cursor-pointer {
        cursor: pointer;
    }
</style>
@endsection

@section('modal')
<!-- Address Modal -->
@include('frontend.' . get_setting('homepage_select') . '.partials.address_modal')
@endsection