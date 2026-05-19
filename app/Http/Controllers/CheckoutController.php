<?php

namespace App\Http\Controllers;

use App\Utility\PayfastUtility;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Address;
use App\Models\Carrier;
use App\Models\CombinedOrder;
use App\Models\Product;
use App\Models\ShippingRate;
use App\Utility\PayhereUtility;
use App\Utility\NotificationUtility;
use Session;
use Auth;
use Illuminate\Support\Facades\Log;
use Seshac\Shiprocket\Shiprocket;
use Illuminate\Support\Facades\Mail;
use App\Utility\CartUtility;

class CheckoutController extends Controller
{

    public function __construct()
    {
        //
    }
    //shivani

    //check the selected payment gateway and redirect to that controller accordingly
    public function checkout(Request $request)
    {
        if ($request->payment_option == null) {
            flash(translate('There is no payment option is selected.'))->warning();
            return redirect()->route('checkout.shipping_info');
        }

        $carts = Cart::where('user_id', Auth::user()->id)->get();

        // Minimum order amount check
        if (get_setting('minimum_order_amount_check') == 1) {

            $subtotal = 0;

            foreach ($carts as $key => $cartItem) {
                $product = Product::find($cartItem['product_id']);

                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
            }

            if ($subtotal < get_setting('minimum_order_amount')) {
                flash(translate('You order amount is less than the minimum order amount'))->warning();
                return redirect()->route('home');
            }
        }

        // Store Order
        (new OrderController)->store($request);

        $request->session()->put('payment_type', 'cart_payment');

        $data['combined_order_id'] = $request->session()->get('combined_order_id');

        $request->session()->put('payment_data', $data);

        if (!empty($data['combined_order_id'])) {

            // Stripe Payment
            if ($request->payment_option == "stripe") {

                $decorator = __NAMESPACE__ . '\\Payment\\' .
                    str_replace(' ', '', ucwords(str_replace('_', ' ', $request->payment_option))) .
                    "Controller";

                if (class_exists($decorator)) {
                    return (new $decorator)->pay($request);
                }
            } else {

                // Manual Payment
                $combined_order = CombinedOrder::findOrFail(
                    $request->session()->get('combined_order_id')
                );

                $manual_payment_data = array(
                    'name'   => $request->payment_option,
                    'amount' => $combined_order->grand_total,
                    'trx_id' => $request->trx_id,
                    'photo'  => $request->photo
                );

                foreach ($combined_order->orders as $order) {

                    $order->manual_payment = 1;
                    $order->manual_payment_data = json_encode($manual_payment_data);
                    $order->save();
                }
            }
        } else {

            \Log::info("Combined order id not found");
        }

        flash(translate('Your order has been placed successfully. Please submit payment information from purchase history'))->success();

        return redirect()->route('order_confirmed');
    }
    //shivani


    public function checkout_done($combined_order_id, $payment, $cod = null)
    {
        // Store combined order id in session
        Session::put('combined_order_id', $combined_order_id);

        $combined_order = CombinedOrder::findOrFail($combined_order_id);

        // COD Order
        if ($cod) {

            foreach ($combined_order->orders as $order) {

                $order->payment_status = 'unpaid';
                $order->payment_details = 'Cash On Delivery';
                $order->save();
            }
            Log::info($order);
            // Send emails
            $this->sendOrderEmails($combined_order_id);
            Log::info($combined_order_id);
            // Clear cart
            if (Auth::check()) {
                Cart::where('user_id', Auth::id())->delete();
            }

            flash(translate("Your order has been placed successfully"))->success();

            Session::forget('orderId');

            return redirect()->route('order_confirmed');
        }

        // ONLINE PAYMENT SUCCESS
        foreach ($combined_order->orders as $order) {

            $order->payment_status = 'paid';
            $order->payment_details = $payment;
            $order->save();

            calculateCommissionAffilationClubPoint($order);
        }
        Log::info('onlinr payment success');
        // Send Emails
        $this->sendOrderEmails($combined_order_id);

        // Clear Cart
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->delete();
        }

        flash(translate("Your order has been placed successfully"))->success();

