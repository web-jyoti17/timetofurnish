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
                $product_discount_total = 0;
                $shipping_total = 0;
                @endphp
                @foreach($carts as $cartItem)
                @php
                $product = get_single_product($cartItem->product_id);
                $base_price = cart_product_base_price($cartItem, $product, false);
                $price = cart_product_price($cartItem, $product, false, false);
                $line_product_discount = max(0, ($base_price - $price) * $cartItem->quantity);
                $product_discount_total += $line_product_discount;
                $product_name_with_choice = $product->getTranslation('name');
                $tax += $cartItem['tax'] * $cartItem['quantity'];
                if(empty($coupon_discount)){
                $coupon_discount += $cartItem['discount'];
                }
                $product_total = $price * $cartItem->quantity;
                $subtotal += $product_total;
                $total += $product_total;
                $shipping_total += (float) $cartItem->shipping_cost;
                @endphp

                {{-- Main Product Row --}}
                <tr>
                    <td class="product-name" colspan="2" style="font-weight: 500;">
                        <div class="d-flex align-items-center justify-content-between" style="gap: 8px;">
                            <span>{{ $product_name_with_choice }}</span>
                            <a href="{{ route('cart.editItem', $cartItem->id) }}"
                                class="btn btn-link p-0 d-flex align-items-center justify-content-center flex-shrink-0"
                                style="outline:none;border:none;border: 1px solid #EADDCF;background:#fdf6ed;width:28px;height:28px;border-radius:7px;transition:all 0.2s ease-in-out;box-shadow: 0 1px 3px rgba(181, 122, 69, 0.08);"
                                onmouseover="this.style.background='#b57a45'; this.querySelector('svg path').style.stroke='#ffffff'; this.style.transform='scale(1.08)';"
                                onmouseout="this.style.background='#fdf6ed'; this.querySelector('svg path').style.stroke='#b57a45'; this.style.transform='scale(1)';"
                                title="{{ translate('Edit options') }}">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M11 4H4C2.89543 4 2 4.89543 2 6V20C2 21.1046 2.89543 22 4 22H18C19.1046 22 20 21.1046 20 20V13M18.5 2.5C19.3284 1.67157 20.6716 1.67157 21.5 2.5C22.3284 3.32843 22.3284 4.67157 21.5 5.5L12 15L8 16L9 12L18.5 2.5Z"
                                        stroke="#b57a45" stroke-width="1.8" stroke-linecap="round"
                                        stroke-linejoin="round" style="transition: stroke 0.2s;" />
                                </svg>
                            </a>
                        </div>
                    </td>
                    <td class="text-right product-price cartdetails" style="font-weight: 500;">
                       
                    <strong>
                    @if($price > 0)
                        @if($line_product_discount > 0)
                            <span class="d-block text-muted" style="font-size:12px;text-decoration:line-through;">
                                £{{ number_format($base_price * $cartItem->quantity, 2) }}
                            </span>
                        @endif
                        £{{ number_format($price * $cartItem->quantity, 2) }}
                        @if($line_product_discount > 0)
                            <span class="d-block text-danger" style="font-size:12px;">
                                Discount -£{{ number_format($line_product_discount, 2) }}
                            </span>
                        @endif
                        @else
                        -
                        @endif
            </strong>
                    </td>
                </tr>

                {{-- Addons: each as its own row --}}
                @php $cartItem_addons = []; @endphp
                @if(!empty($cartItem->addons))
                @php
                $cartItem_addons = json_decode($cartItem->addons, true);

                // Fetch attributes from cart if they exist
                $cartItem_attributes = [];
                if (!empty($cartItem->attributes)) {
                $cartItem_attributes = json_decode($cartItem->attributes, true);
                }

                // Collect the names of all attributes so we can filter them out of the addons array
                $attributeNames = [];
                if (is_array($cartItem_attributes)) {
                foreach ($cartItem_attributes as $attr) {
                if (!empty($attr['attribute_name'])) {
                $attributeNames[] = strtolower(trim($attr['attribute_name']));
                }
                }
                }

                $variation_string = $cartItem->variation ?? '';
                $variation_parts = array_map(function($v) {
                    return strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $v));
                }, explode('-', $variation_string));

                // Remove any redundant variants that were injected into addons
                $cartItem_addons = array_filter($cartItem_addons, function($addon) use ($attributeNames, $variation_parts) {
                if (in_array(strtolower(trim($addon['addon_name'] ?? '')), $attributeNames)) {
                    return false;
                }
                $addon_value_clean = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $addon['name'] ?? ''));
                if (!empty($addon_value_clean) && in_array($addon_value_clean, $variation_parts)) {
                    return false;
                }
                return true;
                });
                @endphp
                @foreach($cartItem_addons as $addon)
                @php
                $addon_price_total = ($addon['price'] ?? 0) * $cartItem->quantity;
                $addonImage = $addon['image'] ?? ($addon['img'] ?? ($addon['image_url'] ?? ''));
                $addonImageSrc = $addonImage
                    ? (\Illuminate\Support\Str::startsWith($addonImage, ['http://', 'https://', 'data:'])
                        ? $addonImage
                        : asset(ltrim($addonImage, '/')))
                    : '';
                $subtotal += $addon_price_total;
                $total += $addon_price_total;
                @endphp
                <tr>
                    <td class="pl-4" colspan="2">
                        <span style="font-weight:400;" class="addons-price">
                            @if($addonImageSrc)
                            <img src="{{ $addonImageSrc }}"
                                alt="{{ $addon['name'] ?? 'Addon' }}"
                                style="width:24px;height:24px;object-fit:cover;border-radius:4px;border:1px solid #e5e5e5;margin-right:6px;vertical-align:middle;">
                            @endif
                            @if(isset($addon['addon_name']))
                            <span class="text-black" style="font-weight:700;">{{ $addon['addon_name'] }}:&nbsp;</span>
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
                        <span style="font-weight:400;" class="services-price">
                          <strong>  {{ $service['name'] }}</strong>
                            <span class="badge badge-inline badge-soft-primary ml-2">{{ ucfirst($service['type']) }}</span>
                            <span class="ml-2">(£{{ number_format($service['price'], 2) }})</span>
                        </span>
                    </td>

                    <td class="text-right">
                        <span style="font-weight:400;" class="services-price-text">£{{ number_format($service['price'], 2) }}</span>
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
                @if($product_discount_total > 0)
                <tr>
                    <td style="border: none;">Items before discount</td>
                    <td class="text-right cartdetails" style="border: none;">
                        £{{ number_format($subtotal + $product_discount_total,2) }}
                    </td>
                </tr>
                <tr>
                    <td style="border: none;">Product Discount</td>
                    <td class="text-right text-danger" style="border: none;">
                        -£{{ number_format($product_discount_total,2) }}
                    </td>
                </tr>
                @endif
                <tr>
                    <td style="border: none;">Subtotal</td>
                    <td class="text-right cartdetails" style="border: none;">
                        £{{ number_format($subtotal,2) }}
                    </td>
                </tr>
                @if(!empty($coupon_discount))
                <tr>
                    <td style="border: none;">Discount</td>
                    <td class="text-right text-danger" style="border: none;">
                        <span id="discount_amount">-£{{ number_format($coupon_discount,2) }}</span>
                    </td>
                </tr>
                @php $total -= $coupon_discount; @endphp
                @endif
                @if($shipping_total > 0)
                <tr>
                    <td style="border: none;">Shipping Charges</td>
                    <td class="text-right" style="border: none;">
                        <span>{{ single_price($shipping_total) }}</span>
                    </td>
                </tr>
                @php $total += $shipping_total; @endphp
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
            0 10px 25px rgba(0, 0, 0, 0.08),
            0 2px 6px rgba(0, 0, 0, 0.04);
    }

    /* =========================
   HEADER (PEACH BACK)
========================= */
    .cart-summary-card .card-header {

        padding: 16px 20px;
        border-bottom: 1px solid #eee;
    }

    .cart-summary-card {
        margin-top: 20px;
        padding-top: 20px;
        padding-bottom: 20px;
        background: #f0eded;
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
        background: #dacbbc;
        color: black;
        font-size: 14px;
        font-weight: 600;
        border-radius: 0 4px 4px 0;
    }

    .coupon-box .btn:hover {
        background: #dacbbc;

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
        font-weight: 400 !important;
        /* normal */
    }

    /* =========================
   SUMMARY UI IMPROVEMENT
========================= */

    /* Product name */
    .summary-total-table .product-name,
    .summary-total-table td:first-child strong {
        font-size: 15px;
        font-weight: 500;
        color: #4b3a2f;
        /* soft brown */
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
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.03);
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
