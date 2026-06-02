@extends('frontend.layouts.app')

@section('content')
    <style>
        .order-confirmed-page {
            color: #20202c;
        }

        .order-confirmed-page .success-mark {
            width: 44px;
            height: 44px;
            margin: 0 auto 16px;
            border-radius: 50%;
            background: #685b4e;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 700;
        }

        .order-confirmed-page .success-title {
            display: inline-block;
            padding: 8px 14px;
            background: #dedede;
            color: #685b4e;
            font-size: 36px;
            font-weight: 700;
            line-height: 1.15;
        }

        .order-confirmed-page .success-note {
            color: #20202c;
            font-size: 16px;
            margin-top: 12px;
        }

        .order-confirmed-page .panel {
            background: #fff;
            border: 1px solid #e6e8ef;
            padding: 28px 32px;
            margin-bottom: 28px;
        }

        .order-confirmed-page .panel-title {
            color: #20202c;
            font-size: 20px;
            font-weight: 700;
            padding-bottom: 16px;
            margin-bottom: 22px;
            border-bottom: 1px solid #e6e8ef;
        }

        .order-confirmed-page .summary-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 24px 44px;
        }

        .order-confirmed-page .summary-item {
            display: grid;
            grid-template-columns: 170px minmax(0, 1fr);
            gap: 14px;
            font-size: 15px;
            line-height: 1.45;
        }

        .order-confirmed-page .summary-label {
            color: #20202c;
            font-weight: 700;
        }

        .order-confirmed-page .summary-value {
            color: #20202c;
            overflow-wrap: anywhere;
        }

        .order-confirmed-page .order-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding-bottom: 18px;
            margin-bottom: 20px;
            border-bottom: 1px solid #e6e8ef;
        }

        .order-confirmed-page .order-code {
            font-size: 22px;
            font-weight: 800;
            color: #111827;
            margin: 0;
        }

        .order-confirmed-page .order-total {
            color: #b57a45;
            font-size: 22px;
            font-weight: 800;
            white-space: nowrap;
        }

        .order-confirmed-page .product-line {
            display: grid;
            grid-template-columns: 96px minmax(0, 1fr) 130px 130px;
            gap: 18px;
            padding: 18px 0;
            border-bottom: 1px solid #edf0f4;
        }

        .order-confirmed-page .product-thumb {
            width: 96px;
            height: 96px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e6e8ef;
            background: #f7f7f7;
        }

        .order-confirmed-page .product-name {
            color: #20202c;
            font-size: 16px;
            font-weight: 700;
            line-height: 1.35;
            margin-bottom: 6px;
        }

        .order-confirmed-page .muted {
            color: #6b7280;
            font-size: 13px;
        }

        .order-confirmed-page .amount-box {
            text-align: right;
            font-size: 14px;
        }

        .order-confirmed-page .amount-label {
            display: block;
            color: #8b8b96;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .order-confirmed-page .amount-value {
            color: #20202c;
            font-weight: 700;
        }

        .order-confirmed-page .addon-list,
        .order-confirmed-page .service-list {
            margin-top: 12px;
            border: 1px solid #eadfd2;
            background: #fbf8f5;
            border-radius: 8px;
            overflow: hidden;
        }

        .order-confirmed-page .mini-row {
            display: grid;
            grid-template-columns: 36px minmax(0, 1fr) 90px;
            gap: 10px;
            align-items: center;
            padding: 9px 10px;
            border-bottom: 1px solid #eee3d8;
            font-size: 13px;
        }

        .order-confirmed-page .mini-row:last-child {
            border-bottom: 0;
        }

        .order-confirmed-page .mini-thumb {
            width: 30px;
            height: 30px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #e2d8ce;
            background: #fff;
        }

        .order-confirmed-page .mini-name {
            color: #20202c;
            font-weight: 700;
            line-height: 1.3;
        }

        .order-confirmed-page .mini-sub {
            color: #6b7280;
            font-size: 12px;
            line-height: 1.3;
        }

        .order-confirmed-page .mini-price {
            text-align: right;
            color: #20202c;
            font-weight: 700;
            white-space: nowrap;
        }

        .order-confirmed-page .section-label {
            color: #8a6f4d;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            margin: 14px 0 8px;
        }

        .order-confirmed-page .totals {
            max-width: 420px;
            margin-left: auto;
            margin-top: 24px;
            border-top: 2px solid #20202c;
            padding-top: 12px;
        }

        .order-confirmed-page .total-row {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 6px 0;
            font-size: 14px;
        }

        .order-confirmed-page .total-row strong {
            font-size: 18px;
        }

        @media (max-width: 991px) {
            .order-confirmed-page .product-line {
                grid-template-columns: 76px minmax(0, 1fr);
            }

            .order-confirmed-page .amount-box {
                text-align: left;
            }
        }

        @media (max-width: 767px) {
            .order-confirmed-page .success-title {
                font-size: 27px;
            }

            .order-confirmed-page .panel {
                padding: 22px 16px;
            }

            .order-confirmed-page .summary-grid {
                grid-template-columns: 1fr;
                gap: 14px;
            }

            .order-confirmed-page .summary-item {
                grid-template-columns: 1fr;
                gap: 3px;
            }

            .order-confirmed-page .order-head {
                align-items: flex-start;
                flex-direction: column;
            }

            .order-confirmed-page .mini-row {
                grid-template-columns: 32px minmax(0, 1fr);
            }

            .order-confirmed-page .mini-price {
                grid-column: 2;
                text-align: left;
            }
        }
    </style>

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
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('2. Shipping info') }}</h3>
                            </div>
                        </div>
                        <div class="col done">
                            <div class="text-center border border-bottom-6px p-2 text-success">
                                <i class="la-3x mb-2 las la-truck"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('3. Delivery info') }}</h3>
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
                                <i class="la-3x mb-2 las la-check-circle"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('5. Confirmation') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-4 order-confirmed-page">
        <div class="container">
            <div class="row">
                <div class="col-xl-11 mx-auto">
                    @php
                        $first_order = $combined_order->orders->first();
                        $shipping_address = !empty($first_order->shipping_address)
                            ? json_decode($first_order->shipping_address)
                            : null;

                        $formatAddress = function ($address) {
                            if (!$address) {
                                return '';
                            }

                            $parts = [];
                            foreach (['flat', 'address', 'street', 'city', 'state', 'postal_code', 'country'] as $field) {
                                if (!empty($address->{$field})) {
                                    $parts[] = $address->{$field};
                                }
                            }

                            if (empty($parts) && !empty($address->city_id)) {
                                $parts[] = $address->city_id;
                            }

                            return implode(', ', $parts);
                        };

                        $additionalInfoFor = function ($order) {
                            $decoded = json_decode($order->additional_info ?? '', true);

                            return is_array($decoded)
                                ? $decoded
                                : ['note' => $order->additional_info, 'services' => [], 'service_total' => 0];
                        };

                        $addonImageSrc = function ($addon) {
                            $image = $addon['image'] ?? ($addon['img'] ?? ($addon['image_url'] ?? ''));

                            if (empty($image)) {
                                static $globalAddonImageMap = null;
                                if ($globalAddonImageMap === null) {
                                    $globalAddonImageMap = \App\Models\ProductAddonGlobal::with('options')
                                        ->get()
                                        ->flatMap(function ($globalAddon) {
                                            return $globalAddon->options
                                                ->filter(function ($option) {
                                                    return !empty($option->img);
                                                })
                                                ->mapWithKeys(function ($option) use ($globalAddon) {
                                                    $key = strtolower(trim($globalAddon->name)) . '|' . strtolower(trim($option->option_name));

                                                    return [$key => $option->img];
                                                });
                                        });
                                }

                                $fallbackKey = strtolower(trim($addon['addon_name'] ?? ($addon['key'] ?? ''))) . '|' . strtolower(trim($addon['name'] ?? ($addon['value'] ?? '')));
                                $image = $globalAddonImageMap[$fallbackKey] ?? '';
                            }

                            if (empty($image)) {
                                return '';
                            }

                            if (\Illuminate\Support\Str::startsWith($image, ['http://', 'https://', 'data:'])) {
                                return $image;
                            }

                            return asset(ltrim($image, '/'));
                        };

                        $paymentDetails = json_decode($first_order->payment_details ?? '');
                        $paymentMethod = ucfirst(str_replace('_', ' ', $first_order->payment_type ?? ''));
                        if (!empty($paymentDetails->mode) && strtolower((string) $paymentDetails->mode) === 'mock') {
                            $paymentMethod = 'Mock';
                        } elseif (strtolower((string) $first_order->payment_type) === 'stripe') {
                            $paymentMethod = 'Card';
                        }
                    @endphp

                    <div class="text-center py-1 mb-4">
                        <div class="success-mark">&check;</div>
                        <h1 class="success-title">{{ translate('Thank You for Your Order!') }}</h1>
                        <p class="success-note">
                            {{ translate('A copy of your order summary has been sent to') }}
                            <strong>{{ $shipping_address->email ?? '' }}</strong>
                        </p>
                    </div>

                    <div class="panel">
                        <h5 class="panel-title">{{ translate('Order Summary') }}</h5>
                        <div class="summary-grid">
                            <div class="summary-item">
                                <div class="summary-label">{{ translate('Order date') }}</div>
                                <div class="summary-value">{{ date('d-m-Y h:i A', $first_order->date) }}</div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-label">{{ translate('Order status') }}</div>
                                <div class="summary-value">{{ translate(ucfirst(str_replace('_', ' ', $first_order->payment_status))) }}</div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-label">{{ translate('Name') }}</div>
                                <div class="summary-value">{{ $shipping_address->name ?? '' }}</div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-label">{{ translate('Payment method') }}</div>
                                <div class="summary-value">{{ translate($paymentMethod) }}</div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-label">{{ translate('Email') }}</div>
                                <div class="summary-value">{{ $shipping_address->email ?? '' }}</div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-label">{{ translate('Mobile number') }}</div>
                                <div class="summary-value">{{ $shipping_address->phone ?? '' }}</div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-label">{{ translate('Shipping address') }}</div>
                                <div class="summary-value">{{ $formatAddress($shipping_address) }}</div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-label">{{ translate('Landline number') }}</div>
                                <div class="summary-value">{{ $shipping_address->landline_no ?? '' }}</div>
                            </div>
                        </div>
                    </div>

                    @foreach ($combined_order->orders as $order)
                        @php
                            $additionalInfo = $additionalInfoFor($order);
                            $services = $additionalInfo['services'] ?? [];
                            $servicesTotal = (float) ($additionalInfo['service_total'] ?? collect($services)->sum('price'));
                            $itemsSubtotal = 0;
                            $addonsSubtotal = 0;
                            $shippingTotal = (float) $order->orderDetails->sum('shipping_cost');
                            $taxTotal = (float) $order->orderDetails->sum('tax');
                        @endphp

                        <div class="panel">
                            <div class="order-head">
                                <h2 class="order-code">{{ translate('Order Id') }} {{ $order->code }}</h2>
                                <div class="order-total">{{ single_price($order->grand_total) }}</div>
                            </div>

                            @foreach ($order->orderDetails as $key => $orderDetail)
                                @php
                                    $lineBase = (float) $orderDetail->price;
                                    $lineAddon = (float) ($orderDetail->addon_price ?? 0);
                                    $itemsSubtotal += $lineBase;
                                    $addonsSubtotal += $lineAddon;
                                    $addons = [];

                                    if (!empty($orderDetail->addons)) {
                                        $addons = json_decode($orderDetail->addons, true) ?: [];
                                    } elseif (!empty($orderDetail->addon)) {
                                        $addons = json_decode($orderDetail->addon, true) ?: [];
                                    }
                                @endphp

                                <div class="product-line">
                                    <div>
                                        @if ($orderDetail->product)
                                            <img src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}"
                                                class="product-thumb"
                                                alt="{{ $orderDetail->product->getTranslation('name') }}"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                        @else
                                            <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                class="product-thumb"
                                                alt="{{ translate('Product Unavailable') }}">
                                        @endif
                                    </div>

                                    <div>
                                        @if ($orderDetail->product)
                                            <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank" class="product-name d-block">
                                                {{ $orderDetail->product->getTranslation('name') }}
                                            </a>
                                        @else
                                            <div class="product-name">{{ translate('Product Unavailable') }}</div>
                                        @endif

                                        @if ($orderDetail->variation)
                                            <div class="muted">{{ translate('Variant') }}: {{ $orderDetail->variation }}</div>
                                        @endif
                                        <div class="muted">{{ translate('Delivery') }}: {{ translate('Home Delivery') }}</div>

                                        @if (!empty($addons))
                                            <div class="section-label">{{ translate('Selected add-ons') }}</div>
                                            <div class="addon-list">
                                                @foreach ($addons as $addon)
                                                    @php
                                                        $addonImage = $addonImageSrc($addon);
                                                    @endphp
                                                    <div class="mini-row">
                                                        <div>
                                                            @if ($addonImage)
                                                                <img src="{{ $addonImage }}"
                                                                    class="mini-thumb"
                                                                    alt="{{ $addon['name'] ?? ($addon['value'] ?? 'Addon') }}">
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <div class="mini-name">{{ $addon['addon_name'] ?? ($addon['key'] ?? 'Addon') }}</div>
                                                            <div class="mini-sub">{{ $addon['name'] ?? ($addon['value'] ?? '-') }}</div>
                                                        </div>
                                                        <div class="mini-price">{{ single_price($addon['price'] ?? 0) }}</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <div class="amount-box">
                                        <span class="amount-label">{{ translate('Quantity') }}</span>
                                        <span class="amount-value">{{ $orderDetail->quantity }}</span>
                                    </div>

                                    <div class="amount-box">
                                        <span class="amount-label">{{ translate('Subtotal') }}</span>
                                        <span class="amount-value">{{ single_price($lineBase + $lineAddon) }}</span>
                                    </div>
                                </div>
                            @endforeach

                            @if (!empty($services))
                                <div class="section-label">{{ translate('Additional Services') }}</div>
                                <div class="service-list">
                                    @foreach ($services as $service)
                                        <div class="mini-row">
                                            <div></div>
                                            <div>
                                                <div class="mini-name">{{ $service['name'] ?? translate('Service') }}</div>
                                                @if (!empty($service['type']))
                                                    <div class="mini-sub">{{ ucfirst($service['type']) }}</div>
                                                @endif
                                            </div>
                                            <div class="mini-price">{{ single_price($service['price'] ?? 0) }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="totals">
                                <div class="total-row">
                                    <span>{{ translate('Items subtotal') }}</span>
                                    <span>{{ single_price($itemsSubtotal) }}</span>
                                </div>
                                <div class="total-row">
                                    <span>{{ translate('Add-ons subtotal') }}</span>
                                    <span>{{ single_price($addonsSubtotal) }}</span>
                                </div>
                                <div class="total-row">
                                    <span>{{ translate('Shipping charges') }}</span>
                                    <span>{{ single_price($shippingTotal) }}</span>
                                </div>
                                @if ($servicesTotal > 0)
                                    <div class="total-row">
                                        <span>{{ translate('Additional services') }}</span>
                                        <span>{{ single_price($servicesTotal) }}</span>
                                    </div>
                                @endif
                                <div class="total-row">
                                    <span>{{ translate('VAT') }}</span>
                                    <span>{{ single_price($taxTotal) }}</span>
                                </div>
                                <div class="total-row">
                                    <span>{{ translate('Coupon Discount') }}</span>
                                    <span>{{ single_price($order->coupon_discount) }}</span>
                                </div>
                                <div class="total-row">
                                    <strong>{{ translate('Total') }}</strong>
                                    <strong>{{ single_price($order->grand_total) }}</strong>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