        return redirect()->route('order_confirmed');
    }

    private function sendOrderEmails($combined_order_id)
    {
        $orders = Order::with([
            'shop.user',
            'orderDetails',
            'user'
        ])->where('combined_order_id', $combined_order_id)->get();

        foreach ($orders as $order) {

            $mailData = [
                'order' => $order,
            ];

            Log::info('onlinr payment success', [$mailData]);
            /*
        |--------------------------------------------------------------------------
        | SELLER EMAIL
        |--------------------------------------------------------------------------
        */
            try {

                if (
                    $order->shop &&
                    $order->shop->user &&
                    !empty($order->shop->user->email)
                ) {

                    $seller_email = trim($order->shop->user->email);

                    Log::info('oDASFGsaS', [$seller_email]);
                    Mail::send(
                        'emails.order-mail',
                        $mailData,
                        function ($message) use ($seller_email, $order) {

                            $message->to($seller_email)
                                ->subject('New Order Received - ' . $order->code);
                        }
                    );
                    Log::info('onlinr payment success', [$seller_email]);


                    \Log::info('Seller mail sent: ' . $seller_email);
                }
            } catch (\Exception $e) {

                \Log::error('Seller mail error: ' . $e->getMessage());
            }

            /*
        |--------------------------------------------------------------------------
        | CUSTOMER EMAIL
        |--------------------------------------------------------------------------
        */
            try {

                if (
                    $order->user &&
                    !empty($order->user->email)
                ) {

                    $customer_email = trim($order->user->email);
                    Log::info('customer_email', [$customer_email]);
                    Mail::send(
                        'emails.order-mail',
                        $mailData,
                        function ($message) use ($customer_email, $order) {

                            $message->to($customer_email)
                                ->subject('Order Confirmation - ' . $order->code);
                        }
                    );
                    Log::info('customer_email', [$customer_email]);
                    \Log::info('Customer mail sent: ' . $customer_email);
                }
            } catch (\Exception $e) {

                \Log::error('Customer mail error: ' . $e->getMessage());
            }

            /*
        |--------------------------------------------------------------------------
        | ADMIN EMAIL
        |--------------------------------------------------------------------------
        */
            try {

                $admin_email = 'manpreetsdev@gmail.com';
                $bcc_email = 'manpreetsdev@gmail.com'; // Set your BCC email here
                Log::info('admin_email', [$admin_email]);
                Mail::send(
                    'emails.order-mail',
                    $mailData,
                    function ($message) use ($admin_email, $order, $bcc_email) {
                        $message->to($admin_email)
                            ->bcc($bcc_email)
                            ->subject('New Order Placed - ' . $order->id);
                    }
                );

                Log::info('admin_emailASDFG', [$admin_email]);

                \Log::info('Admin mail sent: ' . $admin_email);
            } catch (\Exception $e) {

                \Log::error('Admin mail error: ' . $e->getMessage());
            }
        }
    }


    public function creatShiprocket($cod = null)
    {
        if ($cod) {
            $paymenMethod = 'COD';
        } else {
            $paymenMethod = 'Prepaid';
        }
        //$token =  Shiprocket::getToken();
        $user = auth()->user();
        $combined_order_id = Session::get('combined_order_id');
        $order = Order::with(['orderDetails', 'orderDetails.product', 'orderDetails.product.stocks', 'orderDetails.product.taxes', 'shop', 'shop.user'])->where('combined_order_id', $combined_order_id)->first();
        // $order = $order1[0];
        //$shiprocketAddress = Shiprocket::pickup($token)->getLocations();
        $pickupLocation = '';
        if (isset($shiprocketAddress['data']['shipping_address'])) {
            foreach ($shiprocketAddress['data']['shipping_address'] as $address) {
                if ($address['pickup_location'] == $order->shop->user->name) {
                    $pickupLocation = $address['pickup_location'];
                } else {
                    $newLocation = [
                        "pickup_location" => $order->shop->user->name,
                        "name" => $order->shop->user->name,
                        "email" => $order->shop->user->email,
                        "phone" => $order->shop->phone,
                        "address" => $order->shop->address,
                        "city" => $order->shop->city->name,
                        "state" => $order->shop->state->name,
                        "country" => $order->shop->country->name,
                        "pin_code" => $order->shop->postal_code,
                    ];
                    /*
                    $NewPickupLocation = Shiprocket::pickup($token)->addLocation($newLocation);
                    // dd($NewPickupLocation);
                    if(isset($NewPickupLocation['address']['pickup_code'])){
                        $pickupLocation = $NewPickupLocation['address']['pickup_code'];
                    }
                    */
                }
            }
        }
        // dd($pickupLocation);
        if ($order) {
            $address = json_decode($order->shipping_address);
            $orderItems = [];
            $weight = [];
            $height = [];
            $breadth = [];
            $length = [];
            $productPrice = 0;
            $shipping = 0;
            foreach ($order->orderDetails as $o) {
                if ($o->variation == null) {
                    $sku = $o->product->stocks[0]->sku;
                } else {
                    $sku = '1234';
                }
                $orderItems[] = [
                    "name" => $o->product->name,
                    "sku" => $sku,
                    "units" =>  $o->quantity,
                    "selling_price" => real_price($o, $o->product),
                    "discount" => 0,
                    "tax" => 18,
                    "hsn" => $o->product->hsn
                ];
                $productPrice += $o->price;
                $length[] = $o->product->product_length;
                $breadth[] = $o->product->product_breadth;
                $height[] = $o->product->product_height;
                $weight[] = $o->product->product_weight;
                $shipping += $o->shipping_cost;
            }

            $avgLength = array_sum($length) / $order->orderDetails->count();
            $avgBreadth = array_sum($breadth) / $order->orderDetails->count();
            $avgHeight = array_sum($height) / $order->orderDetails->count();
            $avgWeight = array_sum($weight) / $order->orderDetails->count();
            $totalDiscount = discount_amount($o->product_id);
            $orderDetails = [
                "order_id" => $order->code,
                "order_date" => date('Y-m-d H:i:s', $order->date),
                "pickup_location" => $pickupLocation,  // $pickupLocation
                "channel_id" => "",
                "comment" => "New Order",
                "billing_customer_name" => $user->name,
                "billing_last_name" => "",
                "billing_address" => $address->address,
                "billing_address_2" => "",
                "billing_city" => $address->city,
                "billing_pincode" => $address->postal_code,
                "billing_state" => $address->state,
                "billing_country" => $address->country,
                "billing_email" => $address->email,
                "billing_phone" => $address->phone,
                "shipping_is_billing" => true,
                "shipping_customer_name" => "",
                "shipping_last_name" => "",
                "shipping_address" => "",
                "shipping_address_2" => "",
                "shipping_city" => "",
                "shipping_pincode" => "",
                "shipping_country" => "",
                "shipping_state" => "",
                "shipping_email" => "",
                "shipping_phone" => "",
                "order_items" => $orderItems,
                "payment_method" => $paymenMethod,
                "shipping_charges" => $shipping,
                "giftwrap_charges" => 0,
                "transaction_charges" => 0,
                "total_discount" => 0,
                "sub_total" => $order->grand_total - $shipping,
                "length" => $avgLength,
                "breadth" => $avgBreadth,
                "height" => $avgHeight,
                "weight" => $avgWeight
            ];
            //$response =  Shiprocket::order($token)->create($orderDetails);
            if (isset($response['order_id'])) {
                // $order->shiprocket_order_id = $response['order_id'];
                $order->shiprocket_order_id = 0;
                $order->save();
            }
        }
    }
    public function calculate_shipping(Request $request)
    {
        $shipping_info = $request->shipping_info; // address
        $weight = $request->weight;               // total weight
        $cartTotal = $request->cart_total;        // cart total

        $shipping = calculateShipping($weight, $cartTotal);

        return response()->json([
            'shipping_type' => $shipping['type'],
            'shipping_cost' => $shipping['price']
        ]);
    }


    public function get_shipping_info(Request $request)
    {
        $carts = Cart::where('user_id', Auth::user()->id)->get();
        sync_cart_prices($carts);
        //        if (Session::has('cart') && count(Session::get('cart')) > 0) {
        if ($carts && count($carts) > 0) {
            $categories = Category::all();
            return view('frontend.shipping_info', compact('categories', 'carts'));
        }
        flash(translate('Your cart is empty'))->success();
        return back();
    }

    public function store_shipping_info(Request $request)
    {
        if ($request->address_id == null) {
            flash(translate("Please add shipping address"))->warning();
            return back();
        }

        $carts = Cart::where('user_id', Auth::user()->id)->get();
        sync_cart_prices($carts);
        if ($carts->isEmpty()) {
            flash(translate('Your cart is empty'))->warning();
            return redirect()->route('home');
        }

        foreach ($carts as $key => $cartItem) {
            $cartItem->address_id = $request->address_id;
            $cartItem->save();
        }

        $carrier_list = array();
        if (get_setting('shipping_type') == 'carrier_wise_shipping') {
            $zone = \App\Models\Country::where('id', $carts[0]['address']['country_id'])->first()->zone_id;

            $carrier_query = Carrier::query();
            $carrier_query->whereIn('id', function ($query) use ($zone) {
                $query->select('carrier_id')->from('carrier_range_prices')
                    ->where('zone_id', $zone);
            })->orWhere('free_shipping', 1);
            $carrier_list = $carrier_query->get();
        }

        return view('frontend.delivery_info', compact('carts', 'carrier_list'));
    }


    public function store_delivery_info(Request $request)
    {
        try {
            $carts = Cart::where('user_id', Auth::id())->get();
            sync_cart_prices($carts);

            if ($carts->isEmpty()) {
                return back()->with('error', translate('Your cart is empty'));
            }

            // Validate services
            if (!$request->has('selected_services')) {
                return back()->with('error', translate('Please select at least one service'));
            }

            $selectedServices = $request->selected_services;

            // Calculations
            $subtotal = 0;
            $tax = 0;
            $totalWeight = 0;

            foreach ($carts as $cartItem) {
                $product = Product::find($cartItem->product_id);

                if (!$product) {
                    return back()->with('error', translate('Product not found in cart.'));
                }

                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem->quantity;
                $tax += cart_product_tax($cartItem, $product, false) * $cartItem->quantity;
                $totalWeight += ($product->weight ?? 0) * $cartItem->quantity;
            }

            // Services total
            $service_total = 0;
            $service_details = [];

            foreach ($selectedServices as $serviceId) {
                $service = \App\Models\CheckoutService::find($serviceId);

                if (!$service) {
                    return back()->with('error', translate('Some selected service does not exist.'));
                }

                $service_total += $service->price;
                $service_details[] = [
                    'id' => $service->id,
                    'name' => $service->name,
                    'price' => $service->price,
                    'type' => $service->type,
                ];
            }

            // Shipping
            $weight = $totalWeight;

            $shipping = 0;
            $shipping_rates = ShippingRate::where('min_weight', '<=', $weight)
                ->where('max_weight', '>=', $weight)
                ->first();

            // Save shipping into cart
            foreach ($carts as $cartItem) {
                $cartItem->services = json_encode($service_details);
                $cartItem->save();
            }

            $total = $subtotal + $tax + $shipping + $service_total;

            return view('frontend.payment_select', compact(
                'carts',
                'subtotal',
                'tax',
                'shipping',
                'total',
                'totalWeight',
                'shipping_rates',
                'service_total',
                'service_details'
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function getShippingRate($weight, $total_order_value, $shipping_rates)

    {

        // Determine parcel size based on weight (example: assume small parcel for weights <= 2kg)

        $parcel_size = ($weight <= 2) ? 'small_parcel' : 'medium_box';

        // Calculate shipping rate

        foreach ($shipping_rates as $rate) {

            if ($rate->name == $parcel_size . '_standard') {

                if ($total_order_value > $rate->free_threshold) {

                    return 0;
                } else {

                    return $rate->rate;
                }
            }
        }
    }
    public function apply_coupon_code(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)->first();
        $response_message = array();
        $subtotal = 0;
        $tax = 0;
        $shipping = 0;
        $total = 0;
        $coupon_discount = 0;
        $cart_item_qty = 0;
        $cart_product_id = 0;
        $cart_product_price = 0;
        $carts = Cart::where('user_id', Auth::user()->id)->get();
        foreach ($carts as $key => $cartItem) {
            $product = Product::find($cartItem['product_id']);
            $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
            $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
            // $shipping += $cartItem['shipping_cost'];
        }

        if ($coupon != null) {
            if (strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date) {
                if (CouponUsage::where('user_id', Auth::user()->id)->where('coupon_id', $coupon->id)->first() == null) {
                    $coupon_details = json_decode($coupon->details);

                    if ($coupon->type == 'cart_base') {
                        $sum = $subtotal;
                        if ($sum >= $coupon_details->min_buy) {
                            if ($coupon->discount_type == 'percent') {
                                $coupon_discount = ($sum * $coupon->discount) / 100;
                                if ($coupon_discount > $coupon_details->max_discount) {
                                    $coupon_discount = $coupon_details->max_discount;
                                }
                            } elseif ($coupon->discount_type == 'amount') {
                                $coupon_discount = $coupon->discount;
                            }
                        }
                    } elseif ($coupon->type == 'product_base') {
                        foreach ($carts as $key => $cartItem) {
                            $product = Product::find($cartItem['product_id']);
                            foreach ($coupon_details as $key => $coupon_detail) {
                                if ($coupon_detail->product_id == $cartItem['product_id']) {
                                    if ($coupon->discount_type == 'percent') {
                                        $cart_product_price = cart_product_price($cartItem, $product, false, false);
                                        $cart_item_qty = $cartItem['quantity'];
                                        $coupon_discount += ($cart_product_price * $coupon->discount / 100) * $cart_item_qty;
                                        $cart_product_id = $coupon_detail->product_id;
                                    } elseif ($coupon->discount_type == 'amount') {
                                        $cart_product_price = cart_product_price($cartItem, $product, false, false);
                                        $cart_item_qty = $cartItem['quantity'];
                                        $coupon_discount += $coupon->discount * $cartItem['quantity'];
                                        $cart_product_id = $coupon_detail->product_id;
                                    }
                                }
                            }
                        }
                    }
                    if ($coupon_discount > 0) {
                        $coupon_discount2 = $coupon_discount / $cart_item_qty;
                        $taxAbleAmount = $cart_product_price - $coupon_discount2;
                        $tax = CartUtility::tax_calculation(0, $taxAbleAmount);
                        $cartt =  Cart::where('product_id', $cart_product_id)
                            ->update(
                                [
                                    'discount' => $coupon_discount,
                                    'tax' => $tax,
                                    'coupon_code' => $request->code,
                                    'coupon_applied' => 1
                                ]
                            );
                        $tax_total = $tax * $cart_item_qty;
                        $response_message['response'] = 'success';
                        $response_message['message'] = translate('Coupon has been applied');
                        $response_message['cartt'] = $cartt;
                    } else {
                        $response_message['response'] = 'warning';
                        $response_message['message'] = translate('This coupon is not applicable to your cart products!');
                    }
                } else {
                    $response_message['response'] = 'warning';
                    $response_message['message'] = translate('You already used this coupon!');
                }
            } else {
                $response_message['response'] = 'warning';
                $response_message['message'] = translate('Coupon expired!');
            }
        } else {
            $response_message['response'] = 'danger';
            $response_message['message'] = translate('Invalid coupon!');
        }

        //$total = ($subtotal + $tax + $shipping) - $coupon_discount;
        $total = $subtotal + $shipping;

        if ($total < 0) {
            $total = 0;
        }

        $shipping_info = null;
        if (count($carts) > 0) {
            $shipping_info = Address::where('id', $carts[0]['address_id'])->first();
        }
        $carts = Cart::where('user_id', Auth::user()->id)->get();
        $returnHTML = view('frontend.' . get_setting('homepage_select') . '.partials.cart_summary', compact('coupon', 'carts', 'shipping_info', 'subtotal', 'tax', 'shipping', 'total', 'coupon_discount'))->render();
        return response()->json(array('response_message' => $response_message, 'html' => $returnHTML));
    }

    public function remove_coupon_code(Request $request)
    {
        Cart::where('user_id', Auth::user()->id)
            ->update(
                [
                    'discount' => 0.00,
                    'coupon_code' => '',
                    'coupon_applied' => 0
                ]
            );

        $coupon = Coupon::where('code', $request->code)->first();
        $carts = Cart::where('user_id', Auth::user()->id)
            ->get();

        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();

        return view('frontend.' . get_setting('homepage_select') . '.partials.cart_summary', compact('coupon', 'carts', 'shipping_info'));
    }

    public function apply_club_point(Request $request)
    {
        if (addon_is_activated('club_point')) {

            $point = $request->point;

            if (Auth::user()->point_balance >= $point) {
                $request->session()->put('club_point', $point);
                flash(translate('Point has been redeemed'))->success();
            } else {
                flash(translate('Invalid point!'))->warning();
            }
        }
        return back();
    }

    public function remove_club_point(Request $request)
    {
        $request->session()->forget('club_point');
        return back();
    }

    public function order_confirmed()
    {
        $combined_order = CombinedOrder::findOrFail(Session::get('combined_order_id'));

        Cart::where('user_id', $combined_order->user_id)
            ->delete();

        //Session::forget('club_point');
        //Session::forget('combined_order_id');

        // foreach($combined_order->orders as $order){
        //     NotificationUtility::sendOrderPlacedNotification($order);
        // }

        return view('frontend.order_confirmed', compact('combined_order'));
    }

    public function destroy($id)
    {
        $address = Address::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if (!$address) {
            flash(translate('Address not found'))->warning();
            return back();
        }

        // Prevent deleting default if only one exists
        $totalAddresses = Address::where('user_id', Auth::id())->count();

        if ($totalAddresses <= 1) {
            flash(translate('At least one address is required'))->warning();
            return back();
        }

        $address->delete();

        flash(translate('Address deleted successfully'))->success();

        return back();
    }
}
