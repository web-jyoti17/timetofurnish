<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use Auth;
use App\Utility\CartUtility;
use Session;
use Cookie;
use App\Models\ProductAddon;
use App\Models\ProductAddonOption;

class CartController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            if ($request->session()->get('temp_user_id')) {
                Cart::where('temp_user_id', $request->session()->get('temp_user_id'))
                    ->update(
                        [
                            'user_id' => $user_id,
                            'temp_user_id' => null
                        ]
                    );

                Session::forget('temp_user_id');
            }
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            // $carts = Cart::where('temp_user_id', $temp_user_id)->get();
            $carts = ($temp_user_id != null) ? Cart::where('temp_user_id', $temp_user_id)->get() : [];
        }

        return view('frontend.view_cart', compact('carts'));
    }

    public function showCartModal(Request $request)
    {
        $product = Product::find($request->id);
        return view('frontend.'.get_setting('homepage_select').'.partials.addToCart', compact('product'));
    }

    public function showCartModalAuction(Request $request)
    {
        $product = Product::find($request->id);
        return view('auction.frontend.addToCartAuction', compact('product'));
    }

    public function addToCart(Request $request)
    {
        // dd($request->all());
        $carts = Cart::where('user_id', auth()->user()->id)->get();
        $check_auction_in_cart = CartUtility::check_auction_in_cart($carts);
        $product = Product::find($request->id);
        $carts = array();

        if($check_auction_in_cart && $product->auction_product == 0) {
            return array(
                'status' => 0,
                'cart_count' => count($carts),
                'modal_view' => view('frontend.'.get_setting('homepage_select').'.partials.removeAuctionProductFromCart')->render(),
                'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
            );
        }

        $quantity = $request['quantity'];

        if ($quantity < $product->min_qty) {
            return array(
                'status' => 0,
                'cart_count' => count($carts),
                'modal_view' => view('frontend.'.get_setting('homepage_select').'.partials.minQtyNotSatisfied', ['min_qty' => $product->min_qty])->render(),
                'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
            );
        }

        //check the color enabled or disabled for the product
        $str = CartUtility::create_cart_variant($product, $request->all());

        $product_stock = $product->stocks->where('variant', $str)->first();

        $cart = Cart::firstOrNew([
            'variation' => $str,
            'user_id' => auth()->user()->id,
            'product_id' => $request['id']
        ]);

        if ($cart->exists && $product->digital == 0) {
            if ($product->auction_product == 1 && ($cart->product_id == $product->id)) {
                return array(
                    'status' => 0,
                    'cart_count' => count($carts),
                    'modal_view' => view('frontend.'.get_setting('homepage_select').'.partials.auctionProductAlredayAddedCart')->render(),
                    'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
                );
            }
            if ($product_stock->qty < $cart->quantity + $request['quantity']) {
                return array(
                    'status' => 0,
                    'cart_count' => count($carts),
                    'modal_view' => view('frontend.'.get_setting('homepage_select').'.partials.outOfStockCart')->render(),
                    'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
                );
            }
            $quantity = $cart->quantity + $request['quantity'];
        }

        $price = CartUtility::get_price($product, $product_stock, $request->quantity);

        //shivani  (addon code)
        $addons = [];

        if ($request->has('addons')) {

            foreach ($request->addons as $addon_id => $option_ids) {

                // Get addon (e.g. "Assembly Required")
                $addon = ProductAddon::find($addon_id);

                if (!$addon) continue;

                if (!is_array($option_ids) && !is_object($option_ids)) {
                    $option_ids = !empty($option_ids) ? [$option_ids] : [];
                }
                foreach ($option_ids as $option_id) {

                    // Get selected option (e.g. "Yes")
                    $option = ProductAddonOption::find($option_id);

                    if (!$option) continue;

                    $addons[] = [
                        'addon_id'   => $addon->id,
                        'addon_name' => $addon->name,          // "Assembly Required"
                        'name'       => $option->option_name,  // "Yes"
                        'price'      => (float) $option->price,
                        'image'      => $option->img,
                    ];
                }

            }
            // SAVE ATTRIBUTES AS DISPLAYABLE OPTIONS
                foreach ($request->all() as $key => $value) {

                    if (str_starts_with($key, 'attribute_id_')) {

                        $attributeId = str_replace('attribute_id_', '', $key);

                        $attribute = \App\Models\Attribute::find($attributeId);

                        if ($attribute) {

                            // create variant key exactly like product stock
                            $variantKey = str_replace(' ', '', $value);

                            // find matching stock
                            $variantStock = $product->stocks->where('variant', $variantKey)->first();

                            // get variant price
                            $variantPrice = $variantStock ? (float)$variantStock->price : 0;

                            $addons[] = [
                                'addon_id'   => 'attribute_' . $attributeId,
                                'addon_name' => $attribute->getTranslation('name'),
                                'name'       => $value,
                                'price'      => $variantPrice,
                                'image'      => null,
                            ];

                        }

                    }

                }
            $jsonAddons = json_encode($addons);
            $addon_total = collect($addons)->sum('price');
            $price += $addon_total;
            $cart->price = $price;
            $cart->addon_price = $addon_total;
            $cart->addons = $jsonAddons;
        }

        //shivani  (addon code)
        $tax = CartUtility::tax_calculation($product, $price);

        CartUtility::save_cart_data($cart, $product, $price, $tax, $quantity);



        \Log::info([$cart, $product, $price, $tax, $quantity]);
        $carts = Cart::where('user_id', auth()->user()->id)->get();
        return array(
            'status' => 1,
            'cart_count' => count($carts),
            'modal_view' => view('frontend.'.get_setting('homepage_select').'.partials.addedToCart', compact('product', 'cart'))->render(),
            'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
        );
    }

    //removes from Cart
    public function removeFromCart(Request $request)
    {
        Cart::destroy($request->id);
        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        return array(
            'cart_count' => count($carts),
            'cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart_details', compact('carts'))->render(),
            'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
        );
    }

    //updated the quantity for a cart item
    public function updateQuantity(Request $request)
    {
        $cartItem = Cart::findOrFail($request->id);

        if ($cartItem['id'] == $request->id) {
            $product = Product::find($cartItem['product_id']);
            $product_stock = $product->stocks->where('variant', $cartItem['variation'])->first();
            $cartItem['discount'] = 0;
            $cartItem['coupon_applied'] = 0;
            $cartItem['coupon_code'] = '';
            $quantity = $product_stock->qty;
            $price = $product_stock->price;

            //discount calculation
            $discount_applicable = false;

            if ($product->discount_start_date == null) {
                $discount_applicable = true;
            } elseif (
                strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
            ) {
                $discount_applicable = true;
            }

            if ($discount_applicable) {
                if ($product->discount_type == 'percent') {
                    $price -= ($price * $product->discount) / 100;
                } elseif ($product->discount_type == 'amount') {
                    $price -= $product->discount;
                }
            }

            if ($quantity >= $request->quantity) {
                if ($request->quantity >= $product->min_qty) {
                    $cartItem['quantity'] = $request->quantity;
                }
            }

            if ($product->wholesale_product) {
                $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $request->quantity)->where('max_qty', '>=', $request->quantity)->first();
                if ($wholesalePrice) {
                    $price = $wholesalePrice->price;
                }
            }
            $tax = CartUtility::tax_calculation(0, $price);

            $cartItem['tax'] = $tax;

            $cartItem['price'] = $price;
            $cartItem->save();
        }

        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        return array(
            'cart_count' => count($carts),
            'cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart_details', compact('carts'))->render(),
            'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
        );
    }
}
