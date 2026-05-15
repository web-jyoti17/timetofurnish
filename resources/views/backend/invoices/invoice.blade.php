<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .invoice-box {
            width: 100%;
            padding: 30px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 6px;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .product-table th {
            background: #111;
            color: #fff;
            padding: 8px;
        }

        .product-table td {
            border-bottom: 1px solid #000;
            padding: 8px;
        }
    </style>
</head>

<body>
    @php ini_set('allow_url_fopen', 1); @endphp
    <div class="invoice-box">
        <!-- LOGO
 <table>
  <tr>
   <td>
    <img style="width:150px;" src="{{ public_path('uploads/all/226xIlqxsV0wxtSTFRdXMVlTf2dmfBNkCOlYUnqO.jpg') }}">
   </td>
  </tr>
 </table> -->
        <h2>INVOICE</h2>
        <div class="ttf"><b>TIME TO FURNISH </B> </div>
        <!-- HEADER -->
        <table>
            <tr>
                <td width="50%">
                    <b>Registered Address:</b><br>
                    {{ get_setting('contact_address') }}<br>

                    <b>Email:</b> {{ get_setting('contact_email') }}<br>
                    <b>Customer Care:</b> {{ get_setting('contact_phone') }}
                </td>

                <td width="50%" class="text-right">
                    <b>Order ID:</b> {{ $order->code }}<br>
                    <b>Date:</b> {{ \Carbon\Carbon::parse($order->date)->format('d M Y') }}<br>
                    <b>Payment:</b> Card
                </td>
            </tr>
        </table>

        <br>

        <!-- SHIPPING -->
        @php $shipping = json_decode($order->shipping_address); @endphp

        <table>
            <tr>
                <td width="50%">
                    <b>Ship To:</b><br>
                    {{ $shipping->name ?? '' }}<br>
                    {{ $shipping->address ?? '' }},
                    {{ $shipping->city ?? '' }},
                    {{ $shipping->state ?? '' }},
                    {{ $shipping->country ?? '' }}<br>

                    Email: {{ $shipping->email ?? '' }}<br>
                    Phone: {{ $shipping->phone ?? '' }}
                </td>

                <td width="50%" class="text-right">
                    <b>Ship By:</b><br>
                    {{ $order->shop->name ?? '' }}<br>
                    {{ $order->shop->email ?? '' }}
                </td>
            </tr>
        </table>

        <br>

        <!-- PRODUCTS -->
        <table class="product-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="text-center">QTY</th>
                    <th class="text-center">Unit (£)</th>
                    <th class="text-center">VAT</th>
                    <th class="text-center">Total (£)</th>
                </tr>
            </thead>

            <tbody>

                @php $addon_total_all = 0; @endphp

                @foreach ($order->orderDetails as $detail)
                    @if ($detail->product)
                        @php
                            // SKU SAFE
                            $stock = $detail->product->stocks->first();
                            $sku = $stock->sku ?? '';

                            // ADDONS SAFE (JSON + serialized)
                            $addonItems = [];

                            if (!empty($detail->addons)) {
                                $decoded = json_decode($detail->addons, true);

                                if (json_last_error() === JSON_ERROR_NONE) {
                                    $addonItems = $decoded;
                                } else {
                                    try {
                                        $addonItems = unserialize($detail->addons);
                                    } catch (\Exception $e) {
                                        $addonItems = [];
                                    }
                                }
                            }

                            $addons_total = collect($addonItems)->sum('price');
                            $addon_total_all += $addons_total;

                            // SAFE UNIT PRICE
                            $unit = $detail->quantity ? $detail->price / $detail->quantity : 0;
                        @endphp

                        <tr>
                            <td>
                                {{ $detail->product->name }}

                                @if ($detail->variation)
                                    ({{ $detail->variation }})
                                @endif

                                <br>
                                SKU: {{ $sku }}

                                @if (!empty($addonItems))
                                    <br> Addons:
                                    @foreach ($addonItems as $item)
                                        <br> - {{ $item['addon_name'] ?? '' }}: {{ $item['name'] ?? '' }}
                                        (£{{ $item['price'] ?? 0 }})
                                    @endforeach
                                @endif

                            </td>

                            <td class="text-center">{{ $detail->quantity }}</td>
                            <td class="text-center">{{ number_format($unit, 2) }}</td>
                            <td class="text-center">0</td>
                            <td class="text-center">{{ number_format($detail->price + $addons_total, 2) }}</td>
                        </tr>
                    @endif
                @endforeach

                <!-- GRAND -->
                <tr>
                    <td colspan="4" class="text-right"><b>Grand Total (£)</b></td>
                    <td class="text-center">
                        <b>{{ number_format($order->orderDetails->sum('price') + $addon_total_all, 2) }}</b>
                    </td>
                </tr>

            </tbody>
        </table>

        <br>

        <!-- TOTALS -->
        <table width="50" align="right">
            <tr>
                <td class="text-right"><b>Sub Total:</b></td>
                <td class="text-right">£{{ number_format($order->orderDetails->sum('price'), 2) }}</td>
            </tr>

            <tr>
                <td class="text-right"><b>Shipping:</b></td>
                <td class="text-right">£{{ number_format($order->orderDetails->sum('shipping_cost'), 2) }}</td>
            </tr>

            <tr>
                <td class="text-right"><b>Discount:</b></td>
                <td class="text-right">£{{ number_format($order->coupon_discount, 2) }}</td>
            </tr>

            <tr>
                <td class="text-right"><b>Total:</b></td>
                <td class="text-right"><b>£{{ number_format($order->grand_total, 2) }}</b></td>
            </tr>
        </table>

        <br><br>

        <p style="text-align:center;"><b>Thank you for your business!</b></p>

    </div>

</body>

</html>
<style>
    .ttf {
        text-align: left;
        font-size: 20px;
        font-weight: 18px;
    }
</style>
