<?php

namespace App\Utility;

use App\Models\Cart;
use Cookie;

class CartUtility
{

    public static function create_cart_variant($product, $request)
    {
        $selected_attributes = [];

        foreach (get_product_stock_choices($product) as $choice) {
            $field = 'attribute_id_' . $choice->attribute_id;
            if (isset($request[$field]) && !empty($request[$field])) {
                $val = trim($request[$field]);
                if (preg_match('/^#[A-Fa-f0-9]{3,8}$/', $val)) {
                    $color = \App\Models\Color::where('code', $val)->first();
                    if ($color) {
                        $val = $color->name;
                    }
                }
                $selected_attributes[] = str_replace(' ', '', $val);
            }
        }

        return implode('-', $selected_attributes);
    }

    public static function get_price($product, $product_stock, $quantity)
    {
        $price = $product_stock->price;
        if ($product->auction_product == 1) {
            $price = $product->bids->max('amount');
        }

        if ($product->wholesale_product) {
            $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $quantity)
                ->where('max_qty', '>=', $quantity)
                ->first();
            if ($wholesalePrice) {
                $price = $wholesalePrice->price;
            }
        }

        $price = self::discount_calculation($product, $price);
        return $price;
    }

    public static function discount_calculation($product, $price)
    {
        if (function_exists('get_product_active_offer') && get_product_active_offer($product)) {
            return $price;
        }

        $discount_applicable = false;

        if (
            $product->discount_start_date == null ||
            (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date)
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
        return $price;
    }

    public static function tax_calculation($product, $price)
    {
        $tax = 0; 
        $vat = env('toat_val', 0);
        $tax_portion = ($price * $vat) / 100;
        $tax = $tax_portion;
         /*foreach ($product->taxes as $product_tax) {
           if ($product_tax->tax_type == 'percent') {
                $tax_portion = ($price * $product_tax->tax) / (100 + $product_tax->tax);
                $tax += $tax_portion;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            } coomented by Amandeep Singh 3/2/20
            
        }*/
        return $tax;
    }

    public static function save_cart_data($cart, $product, $price, $tax, $quantity)
    {
        $cart->quantity = $quantity;
        $cart->product_id = $product->id;
        $cart->owner_id = $product->user_id;
        $cart->price = $price;
        $cart->tax = $tax;
        $cart->product_referral_code = null;

        if (Cookie::has('referred_product_id') && Cookie::get('referred_product_id') == $product->id) {
            $cart->product_referral_code = Cookie::get('product_referral_code');
        }

        // Cart::create($data);
        $cart->save();
    }

    public static function check_auction_in_cart($carts)
    {
        foreach ($carts as $cart) {
            if ($cart->product->auction_product == 1) {
                return true;
            }
        }

        return false;
    }
}
