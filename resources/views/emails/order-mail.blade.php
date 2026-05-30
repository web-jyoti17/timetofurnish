<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            background: #f2f2f0;
            color: #222222;
            font-family: Roboto, Arial, Helvetica, sans-serif;
            font-size: 12px;
            line-height: 1.45;
            text-align: left;
        }

        table {
            border-collapse: collapse;
            table-layout: fixed;
            text-align: left;
        }

        td,
        th {
            vertical-align: top;
        }

        .page {
            width: 100%;
            background: #f2f2f0;
            padding: 18px 0;
        }

        .invoice {
            width: 100%;
            max-width: 680px;
            background: #ffffff;
            border: 1px solid #ded8d0;
        }

        .section {
            padding-left: 24px;
            padding-right: 24px;
        }

        .header {
            background: #fbfaf8;
            border-bottom: 1px solid #e3ddd5;
            padding-top: 24px;
            padding-bottom: 22px;
        }

        .copy-badge {
            display: inline-block;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 7px 11px;
            text-transform: uppercase;
        }

        .invoice-title {
            font-size: 25px;
            line-height: 1.15;
            font-weight: 700;
            margin-top: 9px;
        }

        .muted {
            color: #666666;
        }

        .label {
            color: #8a6f4d;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .h1 {
            color: #111827;
            font-size: 17px;
            font-weight: 700;
            line-height: 1.25;
            margin-top: 7px;
        }

        .h2 {
            color: #222222;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 7px;
        }

        .info-box {
            background: #f5efe7;
            border: 1px solid #d7c8b8;
        }

        .info-box td {
            padding: 5px 0;
            font-size: 11px;
        }

        .address-row {
            border-top: 1px solid #e1ded9;
            border-bottom: 1px solid #e1ded9;
        }

        .address-row td {
            padding-top: 14px;
            padding-bottom: 14px;
        }

        .details-title {
            color: #222222;
            font-size: 18px;
            font-weight: 700;
            padding-bottom: 9px;
            border-bottom: 2px solid #222222;
        }

        .items th {
            color: #555555;
            font-size: 11px;
            font-weight: 700;
            padding: 10px 7px;
            border-bottom: 1px solid #dddddd;
        }

        .items td {
            font-size: 12px;
            padding: 11px 7px;
            border-bottom: 1px solid #ececec;
        }

        .addons {
            background: #faf8f5;
            border: 1px solid #e5ddd3;
            margin-top: 7px;
            width: 100%;
            table-layout: fixed;
        }

        .addons td {
            border-bottom: 0;
            font-size: 10px;
            padding: 4px 6px;
        }

        .totals {
            border-top: 2px solid #222222;
            margin-top: 8px;
        }

        .totals td {
            font-size: 12px;
            padding: 4px 0;
        }

        .total-row td {
            color: #222222;
            font-size: 18px;
            font-weight: 700;
            padding-top: 11px;
        }

        .footer {
            border-top: 1px solid #e3ddd5;
            text-align: center;
            padding-top: 16px;
            padding-bottom: 22px;
        }

        .nowrap {
            white-space: nowrap;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .line {
            line-height: 1.55;
        }

        .show-mobile {
            display: none;
        }

        @media only screen and (max-width: 600px) {
            .show-mobile {
                display: inline !important;
            }
            .invoice {
                width: 100% !important;
                max-width: 100% !important;
                border: none !important;
            }
            .section {
                padding-left: 15px !important;
                padding-right: 15px !important;
            }
            .col-block {
                display: block !important;
                width: 100% !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
                box-sizing: border-box !important;
            }
            .info-box {
                margin-top: 15px !important;
            }
            .address-row td {
                display: block !important;
                width: 100% !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
                padding-bottom: 15px !important;
            }
            .address-row td:last-child {
                padding-bottom: 0 !important;
            }
            .hide-mobile {
                display: none !important;
            }
            .col-block.right {
                text-align: left !important;
                margin-top: 10px !important;
            }
            .footer-col {
                padding: 10px 0 !important;
                text-align: center !important;
            }
            .footer-col table {
                margin: 0 auto !important;
            }
            /* Items responsive stacking */
            .items > thead, .items > thead > tr > th, .items > tbody > tr > th {
                display: none !important;
            }
            .items > tbody, .items > tbody > tr, .items > tbody > tr > td.item-td {
                display: block !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }
            .items > tbody > tr {
                border-bottom: 1px solid #ececec !important;
                padding: 12px 0 !important;
            }
            .items > tbody > tr > td.item-td {
                text-align: left !important;
                padding: 4px 0 !important;
                border: none !important;
            }
            .items > tbody > tr > td.product-title {
                font-size: 13px !important;
                font-weight: 700 !important;
                padding-bottom: 6px !important;
            }
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
        $isPdf = $isPdf ?? false;
        $assetPath = function ($path) use ($isPdf) {
            if (!$isPdf) {
                return asset($path);
            }

            $absolutePath = public_path($path);
            if (!is_file($absolutePath)) {
                return '';
            }

            $mime = mime_content_type($absolutePath) ?: 'image/jpeg';
            return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($absolutePath));
        };
        $paymentType = strtolower((string) ($order->payment_type ?? ''));
        $paymentMethod =
            str_contains($paymentType, 'stripe') || in_array($paymentType, ['card', 'card_payment', 'online_payment'])
                ? 'Card payment'
                : ucfirst(str_replace('_', ' ', $order->payment_type ?? ''));
        $sellerProfileAddress = null;
        $sellerAddressLines = [];

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

    <table class="page" width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center">
                <table class="invoice" width="680" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                        <td class="section header">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td width="50%" align="left" valign="middle">
                                        <img src="{{ asset('public/assets/img/TTF.jpg') }}" width="128"
                                            alt="Time To Furnish" style="display:block;width:128px;height:auto;">
                                    </td>
                                    <td width="50%" align="right" valign="middle" class="right">
                                        <div class="copy-badge" style="background:{{ $invoiceCopy['color'] }};">
                                            {{ $invoiceCopy['label'] }}
                                        </div>
                                        @if (($invoiceCopyType ?? '') === \App\Services\OrderInvoiceService::ADMIN)
                                            <div class="muted"
                                                style="font-size:9px;margin-top:6px;color:#000;font-weight:900;">
                                                For office use only
                                            </div>
                                        @endif

                                        <div class="invoice-title" style="color:{{ $invoiceCopy['color'] }};">
                                            Invoice
                                        </div>
                                        <div class="muted" style="font-size:11px;margin-top:3px;">Order
                                            {{ $order->code }}</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td class="section" style="padding-top:24px;padding-bottom:12px;">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td class="col-block" width="56%" style="padding-right:22px;">
                                        <div class="label">Invoice to</div>
                                        <div class="h1">{{ $deliveryAddress->name ?? '' }}</div>
                                        @foreach ($formatAddress($deliveryAddress) as $line)
                                            @if (!$loop->first)
                                                <div class="line">{{ $line }}</div>
                                            @endif
                                        @endforeach
                                        @if (!empty($deliveryAddress->email))
                                            <div class="muted" style="font-size:11px;margin-top:7px;">
                                                {{ $deliveryAddress->email }}</div>
                                        @endif
                                        @if (!empty($deliveryAddress->phone))
                                            <div class="muted" style="font-size:11px;">{{ $deliveryAddress->phone }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="col-block" width="44%">
                                        <table class="info-box" width="100%" cellpadding="0" cellspacing="0"
                                            role="presentation">
                                            <tr>
                                                <td style="padding:13px 15px;">
                                                    <table width="100%" cellpadding="0" cellspacing="0"
                                                        role="presentation">
                                                        <tr>
                                                            <td width="45%" class="muted">Payment status</td>
                                                            <td width="55%" class="right nowrap"
                                                                style="font-weight:700;">
                                                                {{ ucfirst($order->payment_status ?? '') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="muted">Invoice no.</td>
                                                            <td class="right inherit">{{ $invoiceNumber }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="muted">Invoice date</td>
                                                            <td class="right nowrap">{{ date('d F Y', $order->date) }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="muted">Generated at</td>
                                                            <td class="right nowrap">
                                                                {{ \Carbon\Carbon::parse($invoiceGeneratedAt)->format('d F Y, h:i A') }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="muted">Payment method</td>
                                                            <td class="right nowrap">{{ $paymentMethod }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="muted"
                                                                style="padding-top:10px;border-top:1px solid #d7c8b8;">
                                                                Total payable</td>
                                                            <td class="right nowrap"
                                                                style="font-size:16px;font-weight:700;padding-top:10px;border-top:1px solid #d7c8b8;">
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
                        <td class="section" style="padding-top:8px;padding-bottom:18px;">
                            <table class="address-row" width="100%" cellpadding="0" cellspacing="0"
                                role="presentation">
                                <tr>
                                    <td class="col-block" width="{{ $addressColumnWidth }}" style="padding-right:16px;">
                                        <div class="h2">Delivery address</div>
                                        @foreach ($formatAddress($deliveryAddress) as $line)
                                            <div class="line" style="font-size:11px;">{{ $line }}</div>
                                        @endforeach
                                    </td>
                                    @if (!empty($sellerAddressLines))
                                        <td class="col-block" width="33.33%" style="padding-left:16px;padding-right:16px;">
                                            <div class="h2">Billing address</div>
                                            @foreach ($sellerAddressLines as $line)
                                                <div class="line" style="font-size:11px;">{{ $line }}</div>
                                            @endforeach
                                        </td>
                                    @endif
                                    <td class="col-block" width="{{ $addressColumnWidth }}" style="padding-left:16px;">
                                        <div class="h2">Sold by</div>
                                        <div class="line" style="font-size:11px;">
                                            {{ $order->shop->name ?? 'Time To Furnish' }}</div>
                                        @if (!empty($sellerAddressLines))
                                            @foreach (array_slice($sellerAddressLines, 1) as $line)
                                                <div class="line" style="font-size:11px;">{{ $line }}</div>
                                            @endforeach
                                        @endif
                                        <div class="line" style="font-size:11px;">{{ $companyEmail }}</div>
                                        <div class="line" style="font-size:11px;">{{ $companyPhone }}</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td class="section" style="padding-bottom:18px;">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td class="col-block" width="50%">
                                        <div class="h2">Order information</div>
                                        <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td width="34%" class="muted"
                                                    style="font-size:11px;padding:2px 0;">
                                                    Order date</td>
                                                <td width="66%" style="font-size:11px;padding:2px 0;">
                                                    {{ date('d F Y', $order->date) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="muted" style="font-size:11px;padding:2px 0;">Order #</td>
                                                <td style="font-size:11px;padding:2px 0;">{{ $order->code }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="col-block right" width="50%" style="font-size:11px;">
                                        For support:
                                        <a href="mailto:{{ $companyEmail }}"
                                            style="color:#8a6f4d;text-decoration:none;">{{ $companyEmail }}</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td class="section" style="padding-bottom:26px;">
                            <div class="details-title">Invoice details</div>
                            <table class="items" width="100%" cellpadding="0" cellspacing="0"
                                role="presentation">
                                <thead>
                                    <tr>
                                        <th width="52%" align="left">Description</th>
                                        <th width="10%" align="center">Qty</th>
                                        <th width="18%" align="right">Unit price</th>
                                        <th width="20%" align="right">Subtotal</th>
                                    </tr>
                                </thead>

                                <tbody>
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
                                                <td class="item-td product-title">
                                                    <div style="font-weight:700;">{{ $orderDetail->product->name }}</div>
                                                    @if ($orderDetail->variation)
                                                        <div class="muted" style="font-size:11px;margin-top:2px;">
                                                            Variant:
                                                            {{ $orderDetail->variation }}</div>
                                                    @endif
                                                    @if (!empty($addons))
                                                         <table class="addons" width="100%" cellpadding="0"
                                                             cellspacing="0" role="presentation" style="width: 100%; table-layout: fixed; border-collapse: collapse;">
                                                             <colgroup>
                                                                 <col style="width: 35%;">
                                                                 <col style="width: 48%;">
                                                                 <col style="width: 17%;">
                                                             </colgroup>
                                                             @foreach ($addons as $addon)
                                                                 <tr>
                                                                     <td style="width: 35%; text-align: left; vertical-align: top; color: #6b5a45;">
                                                                         {{ $addon['addon_name'] ?? ($addon['key'] ?? 'Addon') }}
                                                                     </td>
                                                                     <td style="width: 48%; text-align: left; vertical-align: top;">
                                                                         {{ $addon['name'] ?? ($addon['value'] ?? '-') }}
                                                                     </td>
                                                                     <td class="nowrap" style="width: 17%; text-align: left; vertical-align: top;">
                                                                         {{ single_price($addon['price'] ?? 0) }}
                                                                     </td>
                                                                 </tr>
                                                             @endforeach
                                                         </table>
                                                     @endif
                                                </td>
                                                <td class="item-td center">
                                                    <span class="show-mobile small muted" style="display:none;font-weight:700;">Qty: </span>{{ $orderDetail->quantity }}
                                                </td>
                                                <td class="item-td right nowrap">
                                                    <span class="show-mobile small muted" style="display:none;font-weight:700;">Unit price: </span>{{ single_price($orderDetail->quantity > 0 ? $lineBase / $orderDetail->quantity : $lineBase) }}
                                                </td>
                                                <td class="item-td right nowrap">
                                                    <span class="show-mobile small muted" style="display:none;font-weight:700;">Subtotal: </span>{{ single_price($lineBase + $lineAddon) }}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach

                                    <tr>
                                        <td class="item-td">Shipping charges</td>
                                        <td class="hide-mobile"></td>
                                        <td class="hide-mobile"></td>
                                        <td class="item-td right nowrap"><span class="show-mobile small muted" style="display:none;font-weight:700;">Subtotal: </span>{{ single_price($shippingTotal) }}</td>
                                    </tr>
                                    @if ((float) $order->coupon_discount > 0)
                                        <tr style="background:#faf8f5;">
                                            <td class="item-td">Promotion / coupon</td>
                                            <td class="hide-mobile"></td>
                                            <td class="hide-mobile"></td>
                                            <td class="item-td right nowrap"><span class="show-mobile small muted" style="display:none;font-weight:700;">Subtotal: </span>-{{ single_price($order->coupon_discount) }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                            <table class="totals" width="100%" cellpadding="0" cellspacing="0"
                                role="presentation">
                                <tr>
                                    <td class="hide-mobile" width="56%"></td>
                                    <td class="col-block" width="44%">
                                        <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td width="58%" class="muted">Items subtotal</td>
                                                <td width="42%" class="right nowrap">
                                                    {{ single_price($itemsSubtotal) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="muted">Addons subtotal</td>
                                                <td class="right nowrap">{{ single_price($addonsSubtotal) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="muted">Shipping charges</td>
                                                <td class="right nowrap">{{ single_price($shippingTotal) }}</td>
                                            </tr>
                                            <tr class="total-row">
                                                <td>Invoice total</td>
                                                <td class="right nowrap">{{ single_price($order->grand_total) }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td class="section footer">
                            <div class="muted" style="font-size:11px;line-height:1.6;">
                                You will receive an email notification once your goods have been dispatched by the
                                seller.
                            </div>
                            <div style="font-size:12px;font-weight:700;margin-top:7px;">
                                Thank you for shopping with Time To Furnish.
                            </div>

                            <table cellpadding="0" cellspacing="0" role="presentation"
                                style="margin:20px auto 0;width:100%;max-width:700px;">

                                <tr>

                                    <!-- EMAIL -->
                                    <td class="col-block footer-col" align="center"
                                        style="font-size:13px;color:#333;vertical-align:middle;padding:0 10px;">

                                        <table cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td style="vertical-align:middle;padding-right:10px;">
                                                    <img src="{{ asset('public/assets/img/email.jpeg') }}"
                                                        alt="Email" width="40" height="40"
                                                        style="display:block;width:40px;height:40px;object-fit:contain;">
                                                </td>

                                                <td style="vertical-align:middle;">
                                                    {{ $companyEmail }}
                                                </td>
                                            </tr>
                                        </table>

                                    </td>


                                    <!-- WEBSITE -->
                                    <td class="col-block footer-col" align="center"
                                        style="font-size:13px;color:#333;vertical-align:middle;padding:0 10px;">

                                        <table cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>

                                                <td style="vertical-align:middle;padding-right:10px;">

                                                    <img src="{{ asset('public/assets/img/website.jpeg') }}"
                                                        alt="Website" width="40" height="40"
                                                        style="display:block;width:40px;height:40px;object-fit:contain;">

                                                </td>

                                                <td style="vertical-align:middle;">
                                                    {{ $companyWebsite }}
                                                </td>

                                            </tr>
                                        </table>

                                    </td>


                                    <!-- PHONE -->
                                    <td class="col-block footer-col" align="center"
                                        style="font-size:13px;color:#333;vertical-align:middle;padding:0 10px;">

                                        <table cellpadding="0" cellspacing="0" role="presentation">

                                            <tr>

                                                <td style="vertical-align:middle;padding-right:10px;">

                                                    <img src="{{ asset('public/assets/img/whatsapp.jpeg') }}"
                                                        alt="Phone" width="40" height="40"
                                                        style="display:block;width:40px;height:40px;object-fit:contain;">

                                                </td>

                                                <td style="vertical-align:middle;">
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
