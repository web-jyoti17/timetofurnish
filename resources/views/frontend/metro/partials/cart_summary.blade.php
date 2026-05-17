{{-- CART SUMMARY --}}
<div class="card cart-summary-card cardcolor">
    <div class="card-header cart1 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-600 cartdetails">Summary</h5>
        <span class="item-count cartdetails">{{ count($carts) }} Items</span>
    </div>

    <div class="card-body p-0 pb-3">
        {{-- PRODUCTS --}}
        <table class="table summary-table mb-0 cartdetails" style="background: #f8f7f5;">
            <tbody>
            @php
                $tax = 0;
                $coupon_discount = 0;
                $total = 0;
                $subtotal = 0;
            @endphp
            @foreach($carts as $cartItem)
                @php
                    $product = get_single_product($cartItem->product_id);
                    $price = cart_product_price($cartItem, $product, false, false);
                    $product_name_with_choice = $product->getTranslation('name');
                    if ($cartItem->variation != null) {
                         $product_name_with_choice = $product->getTranslation('name') . ' - ' . $cartItem['variation'];
                    }
                    $tax += $cartItem['tax'] * $cartItem['quantity'];
                    if(empty($coupon_discount)){
                        $coupon_discount += $cartItem['discount'];
                    }
                    $product_total = $price * $cartItem->quantity;
                    $subtotal += $product_total;
                    $total += $product_total;
                @endphp

                {{-- Main Product Row --}}
                <tr>
                    <td class="product-name" colspan="2" style="font-weight: 500;">
                        {{ $product_name_with_choice }}
                        <span class="qty cartdetails">× {{ $cartItem->quantity }}</span>
                    </td>
                    <td class="text-right product-price cartdetails" style="font-weight: 500;">
                        £{{ number_format($product_total, 2) }}
                    </td>
                </tr>

                {{-- Addons: each as its own row --}}
                @php $cartItem_addons = []; @endphp
                @if(!empty($cartItem->addons))
                    @php
                        $cartItem_addons = json_decode($cartItem->addons, true);
                    @endphp
                    @foreach($cartItem_addons as $addon)
                        @php
                            $addon_price_total = ($addon['price'] ?? 0) * $cartItem->quantity;
                            $subtotal += $addon_price_total;
                            $total += $addon_price_total;
                        @endphp
                        <tr>
                            <td class="pl-4" colspan="2">
                                <span style="font-weight:400;">
                                    @if(isset($addon['addon_name']))
                                        <span class="text-muted">{{ $addon['addon_name'] }}:&nbsp;</span>
                                    @endif
                                    {{ $addon['name'] ?? '' }}
                                    <span class="qty cartdetails">&times; {{ $cartItem->quantity }}</span>
                                </span>
                            </td>
                            <td class="text-right">
                                <span style="font-weight: 400;">£{{ number_format($addon_price_total, 2) }}</span>
                            </td>
                        </tr>
                    @endforeach
                @endif

                {{-- Services --}}
                @if(!empty($cartItem->services))
                    @foreach(get_cart_services($cartItem) as $service)
                        @php $total += $service['price']; $subtotal += $service['price']; @endphp
                        <tr>
                            <td class="pl-4" colspan="2">
                                <span style="font-weight:400;">
                                    {{ $service['name'] }}
                                    <span class="badge badge-inline badge-soft-primary ml-2">{{ ucfirst($service['type']) }}</span>
                                    <span class="ml-2">(£{{ number_format($service['price'], 2) }})</span>
                                </span>
                            </td>

                            <td class="text-right">
                                <span style="font-weight:400;">£{{ number_format($service['price'], 2) }}</span>
                            </td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
            </tbody>
        </table>

        <div class="divider mb-3" style="margin-top:8px;"></div>

        {{-- TOTALS --}}
        <table class="table summary-total-table mb-0 cartdetails" style="background: #f8f7f5;">
            <tbody>
                <tr>
                    <td style="border: none;">Subtotal</td>
                    <td class="text-right cartdetails" style="border: none;">
                        £{{ number_format($subtotal,2) }}
                    </td>
                </tr>
                @if(!empty($coupon_discount))
                    <tr>
                        <td style="border: none;">Discount</td>
                        <td class="text-right" style="border: none;">
                            <span id="discount_amount">-£{{ number_format($coupon_discount,2) }}</span>
                        </td>
                    </tr>
                    @php $total -= $coupon_discount; @endphp
                @endif
                @if(!empty($shipping))
                    <tr>
                        <td style="border: none;">Shipping (£)</td>
                        <td class="text-right" style="border: none;">
                            <span id="shipping_rate">{{ number_format($shipping,2) }}</span>
                        </td>
                    </tr>
                    @php $total += $shipping; @endphp
                @endif
                @php
                    if(!empty($tax)) $total += $tax;
                @endphp
                <tr class="grand-total" style="border-top:2px solid #ddd;">
                    <td style="font-size: 18px; font-weight: 700; padding-top:10px;">Total</td>
                    <td class="text-right" style="font-size: 22px; font-weight: 700; padding-top:10px;">
                        <strong id="grand_total">£{{ number_format($total,2) }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- COUPON --}}
        <div class="coupon-box applycupon mt-3" style="background: #fff; border-radius:8px; padding: 20px;">
            <form class="" id="apply-coupon-form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="owner_id" value="{{ $carts[0]['owner_id'] }}">
                <div class="input-group">
                    <input type="text" class="form-control rounded-0" name="code"
                        onkeydown="return event.key != 'Enter';"
                        placeholder="{{ translate('Have coupon code? Apply here') }}" required
                        style="background: #f8f7f5; border:1px solid #e4e2df; height:48px;">
                    <div class="input-group-append">
                        <button type="button" id="coupon-apply"
                            class="btn borderbtn cartdetails" style="min-width: 100px; font-size: 16px; font-weight: 600;">
                            {{ translate('Apply') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- STYLE --}}
