<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Status Update</title>
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

        .status-badge {
            display: inline-block;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 7px 11px;
            text-transform: uppercase;
            background: #8a6f4d;
        }

        .invoice-title {
            font-size: 25px;
            line-height: 1.15;
            font-weight: 700;
            margin-top: 9px;
            color: #8a6f4d;
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

        .status-box {
            background: #faf8f5;
            border: 1px solid #e5ddd3;
            padding: 18px;
            margin: 20px 0;
            text-align: center;
        }

        .status-text {
            font-size: 18px;
            font-weight: 700;
            color: #222222;
            margin-bottom: 6px;
        }

        .footer {
            border-top: 1px solid #e3ddd5;
            text-align: center;
            padding-top: 16px;
            padding-bottom: 22px;
        }

        .right {
            text-align: right;
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
            .footer-col {
                padding: 10px 0 !important;
                text-align: center !important;
            }
            .footer-col table {
                margin: 0 auto !important;
            }
        }
    </style>
</head>

<body>
    @php
        $deliveryAddress = json_decode($order->shipping_address);
        $companyEmail = 'sales@timetofurnish.com';
        $companyPhone = '+44 7751510365';
        $companyWebsite = 'www.timetofurnish.com';
        $statusColors = [
            'pending' => '#8a6f4d',
            'confirmed' => '#3b82f6', 
            'picked_up' => '#f59e0b',
            'on_the_way' => '#8b5cf6',
            'delivered' => '#10b981',
            'cancelled' => '#ef4444'
        ];
        $statusColor = $statusColors[strtolower($array['status'])] ?? '#8a6f4d';
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
                                        <div class="status-badge" style="background:{{ $statusColor }};">
                                            {{ strtoupper($array['status']) }}
                                        </div>
                                        <div class="invoice-title">Status Update</div>
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
                                        <div class="label">Delivering to</div>
                                        <div class="h1">{{ $deliveryAddress->name ?? '' }}</div>
                                        @if (!empty($deliveryAddress->address))
                                            <div class="line">{{ $deliveryAddress->address }}</div>
                                        @endif
                                        @if (!empty($deliveryAddress->city) || !empty($deliveryAddress->postal_code))
                                            <div class="line">
                                                {{ collect([$deliveryAddress->city, $deliveryAddress->state, $deliveryAddress->postal_code])->filter()->implode(', ') }}
                                            </div>
                                        @endif
                                        @if (!empty($deliveryAddress->country))
                                            <div class="line">{{ $deliveryAddress->country }}</div>
                                        @endif
                                    </td>
                                    <td class="col-block" width="44%">
                                        <table class="info-box" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td style="padding:13px 15px;">
                                                    <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                                        <tr>
                                                            <td width="45%" class="muted">Order #</td>
                                                            <td width="55%" class="right nowrap" style="font-weight:700;">{{ $order->code }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="muted">Order date</td>
                                                            <td class="right nowrap">{{ date('d F Y', $order->date) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="muted">Updated at</td>
                                                            <td class="right nowrap">{{ now()->format('d F Y, h:i A') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="muted" style="padding-top:10px;border-top:1px solid #d7c8b8;">Order total</td>
                                                            <td class="right nowrap" style="font-size:16px;font-weight:700;padding-top:10px;border-top:1px solid #d7c8b8;">
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
                        <td class="section" style="padding-bottom:26px;">
                            <div class="status-box">
                                <div class="status-text" style="color:{{ $statusColor }};">
                                    Your order is {{ ucfirst($array['status']) }}
                                </div>
                                <div class="muted" style="font-size:12px;">
                                    @if(strtolower($array['status']) == 'confirmed')
                                        We've confirmed your order and are preparing it for dispatch.
                                    @elseif(strtolower($array['status']) == 'picked_up')
                                        Your order has been picked up and is on its way to you.
                                    @elseif(strtolower($array['status']) == 'on_the_way')
                                        Your order is out for delivery today.
                                    @elseif(strtolower($array['status']) == 'delivered')
                                        Your order has been delivered. We hope you love it!
                                    @elseif(strtolower($array['status']) == 'cancelled')
                                        Your order has been cancelled. Any payment will be refunded.
                                    @else
                                        We're processing your order and will update you soon.
                                    @endif
                                </div>
                            </div>

                            <div class="h2" style="margin-top:24px;">Order Summary</div>
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="font-size:11px;">
                                @foreach ($order->orderDetails as $orderDetail)
                                    @if ($orderDetail->product)
                                        <tr>
                                            <td style="padding:4px 0;border-bottom:1px solid #ececec;">
                                                {{ $orderDetail->product->name }} × {{ $orderDetail->quantity }}
                                            </td>
                                            <td class="right" style="padding:4px 0;border-bottom:1px solid #ececec;">
                                                {{ single_price($orderDetail->price + ($orderDetail->addon_price ?? 0)) }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td class="section footer">
                            <div class="muted" style="font-size:11px;line-height:1.6;">
                                Need help with your order? Reply to this email or contact us below.
                            </div>
                            <div style="font-size:12px;font-weight:700;margin-top:7px;">
                                Thank you for shopping with Time To Furnish.
                            </div>

                            <table cellpadding="0" cellspacing="0" role="presentation" style="margin:20px auto 0;width:100%;max-width:700px;">
                                <tr>
                                    <!-- EMAIL -->
                                    <td class="col-block footer-col" align="center" style="font-size:13px;color:#333;vertical-align:middle;padding:0 10px;">
                                        <table cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td style="vertical-align:middle;padding-right:10px;">
                                                    <img src="{{ asset('public/assets/img/email.jpeg') }}" alt="Email" width="40" height="40" style="display:block;width:40px;height:40px;object-fit:contain;">
                                                </td>
                                                <td style="vertical-align:middle;">
                                                    {{ $companyEmail }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>

                                    <!-- WEBSITE -->
                                    <td class="col-block footer-col" align="center" style="font-size:13px;color:#333;vertical-align:middle;padding:0 10px;">
                                        <table cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td style="vertical-align:middle;padding-right:10px;">
                                                    <img src="{{ asset('public/assets/img/website.jpeg') }}" alt="Website" width="40" height="40" style="display:block;width:40px;height:40px;object-fit:contain;">
                                                </td>
                                                <td style="vertical-align:middle;">
                                                    {{ $companyWebsite }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>

                                    <!-- PHONE -->
                                    <td class="col-block footer-col" align="center" style="font-size:13px;color:#333;vertical-align:middle;padding:0 10px;">
                                        <table cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td style="vertical-align:middle;padding-right:10px;">
                                                    <img src="{{ asset('public/assets/img/whatsapp.jpeg') }}" alt="Phone" width="40" height="40" style="display:block;width:40px;height:40px;object-fit:contain;">
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