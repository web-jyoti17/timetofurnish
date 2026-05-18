<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Laravel</title>
	<meta http-equiv="Content-Type" content="text/html;" />
	<meta charset="UTF-8">
	<style media="all">
		@font-face {
			font-family: 'Roboto';
			src: url("{{ static_asset('fonts/Roboto-Regular.ttf') }}") format("truetype");
			font-weight: normal;
			font-style: normal;
		}

		* {
			margin: 0;
			padding: 0;
			line-height: 1.3;
			font-family: 'Roboto';
			color: #333542;
		}

		body {
			font-size: .875rem;
		}

		.gry-color *,
		.gry-color {
			color: #878f9c;
		}

		table {
			width: 100%;
		}

		table th {
			font-weight: normal;
		}

		table.padding th {
			padding: .5rem .7rem;
		}

		table.padding td {
			padding: .7rem;
		}

		table.sm-padding td {
			padding: .2rem .7rem;
		}

		.border-bottom td,
		.border-bottom th {
			border-bottom: 1px solid #eceff4;
		}

		.text-left {
			text-align: left;
		}

		.text-right {
			text-align: right;
		}

		.small {
			font-size: .85rem;
		}

		.currency {}
	</style>
</head>

<body>
	<div>
		@php
		$logo = get_setting('header_logo');
		@endphp
		<div style="background: #eceff4;padding: 1.5rem;">
			<table>
				<tr>
					<td>
						@if($logo != null)
						<img loading="lazy" src="{{ uploaded_asset($logo) }}" height="40" style="display:inline-block;">
						@else
						<img loading="lazy" src="{{ static_asset('assets/img/logo.png') }}" height="40" style="display:inline-block;">
						@endif
					</td>
				</tr>
			</table>
			<table>
				<tr>
					<td style="font-size: 1.2rem;" class="strong">{{ get_setting('site_name') }}</td>
					<td class="text-right"></td>
				</tr>
				<tr>
					<td class="gry-color small">{{ get_setting('contact_address') }}</td>
					<td class="text-right"></td>
				</tr>
				<tr>
					<td class="gry-color small">{{ translate('Email') }}: {{ get_setting('contact_email') }}</td>
					<td class="text-right small"><span class="gry-color small">{{ translate('Order ID') }}:</span> <span class="strong">{{ $order->code }}</span></td>
				</tr>
				<tr>
					<td class="gry-color small">{{ translate('Phone') }}: {{ get_setting('contact_phone') }}</td>
					<td class="text-right small"><span class="gry-color small">{{ translate('Order Date') }}:</span> <span class=" strong">{{ date('d-m-Y', $order->date) }}</span></td>
				</tr>
			</table>

		</div>

		<div style="padding: 1.5rem;padding-bottom: 0">
			<table>
				<tr>
					<td class="text-left" style="width:50%;">
						<table>
							@php
							$shipping_address = json_decode($order->shipping_address);
							@endphp
							<tr>
								<td class="strong small gry-color">{{ translate('Ship To') }}:</td>
							</tr>
							<tr>
								<td class="strong">{{ $shipping_address->name }}</td>
							</tr>
							<tr>
								<td class="gry-color small">{{ $shipping_address->address }},<br> {{ $shipping_address->city }} - {{ $shipping_address->postal_code }} @if(isset(json_decode($order->shipping_address)->state)) {{ json_decode($order->shipping_address)->state }} @endif<br> {{ $shipping_address->country }}</td>
							</tr>
							<tr>
								<td class="gry-color small">{{ translate('Email') }}: {{ $shipping_address->email }}</td>
							</tr>
							<tr>
								<td class="gry-color small">{{ translate('Phone') }}: {{ $shipping_address->phone }}</td>
							</tr>
						</table>
					</td>
					<td class="text-left" style="width:50%;">
						<table>
							<tr>
								<td class="strong small gry-color">{{ translate('Ship By') }}:</td>
							</tr>
							<tr>
								<td class="strong">{{ $order->shop->name }}</td>
							</tr>
							<tr>
								<td class="gry-color small">{{ $order->shop->address }},<br> {{ $order->shop->city->name }} - {{ $order->shop->postal_code }} {{ $order->shop->state->name }},<br> {{ $order->shop->country->name }}</td>
							</tr>
							<tr>
								<td class="gry-color small">{{ translate('Email') }}: {{ $order->shop->email }}</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>

		<div style="padding: 1.5rem;">
			<table class="padding text-left small border-bottom">
				<thead>
					<tr class="gry-color" style="background: #eceff4;">
						<th width="35%" class="text-left">{{ translate('Product Name') }}</th>
						{{--<th width="15%" class="text-left">{{ translate('Delivery Type') }}</th>--}}
						<th width="10%" class="text-left">{{ translate('Qty') }}</th>
						<th width="15%" class="text-left">{{ translate('Unit Price') }}</th>
						@if($order->igst)
						<th width="10%" class="text-left">{{ translate('IGST') }}</th>
						@else
						<th width="7.5%" class="text-left">{{ translate('CGST') }}</th>
						<th width="7.5%" class="text-left">{{ translate('SGST') }}</th>
						@endif
						<th width="15%" class="text-right">{{ translate('Total') }}</th>
					</tr>
				</thead>
				<tbody class="strong">
					@foreach ($order->orderDetails as $key => $orderDetail)
					@if ($orderDetail->product != null)
					<tr class="">
						<td>{{ $orderDetail->product->getTranslation('name') }} @if($orderDetail->variation != null) ({{ $orderDetail->variation }}) @endif</td>
						{{--<td>
									@if ($order->shipping_type != null && $order->shipping_type == 'home_delivery')
										{{ translate('Home Delivery') }}
						@elseif ($order->shipping_type == 'pickup_point')
						@if ($order->pickup_point != null)
						{{ $order->pickup_point->getTranslation('name') }} ({{ translate('Pickip Point') }})
						@endif
						@endif
						</td>--}}
						<td class="gry-color">{{ $orderDetail->quantity }}</td>
						<td class="gry-color currency">{{ single_price($orderDetail->price/$orderDetail->quantity) }}</td>
						@if($order->igst)
						<td class="gry-color currency">{{ single_price($orderDetail->tax/$orderDetail->quantity) }}</td>
						@else
						@php
						$oldTax = $orderDetail->tax/$orderDetail->quantity;
						$singleTax = $oldTax/2;
						@endphp
						<td class="currency gry-color">{{ single_price($singleTax) }}</td>
						<td class="currency gry-color">{{ single_price($singleTax) }}</td>
						@endif
						<td class="text-right currency">{{ single_price($orderDetail->price) }}</td>
					</tr>
					@endif
					@endforeach
				</tbody>
			</table>
		</div>

		<div style="padding:0 1.5rem;">
			<table style="width: 40%;margin-left:auto;" class="text-right sm-padding small strong">
				<tbody>
					<tr>
						<th class="gry-color text-left">{{ translate('Sub Total') }} <small>(Tax inclusive.)</small></th>
						<td class="currency">{{ single_price($order->orderDetails->sum('price')) }}</td>
					</tr>
					<tr>
						<th class="gry-color text-left">{{ translate('Shipping Cost') }}</th>
						<td class="currency">{{ single_price($order->orderDetails->sum('shipping_cost')) }}</td>
					</tr>
					@if($order->igst)
					<tr class="border-bottom">
						<th class="gry-color text-left">{{ translate('IGST') }} <small>(18%)</small></th>
						<td class="currency">{{ single_price($order->orderDetails->sum('tax')) }}</td>
					</tr>
					@else
					@php $newTax = $order->orderDetails->sum('tax')/2 ; @endphp
					<tr class="border-bottom">
						<th class="gry-color text-left">{{ translate('SGST') }} <small>(9%)</small></th>
						<td class="currency">{{ single_price($$newTax) }}</td>
					</tr>
					<tr class="border-bottom">
						<th class="gry-color text-left">{{ translate('load_home_categories_section') }} <small>(9%)</small></th>
						<td class="currency">{{ single_price($newTax) }}</td>
					</tr>
					@endif
					<tr class="border-bottom">
						<th class="gry-color text-left">{{ translate('Coupon') }}</th>
						<td class="currency">{{ single_price($order->coupon_discount) }}</td>
					</tr>
					<tr>
						<!-- <th class="text-left strong">{{ translate('Grand Total') }} 
							<small> (20% VAT INCURRED)</small>
						</th> -->
						<td class="currency">{{ single_price($order->grand_total) }}</td>
					</tr>
				</tbody>
			</table>
		</div>

	</div>
</body>

</html>