<style>
/* ===============================
/* =========================
   MAIN CART CARD
========================= */
.cart-summary-card {
    border: 1px solid #e6e6e6;
    border-radius: 6px;
    background: #fff;
    box-shadow:
        0 10px 25px rgba(0,0,0,0.08),
        0 2px 6px rgba(0,0,0,0.04);
}

/* =========================
   HEADER (PEACH BACK)
========================= */
.cart-summary-card .card-header {

    padding: 16px 20px;
    border-bottom: 1px solid #eee;
}
.cart-summary-card{
    margin-top:20px;
    padding-top:20px;
    padding-bottom:20px;
    background:#f0eded;
}

.cart-summary-card h5 {
    font-size: 18px;
    font-weight: 600;
    color: #222;
}

/* =========================
   CARD BODY PADDING
========================= */
.cart-summary-card .card-body {
    padding: 30px;
}

/* =========================
   PRODUCT LIST
========================= */
.summary-table td {
    padding: 15px 20px;
    font-size: 15px;
    border-top: none;
    border-bottom: 1px solid #f0f0f0;
}



.product-name .qty {
    font-size: 13px;
    color: #000;
    white-space: nowrap;
    margin: 0;
}


/* Product price */
.product-price {
    font-size: 15px;
    font-weight: 600;
    color: #2a2a2a;
}

/* =========================
   TOTAL TABLE
========================= */
.summary-total-table td {
    padding: 15px 20px;
    font-size: 14.5px;
    border-top: none;
    border-bottom: 1px solid #f0f0f0;
}

/* Subtotal / Shipping text */
.summary-total-table tr td:first-child {
    color: #7a7a7a;
}

/* Subtotal / Shipping price */
.summary-total-table tr td.text-right {
    color: #3d3d3d;
    font-weight: 500;
}

/* GRAND TOTAL */
.summary-total-table .grand-total td {
    font-size: 19px;
    font-weight: 700;
    color: #2b2b2b;
}

