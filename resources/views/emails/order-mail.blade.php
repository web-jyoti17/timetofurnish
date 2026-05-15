<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Confirmation</title>
<style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        font-size: 14px;
        color: #333;
        background-color: #f4f6f8;
    }
    a { color: #007bff; text-decoration: none; }

    /* HEADER */
    .header {
        width: 100%;
       
        padding: 20px 0;
        text-align: center;
    }
    .header img {
        max-height: 60px;
    }

    /* CONTAINER */
    .container {
        max-width: 800px;
        margin: 0 auto;
        background-color: #ffffff;
        border-radius: 8px;
        padding: 20px;
    }

    /* BOX TITLE */
    .box-title {
        font-weight: bold;
        color: #a4a4a4;
        font-size: 14px;
        text-transform: uppercase;
        margin-bottom: 8px;
    }
    .name-highlight {
        color: #000;
        font-weight: bold;
        margin-bottom: 6px;
        display: block;
    }

    /* TABLES */
    table {
        width: 100%;
        border-collapse: collapse;
    }
    table.order-table th, table.order-table td {
        border-bottom: 1px solid #ddd;
        padding: 8px;
        font-size: 13px;
        text-align: left;
    }
    table.order-table th {
        background-color: #f6f7f9;
    }
    .text-right { text-align: right; }

    /* SUMMARY */
    table.summary td {
        padding: 6px 0;
        font-size: 13px;
    }
    .grand-total {
        font-size: 16px;
        font-weight: bold;
        border-top: 2px solid #ddd;
        padding-top: 8px;
    }

    /* FOOTER */
    .footer {
        text-align: center;
        font-size: 12px;
        color: #888;
        padding: 15px;
        background: #f6f7f9;
        margin-top: 20px;
        border-radius: 0 0 8px 8px;
    }
    .header img {
    max-height: 60px;
}
@media(max-width: 600px){
    .container { 
        padding: 10px; 
    }

    table.order-table th,
    table.order-table td {
        padding: 6px;
        font-size: 12px;
    }

    /* LOGO SIZE FOR MOBILE */
    .header img {
        max-height: 40px;   /* mobile me chhota */
    }
}


    /* MOBILE */
    @media(max-width: 600px){
        .container { padding: 10px; }
        table.order-table th, table.order-table td { padding: 6px; font-size: 12px; }
    }
    .my{
        border-radius: 5px !important;
    }
   .emailbody{
    padding: 20px 25px;
    border: 2px solid #000000;   /* ✅ BLACK OUTLINE */
    background: #ffffff;
    border-radius: 10px;
    max-width: 820px;
    margin: 20px auto;
}

</style>
</head>

<body>

<div class="emailbody">
    <!-- HEADER -->
<div class="header">
   {{-- @php $logo = get_setting('header_logo'); @endphp
    @if($logo)
        <img src="{{ uploaded_asset($logo) }}" alt="Logo">
           
    @endif--}}
     <img src="{{ asset('public/assets/img/TTF.jpg') }}"  class ="my" alt="Logo">
    
</div>

<!-- CONTAINER -->
<div class="container">

    <!-- ORDER INFO / SHIP TO / SHIP BY -->
    <div class="row" style="margin-bottom:20px;"> 
            <!-- ORDER INFO -->
            <div class="col-md-6" style="border:1px solid #e2e6ea; border-radius:6px; padding:8px;">
                <div class="box-title">Order Info</div>
                <div><strong>Email:</strong> {{ get_setting('contact_email') }}</div>
                <div><strong>Order ID:</strong> {{ $order->code }}</div>
                <div><strong>Order Date:</strong> {{ date('d-m-Y', $order->date) }}</div>
            </div>

            <!-- SHIP TO -->
            <div class="col-md-6" style="border:1px solid #e2e6ea; border-radius:6px; padding:8px;">
                @php $shipping_address = json_decode($order->shipping_address); @endphp
                <div class="box-title">Ship To</div>
                <span class="name-highlight">{{ $shipping_address->name }}</span>
                @if(!empty($shipping_address->address))
                    <div><strong>Address:</strong> {{ $shipping_address->address }}</div>
                @endif
                @if(!empty($shipping_address->city))
                    <div><strong>City:</strong> {{ $shipping_address->city }}</div>
                @endif
                @if(!empty($shipping_address->postal_code))
                    <div><strong>Post  Code:</strong> {{ $shipping_address->postal_code }}</div>
                @endif
                @if(!empty($shipping_address->country))
                    <div><strong>Country:</strong> {{ $shipping_address->country }}</div>
                @endif
                <div><strong>Email:</strong> {{ $shipping_address->email }}</div>
                <div><strong>Phone:</strong> {{ $shipping_address->phone }}</div>
            </div>

           
    </div>

    <!-- ORDER ITEMS -->
    <table class="order-table" style="margin-bottom:20px;">
        <tr>
            <th>#</th>
            <th>Item</th>
            <th class="text-right">Qty</th>
            <th class="text-right">Price</th>
            <th class="text-right">Total</th>
        </tr>
        @php $i = 1; @endphp
        @foreach($order->orderDetails as $orderDetail)
            @if($orderDetail->product)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $orderDetail->product->name }} @if($orderDetail->variation) ({{ $orderDetail->variation }}) @endif
					 @php
        $addons = [];

        if (!empty($orderDetail->addon)) {
            $addons = json_decode($orderDetail->addon, true) ?? [];
        } elseif (!empty($orderDetail->addons)) {
            $addons = json_decode($orderDetail->addons, true) ?? [];
        }
    @endphp

    @if(!empty($addons))
        <br>
        <ul style="margin:5px 0 0 15px; padding:0;">
            @foreach($addons as $addon)
                <li style="font-size:12px; color:#555;">
                    {{ $addon['name'] ?? '' }}
                    @if(isset($addon['price']))
                        (+{{ single_price($addon['price']) }})
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
				</td>
				
                <td class="text-right">{{ $orderDetail->quantity }}</td>
                <td class="text-right">{{ single_price($orderDetail->price / $orderDetail->quantity) }}</td>
                <td class="text-right">{{ single_price($orderDetail->price) }}</td>
            </tr>
            @endif
        @endforeach
    </table>

    <!-- SUMMARY -->
    <table class="summary">
        <tr>
            <td>Sub Total </td>
            <td class="text-right">{{ single_price($order->grand_total) }}</td>
        </tr>
        <tr>
            <td>Shipping Cost</td>
            <td class="text-right">{{ single_price($order->orderDetails->sum('shipping_cost')) }}</td>
        </tr>
        <tr>
            <td>Coupon Discount</td>
            <td class="text-right">{{ single_price($order->coupon_discount) }}</td>
        </tr>
        <tr>
            <td class="grand-total">Grand Total<small> (20% VAT INCURRED)</small></td> 
            <td class="text-right grand-total">{{ single_price($order->grand_total) }}</td>
        </tr>
    </table>
 <!-- SHIP BY -->
            <div  style="border:1px solid #e2e6ea; border-radius:6px; padding:8px; width:100%;">
                <div class="box-title">Ship By</div>
                <span class="name-highlight">{{ $order->shop->name }}</span>
                {{--@if(!empty($order->shop->address))
                    <div><strong>Address:</strong> {{ $order->shop->address }}</div>
                @endif
                @if(!empty($order->shop->city))
                    <div><strong>City:</strong> {{ $order->shop->city->name }}</div>
                @endif
                @if(!empty($order->shop->postal_code))
                    <div><strong>Post  Code:</strong> {{ $order->shop->postal_code }}</div>
                @endif--}}
            </div> 
             
