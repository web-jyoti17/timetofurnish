<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            background: #f2f2f0;
            color: #222;
            font-family: roboto, Arial, Helvetica, sans-serif;
            font-size: 11px;
            line-height: 1.45;
        }

        table {
            border-collapse: collapse;
            table-layout: fixed;
        }

        td,
        th {
            vertical-align: top;
        }
    </style>
</head>

<body>
    @php
        $deliveryAddress = json_decode($order->shipping_address);
        $shippingTotal = (float) $order->orderDetails->sum('shipping_cost');
        $itemsSubtotal = 0;
        $addonsSubtotal = 0;
        $companyEmail = 'sales@timetofurnish.com';
        $companyPhone = '+44 7751510365';
        $companyWebsite = 'www.timetofurnish.com';
        $invoiceCopy = $invoiceCopy ?? \App\Services\OrderInvoiceService::copyTypes()[$invoiceCopyType ?? 'customer'];
        $invoiceNumber = $invoiceNumber ?? app(\App\Services\OrderInvoiceService::class)->invoiceNumber($order);
        $invoiceName = $invoiceName ?? $invoiceCopy['name'];
        $invoiceGeneratedAt = $invoiceGeneratedAt ?? now();
        $assetPath = function ($path) {
            $absolutePath = public_path($path);
            if (!is_file($absolutePath)) {
                return '';
            }
            $mime = mime_content_type($absolutePath) ?: 'image/jpeg';
            return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($absolutePath));
        };
        $formatAddress = function ($address) {
            if (!$address) {
                return [];
            }
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
        $paymentType = strtolower((string) ($order->payment_type ?? ''));
        $paymentMethod =
            str_contains($paymentType, 'stripe') || in_array($paymentType, ['card', 'card_payment', 'online_payment'])
                ? 'Card payment'
                : ucfirst(str_replace('_', ' ', $order->payment_type ?? ''));
        $sellerAddressLines = [];
        $sellerProfileAddress = null;
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
        $addressColumnWidth = !empty($sellerAddressLines) ? '33.33%' : '50%';
    @endphp

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f2f2f0;">
        <tr>
            <td align="center" style="padding:18px 0;">
                <table width="680" cellpadding="0" cellspacing="0" style="background:#fff;border:1px solid #ded8d0;">
                    <tr>
                        <td style="padding:24px;background:#fbfaf8;border-bottom:1px solid #e3ddd5;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td width="50%" align="left" style="vertical-align:start;">
                                        <img src="{{ $assetPath('assets/img/TTF.jpg') }}" width="128"
                                            style="width:128px;height:auto;">
                                    </td>
                                    <td width="50%" align="right" style="vertical-align:middle;text-align:right;">
                                        <span
                                            style="background:{{ $invoiceCopy['color'] }};color:#fff;font-size:11px;font-weight:bold;padding:7px 11px;text-transform:uppercase;width:100px;height:100px;display:block;">{{ $invoiceCopy['label'] }}</span>
                                        <div
                                            style="font-size:26px;font-weight:bold;line-height:1.2;color:{{ $invoiceCopy['color'] }};margin-top:9px;">
                                            Invoice</div>
                                        @if (($invoiceCopyType ?? '') === \App\Services\OrderInvoiceService::ADMIN)
                                            <div class="muted"
                                                style="font-size:9px;margin-top:6px;color:#000;font-weight:900;">
                                                For office use only
                                            </div>
                                        @endif

                                        <div style="font-size:11px;color:#687076;margin-top:3px;">Order
                                            {{ $order->code }}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:24px 24px 12px;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td width="56%" style="padding-right:22px;text-align:left;">
                                        <div
                                            style="font-size:10px;color:#8a6f4d;text-transform:uppercase;font-weight:bold;">
                                            Invoice to</div>
                                        <div style="font-size:17px;font-weight:bold;margin-top:7px;color:#111827;">
                                            {{ $deliveryAddress->name ?? '' }}</div>
                                        @foreach ($formatAddress($deliveryAddress) as $line)
                                            @if (!$loop->first)
                                                <div style="font-size:12px;line-height:1.55;">{{ $line }}</div>
                                            @endif
                                        @endforeach
                                        @if (!empty($deliveryAddress->email))
                                            <div style="font-size:11px;color:#0052cc;margin-top:7px;">
                                                {{ $deliveryAddress->email }}</div>
                                        @endif
                                        @if (!empty($deliveryAddress->phone))
                                            <div style="font-size:11px;color:#555;">{{ $deliveryAddress->phone }}</div>
                                        @endif
                                    </td>
                                    <td width="44%" style="text-align:left;">
                                        <table width="100%" cellpadding="0" cellspacing="0"
                                            style="background:#f5efe7;border:1px solid #d7c8b8;">
                                            <tr>
                                                <td style="padding:14px 16px;">
                                                    <table width="100%" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td style="font-size:11px;color:#6b5a45;padding:4px 0;">
                                                                Payment
                                                                status</td>
                                                            <td align="right"
                                                                style="font-size:11px;font-weight:bold;padding:4px 0;">
                                                                {{ ucfirst($order->payment_status ?? '') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size:11px;color:#6b5a45;padding:4px 0;">
                                                                Invoice
                                                                no.</td>
                                                            <td align="right" style="font-size:11px;padding:4px 0;">
                                                                {{ $invoiceNumber }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size:11px;color:#6b5a45;padding:4px 0;">
                                                                Invoice
                                                                date</td>
                                                            <td align="right" style="font-size:11px;padding:4px 0;">
                                                                {{ date('d F Y', $order->date) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size:11px;color:#6b5a45;padding:4px 0;">
                                                                Generated
                                                                at</td>
                                                            <td align="right" style="font-size:11px;padding:4px 0;">
                                                                {{ \Carbon\Carbon::parse($invoiceGeneratedAt)->format('d F Y, h:i A') }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size:11px;color:#6b5a45;padding:4px 0;">
                                                                Payment
                                                                method</td>
                                                            <td align="right" style="font-size:11px;padding:4px 0;">
                                                                {{ $paymentMethod }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                style="font-size:11px;color:#6b5a45;padding-top:10px;border-top:1px solid #d7c8b8;">
                                                                Total payable</td>
                                                            <td align="right"
                                                                style="font-size:16px;font-weight:bold;padding-top:10px;border-top:1px solid #d7c8b8;">
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
                        <td style="padding:8px 24px 18px;">
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="border-top:1px solid #e1ded9;border-bottom:1px solid #e1ded9;">
                                <tr>
                                    <td width="{{ $addressColumnWidth }}"
                                        style="padding:14px 16px 14px 0;text-align:left;">
                                        <div style="font-size:13px;font-weight:bold;margin-bottom:7px;">Delivery address
                                        </div>
                                        @foreach ($formatAddress($deliveryAddress) as $line)
                                            <div style="font-size:11px;line-height:1.55;">{{ $line }}</div>
                                        @endforeach
                                    </td>
                                    @if (!empty($sellerAddressLines))
                                        <td width="33.33%" style="padding:14px 16px;text-align:left;">
                                            <div style="font-size:13px;font-weight:bold;margin-bottom:7px;">Billing
                                                address
                                            </div>
                                            @foreach ($sellerAddressLines as $line)
                                                <div style="font-size:11px;line-height:1.55;">{{ $line }}</div>
                                            @endforeach
                                        </td>
                                    @endif
                                    <td width="{{ $addressColumnWidth }}"
                                        style="padding:14px 0 14px 16px;text-align:left;">
                                        <div style="font-size:13px;font-weight:bold;margin-bottom:7px;">Sold by</div>
                                        <div style="font-size:11px;line-height:1.55;">
                                            {{ $order->shop->name ?? 'Time To Furnish' }}</div>
                                        @foreach (array_slice($sellerAddressLines, 1) as $line)
                                            <div style="font-size:11px;line-height:1.55;">{{ $line }}</div>
                                        @endforeach
                                        <div style="font-size:11px;line-height:1.55;color:#0052cc;">{{ $companyEmail }}
                                        </div>
                                        <div style="font-size:11px;line-height:1.55;">{{ $companyPhone }}</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 24px 18px;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td width="50%" align="left" style="text-align:left;">
                                        <div style="font-size:13px;font-weight:bold;margin-bottom:7px;text-align:left;">
                                            Order information
                                        </div>
                                        <table width="100%" cellpadding="0" cellspacing="0" align="left"
                                            style="text-align:left;">
                                            <tr>
                                                <td width="20%" align="left"
                                                    style="font-size:11px;color:#666;padding:2px 0;text-align:left;">
                                                    Order date
                                                </td>
                                                <td width="80%" align="left"
                                                    style="font-size:11px;padding:2px 0;text-align:left;">
                                                    {{ date('d F Y', $order->date) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%" align="left"
                                                    style="font-size:11px;color:#666;padding:2px 0;text-align:left;">
                                                    Order #</td>
                                                <td width="80%" align="left"
                                                    style="font-size:11px;padding:2px 0;text-align:left;">
                                                    {{ $order->code }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="50%" align="right"
                                        style="font-size:11px;color:#666;text-align:right;">
                                        For support: <span style="color:#8a6f4d;">{{ $companyEmail }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 24px 26px;">
                            <div
                                style="font-size:18px;font-weight:bold;padding-bottom:9px;border-bottom:2px solid #222;text-align:left;">
                                Invoice details</div>
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <th align="left"
                                        style="width:52%;font-size:11px;color:#555;padding:10px 7px;border-bottom:1px solid #ddd;">
                                        Description</th>
                                    <th align="center"
                                        style="width:10%;font-size:11px;color:#555;padding:10px 7px;border-bottom:1px solid #ddd;">
                                        Qty</th>
                                    <th align="right"
                                        style="width:18%;font-size:11px;color:#555;padding:10px 7px;border-bottom:1px solid #ddd;">
                                        Unit price</th>
                                    <th align="right"
                                        style="width:20%;font-size:11px;color:#555;padding:10px 7px;border-bottom:1px solid #ddd;">
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
                                            <td
                                                style="font-size:12px;padding:11px 7px;border-bottom:1px solid #ececec;">
                                                <div style="font-weight:bold;">{{ $orderDetail->product->name }}</div>
                                                @if ($orderDetail->variation)
                                                    <div style="font-size:11px;color:#666;margin-top:2px;">Variant:
                                                        {{ $orderDetail->variation }}</div>
                                                @endif
                                                @if (!empty($addons))
                                                    <table width="100%" cellpadding="0" cellspacing="0"
                                                        style="margin-top:7px;background:#faf8f5;border:1px solid #e5ddd3;">
                                                        @foreach ($addons as $addon)
                                                            <tr>
                                                                <td width="34%"
                                                                    style="font-size:10px;color:#6b5a45;padding:4px 6px;">
                                                                    {{ $addon['addon_name'] ?? ($addon['key'] ?? 'Addon') }}
                                                                </td>
                                                                <td width="44%"
                                                                    style="font-size:10px;padding:4px 6px;">
                                                                    {{ $addon['name'] ?? ($addon['value'] ?? '-') }}
                                                                </td>
                                                                <td width="22%" align="right"
                                                                    style="font-size:10px;padding:4px 6px;">
                                                                    {{ single_price($addon['price'] ?? 0) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                @endif
                                            </td>
                                            <td align="center"
                                                style="font-size:12px;padding:11px 7px;border-bottom:1px solid #ececec;">
                                                {{ $orderDetail->quantity }}</td>
                                            <td align="right"
                                                style="font-size:12px;padding:11px 7px;border-bottom:1px solid #ececec;">
                                                {{ single_price($orderDetail->quantity > 0 ? $lineBase / $orderDetail->quantity : $lineBase) }}
                                            </td>
                                            <td align="right"
                                                style="font-size:12px;padding:11px 7px;border-bottom:1px solid #ececec;">
                                                {{ single_price($lineBase + $lineAddon) }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                <tr>
                                    <td style="font-size:12px;padding:11px 7px;border-bottom:1px solid #ececec;">
                                        Shipping
                                        charges</td>
                                    <td style="border-bottom:1px solid #ececec;"></td>
                                    <td style="border-bottom:1px solid #ececec;"></td>
                                    <td align="right"
                                        style="font-size:12px;padding:11px 7px;border-bottom:1px solid #ececec;">
                                        {{ single_price($shippingTotal) }}</td>
                                </tr>
                                @if ((float) $order->coupon_discount > 0)
                                    <tr>
                                        <td
                                            style="font-size:12px;padding:11px 7px;border-bottom:1px solid #ececec;background:#faf8f5;">
                                            Promotion / coupon</td>
                                        <td style="border-bottom:1px solid #ececec;background:#faf8f5;"></td>
                                        <td style="border-bottom:1px solid #ececec;background:#faf8f5;"></td>
                                        <td align="right"
                                            style="font-size:12px;padding:11px 7px;border-bottom:1px solid #ececec;background:#faf8f5;">
                                            -{{ single_price($order->coupon_discount) }}</td>
                                    </tr>
                                @endif
                            </table>

                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="margin-top:8px;border-top:2px solid #222;">
                                <tr>
                                    <td width="56%"></td>
                                    <td width="44%">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="font-size:12px;color:#666;padding:5px 0;">Items subtotal
                                                </td>
                                                <td align="right" style="font-size:12px;padding:5px 0;">
                                                    {{ single_price($itemsSubtotal) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:12px;color:#666;padding:5px 0;">Addons subtotal
                                                </td>
                                                <td align="right" style="font-size:12px;padding:5px 0;">
                                                    {{ single_price($addonsSubtotal) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:12px;color:#666;padding:5px 0;">Shipping charges
                                                </td>
                                                <td align="right" style="font-size:12px;padding:5px 0;">
                                                    {{ single_price($shippingTotal) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:18px;font-weight:bold;padding-top:11px;">Invoice
                                                    total
                                                </td>
                                                <td align="right"
                                                    style="font-size:18px;font-weight:bold;padding-top:11px;">
                                                    {{ single_price($order->grand_total) }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center"
                            style="padding:16px 24px 22px;border-top:1px solid #e3ddd5;text-align:center;">
                            <div style="font-size:11px;color:#555;line-height:1.6;">You will receive an email
                                notification
                                once your goods have been dispatched by the seller.</div>
                            <div style="font-size:12px;font-weight:bold;margin-top:7px;">Thank you for shopping with
                                Time
                                To Furnish.</div>
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:20px;">
                                <tr>
                                    <td width="33.33%" align="left"
                                        style="font-size:11px;text-align:left;padding:0 8px;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td width="40" align="right"
                                                    style="padding-right:10px;vertical-align:middle;">
                                                    <img src="{{ $assetPath('assets/img/email.jpeg') }}"
                                                        width="32" style="width:32px;height:auto;">
                                                </td>
                                                <td align="left"
                                                    style="font-size:11px;vertical-align:middle;text-align:left;">
                                                    {{ $companyEmail }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="33.33%" align="left"
                                        style="font-size:11px;text-align:left;padding:0 8px;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td width="40" align="right"
                                                    style="padding-right:10px;vertical-align:middle;">
                                                    <img src="{{ $assetPath('assets/img/website.jpeg') }}"
                                                        width="32" style="width:32px;height:auto;">
                                                </td>
                                                <td align="left"
                                                    style="font-size:11px;vertical-align:middle;text-align:left;">
                                                    {{ $companyWebsite }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="33.33%" align="left"
                                        style="font-size:11px;text-align:left;padding:0 8px;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td width="40" align="right"
                                                    style="padding-right:10px;vertical-align:middle;">
                                                    <img src="{{ $assetPath('assets/img/whatsapp.jpeg') }}"
                                                        width="32" style="width:32px;height:auto;">
                                                </td>
                                                <td align="left"
                                                    style="font-size:11px;vertical-align:middle;text-align:left;white-space:nowrap;">
                                                    {{ $companyPhone }}
                                                </td>
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
    </table>
</body>

</html>