/* =========================
   COUPON BOX
========================= */
.coupon-box {
    margin: 18px;
    padding: 16px;
    background: #dacbbc;
    border: 2px solid #e6e6e6;
    border-radius: 6px;
}

/* Coupon input */
.coupon-box .form-control {
    height: 48px;
    border: 2px solid #ddd;
    border-radius: 4px 0 0 4px;
    font-size: 14px;
    padding-left: 14px;
}

/* Apply button */
.coupon-box .btn {
    height: 48px;
    padding: 0 26px;
    background:  #dacbbc;
    color: black;
    font-size: 14px;
    font-weight: 600;
    border-radius: 0 4px 4px 0;
}

.coupon-box .btn:hover {
    background:  #dacbbc;

}

/* =========================
   PROCEED TO CHECKOUT BUTTON
========================= */
.proceed-checkout-btn,
.checkout-btn,
.btn-checkout {
    width: calc(100% - 40px);
    margin: 10px 20px 20px;
    height: 56px;
    border-radius: 30px;
    background: #ff7a57;
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    border: none;
    text-transform: uppercase;
    transition: all 0.25s ease;
}



/* =========================
   MOBILE FIX
========================= */
@media (max-width: 575px) {
    .summary-table td,
    .summary-total-table td {
        padding: 12px 14px;
    }

    .coupon-box {
        margin: 14px;
    }

    .cart-summary-card .card-body {
        padding: 20px;
    }
}


/* =========================
   FORCE BLACK TEXT (AS REQUESTED)
========================= */

/* Subtotal, Shipping, Total labels */
.summary-total-table tr td:first-child {
    color: #000 !important;
}

/* Subtotal, Shipping, Total prices */
.summary-total-table tr td.text-right,
.summary-total-table tr td strong {
    color: #000 !important;
}

/* Product name (optional – keeps consistent black) */


/* Coupon input text */
.coupon-box .form-control {
    color: #000 !important;
}

/* Coupon placeholder text */
.coupon-box .form-control::placeholder {
    color: #000 !important;
    opacity: 1;
}

/* Apply button text (already white, keeping safe) */
.coupon-box .btn {
    color: #000 !important;
}
/* =========================
   TOTAL ko NORMAL BLACK
========================= */

.summary-total-table tr:last-child td,
.summary-total-table tr:last-child td strong {
    color: #000 !important;
    font-weight: 400 !important; /* normal */
}
/* =========================
   SUMMARY UI IMPROVEMENT
========================= */

/* Product name */
.summary-total-table .product-name,
.summary-total-table td:first-child strong {
    font-size: 15px;
    font-weight: 500;
    color: #4b3a2f; /* soft brown */
}

/* Subtotal & Shipping text */
.summary-total-table tr td {
    font-size: 14px;
    color: #777;
}

/* Total row label */
.summary-total-table tr:last-child td:first-child {
    font-size: 15px;
    font-weight: 500;
    color: #555;
}

/* Total amount (keep normal black) */
.summary-total-table tr:last-child td:last-child {
    color: #000;
    font-weight: 400;
}

/* Summary card shadow (premium soft) */
.cart-summary-card {
    box-shadow: 0 12px 30px rgba(0,0,0,0.03);
    border-radius: 8px;
}


</style>

{{-- JS --}}
<script>
/*document.addEventListener('DOMContentLoaded', function () {

    const delivery = document.getElementById('delivery_option');

    delivery.addEventListener('change', function () {

        let selected = this.options[this.selectedIndex];
        let shipping = selected.getAttribute('data-rate')
            ? parseFloat(selected.getAttribute('data-rate'))
            : 0;

        let subtotal = parseFloat(document.getElementById('sub_total').value);

        document.getElementById('shipping_rate').innerText = shipping.toFixed(2);
        document.getElementById('total_shipping').innerText = shipping.toFixed(2);
        document.getElementById('grand_total').innerText = (subtotal + shipping).toFixed(2);

        document.getElementById('shipping_cost').value = shipping;
        document.getElementById('shipping_rate_id').value = this.value;
    });

});*/
</script>