</div>
<!-- SOCIAL ICONS -->

<table cellpadding="0" cellspacing="0" width="100%" style="margin:20px 0;">
    <tr>
        <!-- LEFT EMPTY SPACE -->
        <td width="60%"></td>

        <!-- ICONS COLUMN -->
        <td align="right" style="padding-right:10px;">
            <table cellpadding="0" cellspacing="0" align="right">
                <tr>
                    <td style="padding:6px;">
                        <a href="#" target="_blank">
                            <img src="{{ asset('public/assets/img/facebookL.png') }}" width="24" alt="Facebook">
                        </a>
                    </td>
                    <td style="padding:6px;">
                        <a href="#" target="_blank">
                            <img src="{{ asset('public/assets/img/instagramL.png') }}" width="24" alt="Instagram">
                        </a>
                    </td>
                    <td style="padding:6px;">
                        <a href="#" target="_blank">
                            <img src="{{ asset('public/assets/img/twitterL.png') }}" width="24" alt="Twitter">
                        </a>
                    </td>
                    <td style="padding:6px;">
                        <a href="#" target="_blank">
                            <img src="{{ asset('public/assets/img/linkedinL.png') }}" width="24" alt="LinkedIn">
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>


<!-- FOOTER -->
<div class="footer">
    Thank you for shopping with <b>{{ get_setting('site_name') }}</b>
</div>

</div>
</body>
</html>
