<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
</head>

<body
    style="margin:0; padding:0; background:#f2f2f0; font-family:Arial, Helvetica, sans-serif; color:#222; font-size:14px;">
    @php
        $deliveryAddress = json_decode($order->shipping_address);
        $shippingTotal = (float) $order->orderDetails->sum('shipping_cost');
        $itemsSubtotal = 0;
        $addonsSubtotal = 0;
        $companyEmail = 'sales@timetofurnish.com';
        $companyPhone = '+44 7751510365';
        $companyWebsite = 'www.timetofurnish.com';
        $emailAsset = function ($path) {
            $path = ltrim($path, '/');
            $root = app()->runningInConsole()
                ? rtrim((string) config('app.url'), '/')
                : rtrim(request()->getSchemeAndHttpHost(), '/');

            return $root . '/' . $path;
        };
        $paymentType = strtolower((string) ($order->payment_type ?? ''));
        $paymentMethod =
            str_contains($paymentType, 'stripe') || in_array($paymentType, ['card', 'card_payment', 'online_payment'])
                ? 'Card payment'
                : ucfirst(str_replace('_', ' ', $order->payment_type ?? ''));
        $sellerProfileAddress = null;
        $sellerAddressLines = [];

        $formatAddress = function ($address) {
            $lines = [];
            if (!empty($address->name)) {
                $lines[] = $address->name;
            }
            if (!empty($address->address)) {
                $lines[] = $address->address;
            }
            $cityLine = collect([
                $address->street ?? null,
                $address->city ?? null,
                $address->state ?? null,
                $address->postal_code ?? null,
            ])
                ->filter()
                ->implode(', ');
            if ($cityLine !== '') {
                $lines[] = $cityLine;
            }
            if (!empty($address->country)) {
                $lines[] = $address->country;
            }
            return $lines;
        };

        if ($order->shop && $order->shop->user && $order->shop->user->addresses->count() > 0) {
            $sellerProfileAddress = $order->shop->user->addresses->sortByDesc('set_default')->first();
        }

        if ($sellerProfileAddress && !empty($sellerProfileAddress->address)) {
            $sellerAddressLines[] = $order->shop->name ?? 'Seller';
            $sellerAddressLines[] = $sellerProfileAddress->address;
            $sellerCityLine = collect([
                $sellerProfileAddress->flat ?? null,
                $sellerProfileAddress->street ?? null,
                optional($sellerProfileAddress->city)->name,
                optional($sellerProfileAddress->state)->name,
                $sellerProfileAddress->postal_code ?? null,
            ])
                ->filter()
                ->implode(', ');
            if ($sellerCityLine !== '') {
                $sellerAddressLines[] = $sellerCityLine;
            }
            if (optional($sellerProfileAddress->country)->name) {
                $sellerAddressLines[] = optional($sellerProfileAddress->country)->name;
            }
            if (!empty($sellerProfileAddress->phone)) {
                $sellerAddressLines[] = $sellerProfileAddress->phone;
            }
            if (!empty($sellerProfileAddress->landline_no)) {
                $sellerAddressLines[] = $sellerProfileAddress->landline_no;
            }
        }
    @endphp

    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background:#f2f2f0; padding:28px 10px;">
        <tr>
            <td align="center">
                <table width="820" cellpadding="0" cellspacing="0" role="presentation"
                    style="width:820px; max-width:100%; background:#ffffff; border:1px solid #ded8d0;">
                    <tr>
                        <td style="padding:28px 34px; background:#fbfaf8; border-bottom:1px solid #e3ddd5;">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td valign="middle">
                                        <img src="{{ $emailAsset('assets/img/TTF.jpg') }}" width="128"
                                            alt="Time To Furnish" style="display:block; max-width:128px;">
                                    </td>
                                    <td align="right" valign="middle">
                                        <div style="font-size:27px; font-weight:700; color:#1f2933;">Invoice</div>
                                        <div style="font-size:13px; color:#687076; margin-top:5px;">Order
                                            {{ $order->code }}</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:26px 34px 12px;">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td width="58%" valign="top" style="padding-right:22px;">
                                        <div
                                            style="font-size:12px; color:#8a6f4d; text-transform:uppercase; font-weight:700; letter-spacing:.05em;">
                                            Invoice to</div>
                                        <div style="font-size:18px; font-weight:700; margin-top:8px; color:#111827;">
                                            {{ $deliveryAddress->name ?? '' }}</div>
                                        @foreach ($formatAddress($deliveryAddress) as $line)
                                            @if (!$loop->first)
                                                <div style="font-size:14px; line-height:1.55; color:#333;">
                                                    {{ $line }}</div>
                                            @endif
                                        @endforeach
                                        @if (!empty($deliveryAddress->email))
                                            <div style="font-size:13px; color:#555; margin-top:8px;">
                                                {{ $deliveryAddress->email }}</div>
                                        @endif
                                        @if (!empty($deliveryAddress->phone))
                                            <div style="font-size:13px; color:#555;">{{ $deliveryAddress->phone }}</div>
                                        @endif
                                    </td>
                                    <td width="42%" valign="top">
                                        <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                                            style="background:#f5efe7; border:1px solid #d7c8b8;">
                                            <tr>
                                                <td style="padding:16px 18px;">
                                                    <table width="100%" cellpadding="0" cellspacing="0"
                                                        role="presentation">
                                                        <tr>
                                                            <td style="font-size:13px; color:#6b5a45;">Payment status
                                                            </td>
                                                            <td align="right"
                                                                style="font-size:14px; font-weight:700; white-space:nowrap;">
                                                                {{ ucfirst($order->payment_status ?? '') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size:13px; color:#6b5a45; padding-top:8px;">
                                                                Invoice date</td>
                                                            <td align="right"
                                                                style="font-size:13px; padding-top:8px; white-space:nowrap;">
                                                                {{ date('d F Y', $order->date) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size:13px; color:#6b5a45; padding-top:8px;">
                                                                Payment method</td>
                                                            <td align="right"
                                                                style="font-size:13px; padding-top:8px; white-space:nowrap;">
                                                                {{ $paymentMethod }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                style="font-size:13px; color:#6b5a45; padding-top:12px; border-top:1px solid #d7c8b8;">
                                                                Total payable</td>
                                                            <td align="right"
                                                                style="font-size:18px; font-weight:700; padding-top:12px; border-top:1px solid #d7c8b8; white-space:nowrap;">
                                                                {{ single_price($order->grand_total) }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:10px 34px 20px;">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                                style="border-top:1px solid #e1ded9; border-bottom:1px solid #e1ded9;">
                                <tr>
                                    <td width="{{ !empty($sellerAddressLines) ? '33.33%' : '50%' }}" valign="top"
                                        style="padding:16px 18px 16px 0;">
                                        <div style="font-size:15px; font-weight:700; margin-bottom:8px;">Delivery
                                            address</div>
                                        @foreach ($formatAddress($deliveryAddress) as $line)
                                            <div style="font-size:12px; line-height:1.55; color:#333;">
                                                {{ $line }}</div>
                                        @endforeach
                                    </td>
                                    @if (!empty($sellerAddressLines))
                                        <td width="33.33%" valign="top" style="padding:16px 18px;">
                                            <div style="font-size:15px; font-weight:700; margin-bottom:8px;">Billing
                                                address</div>
                                            @foreach ($sellerAddressLines as $line)
                                                <div style="font-size:12px; line-height:1.55; color:#333;">
                                                    {{ $line }}</div>
                                            @endforeach
                                        </td>
                                    @endif
                                    <td width="{{ !empty($sellerAddressLines) ? '33.33%' : '50%' }}" valign="top"
                                        style="padding:16px 0 16px 18px;">
                                        <div style="font-size:15px; font-weight:700; margin-bottom:8px;">Sold by</div>
                                        <div style="font-size:12px; line-height:1.55; color:#333;">
                                            {{ $order->shop->name ?? 'Time To Furnish' }}</div>
                                        @if (!empty($sellerAddressLines))
                                            @foreach (array_slice($sellerAddressLines, 1) as $line)
                                                <div style="font-size:12px; line-height:1.55; color:#333;">
                                                    {{ $line }}</div>
                                            @endforeach
                                        @endif
                                        <div style="font-size:12px; line-height:1.55; color:#333;">{{ $companyEmail }}
                                        </div>
                                        <div style="font-size:12px; line-height:1.55; color:#333;">{{ $companyPhone }}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 34px 20px;">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td width="50%" valign="top">
                                        <div style="font-size:15px; font-weight:700; margin-bottom:8px;">Order
                                            information</div>
                                        <table cellpadding="0" cellspacing="0" role="presentation"
                                            style="font-size:12px; line-height:1.8;">
                                            <tr>
                                                <td style="color:#666; padding-right:42px;">Order date</td>
                                                <td>{{ date('d F Y', $order->date) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color:#666; padding-right:42px;">Order #</td>
                                                <td>{{ $order->code }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="50%" align="right" valign="top"
                                        style="font-size:12px; color:#666;">
                                        For support: <a href="mailto:{{ $companyEmail }}"
                                            style="color:#8a6f4d; text-decoration:none;">{{ $companyEmail }}</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 34px 28px;">
                            <div
                                style="font-size:20px; font-weight:700; padding-bottom:10px; border-bottom:2px solid #222;">
                                Invoice details</div>
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                                style="border-collapse:collapse;">
                                <tr>
                                    <th align="left"
                                        style="padding:12px 8px; font-size:12px; color:#555; font-weight:700; border-bottom:1px solid #ddd;">
                                        Description</th>
                                    <th align="center"
                                        style="padding:12px 8px; font-size:12px; color:#555; font-weight:700; border-bottom:1px solid #ddd;">
                                        Qty</th>
                                    <th align="right"
                                        style="padding:12px 8px; font-size:12px; color:#555; font-weight:700; border-bottom:1px solid #ddd; white-space:nowrap;">
                                        Unit price</th>
                                    <th align="right"
                                        style="padding:12px 8px; font-size:12px; color:#555; font-weight:700; border-bottom:1px solid #ddd; white-space:nowrap;">
                                        Subtotal</th>
                                </tr>

                                @foreach ($order->orderDetails as $orderDetail)
                                    @if ($orderDetail->product)
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
                                        <tr>
                                            <td valign="top"
                                                style="padding:13px 8px; font-size:13px; line-height:1.55; border-bottom:1px solid #ececec;">
                                                <div style="font-weight:700;">{{ $orderDetail->product->name }}</div>
                                                @if ($orderDetail->variation)
                                                    <div style="font-size:12px; color:#666; margin-top:3px;">Variant:
                                                        {{ $orderDetail->variation }}</div>
                                                @endif
                                                @if (!empty($addons))
                                                    <table width="100%" cellpadding="0" cellspacing="0"
                                                        role="presentation"
                                                        style="margin-top:8px; background:#faf8f5; border:1px solid #e5ddd3;">
                                                        @foreach ($addons as $addon)
                                                            <tr>
                                                                <td width="34%"
                                                                    style="font-size:12px; color:#6b5a45; padding:5px 8px;">
                                                                    {{ $addon['addon_name'] ?? ($addon['key'] ?? 'Addon') }}
                                                                </td>
                                                                <td width="44%"
                                                                    style="font-size:12px; color:#222; padding:5px 8px;">
                                                                    {{ $addon['name'] ?? ($addon['value'] ?? '-') }}
                                                                </td>
                                                                <td width="22%" align="right"
                                                                    style="font-size:12px; color:#222; padding:5px 8px; white-space:nowrap;">
                                                                    {{ single_price($addon['price'] ?? 0) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                @endif
                                            </td>
                                            <td align="center" valign="top"
                                                style="padding:13px 8px; font-size:13px; border-bottom:1px solid #ececec;">
                                                {{ $orderDetail->quantity }}</td>
                                            <td align="right" valign="top"
                                                style="padding:13px 8px; font-size:13px; border-bottom:1px solid #ececec; white-space:nowrap;">
                                                {{ single_price($orderDetail->quantity > 0 ? $lineBase / $orderDetail->quantity : $lineBase) }}
                                            </td>
                                            <td align="right" valign="top"
                                                style="padding:13px 8px; font-size:13px; border-bottom:1px solid #ececec; white-space:nowrap;">
                                                {{ single_price($lineBase + $lineAddon) }}</td>
                                        </tr>
                                    @endif
                                @endforeach

                                <tr>
                                    <td style="padding:11px 8px; font-size:13px;">Shipping charges</td>
                                    <td></td>
                                    <td></td>
                                    <td align="right" style="padding:11px 8px; font-size:13px; white-space:nowrap;">
                                        {{ single_price($shippingTotal) }}</td>
                                </tr>
                                @if ((float) $order->coupon_discount > 0)
                                    <tr style="background:#faf8f5;">
                                        <td style="padding:11px 8px; font-size:13px;">Promotion / coupon</td>
                                        <td></td>
                                        <td></td>
                                        <td align="right"
                                            style="padding:11px 8px; font-size:13px; white-space:nowrap;">
                                            -{{ single_price($order->coupon_discount) }}</td>
                                    </tr>
                                @endif
                            </table>

                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                                style="margin-top:8px; border-top:2px solid #222;">
                                <tr>
                                    <td width="55%"></td>
                                    <td width="45%">
                                        <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td style="padding:10px 0 4px; font-size:13px; color:#666;">Items
                                                    subtotal</td>
                                                <td align="right"
                                                    style="padding:10px 0 4px; font-size:13px; white-space:nowrap;">
                                                    {{ single_price($itemsSubtotal) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:4px 0; font-size:13px; color:#666;">Addons subtotal
                                                </td>
                                                <td align="right"
                                                    style="padding:4px 0; font-size:13px; white-space:nowrap;">
                                                    {{ single_price($addonsSubtotal) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:12px 0 0; font-size:20px; font-weight:700;">Invoice
                                                    total</td>
                                                <td align="right"
                                                    style="padding:12px 0 0; font-size:20px; font-weight:700; white-space:nowrap;">
                                                    {{ single_price($order->grand_total) }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td
                            style="padding:18px 34px 26px; background:#fbfaf8; border-top:1px solid #e3ddd5;text-align:center;">
                            <div style="font-size:12px; color:#555; line-height:1.65;">You will receive an email
                                notification once your goods have been dispatched by the seller.</div>
                            <div style="font-size:13px; font-weight:700; color:#222; margin-top:8px;">Thank you for
                                shopping with Time To Furnish.</div>
                            <table cellpadding="0" cellspacing="0" role="presentation"
                                style="margin-top:13px;width:100%">
                                <tr>
                                    <td style="font-size:12px; color:#333; padding-right:18px; white-space:nowrap;">
                                        <img src="{{ $emailAsset('assets/img/order-emails/email.jpeg') }}"
                                            width="14" height="14" alt="Email"
                                            style="vertical-align:middle;">
                                        {{ $companyEmail }}
                                    </td>
                                    <td style="font-size:12px; color:#333; padding-right:18px; white-space:nowrap;">
                                        <img src="{{ $emailAsset('assets/img/order-emails/website.jpeg') }}"
                                            width="14" height="14" alt="Website"
                                            style="vertical-align:middle;">
                                        {{ $companyWebsite }}
                                    </td>
                                    <td style="font-size:12px; color:#333; white-space:nowrap;">
                                        <img src="{{ $emailAsset('assets/img/order-emails/whatsapp.jpeg') }}"
                                            width="14" height="14" alt="Phone"
                                            style="vertical-align:middle;"> {{ $companyPhone }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
