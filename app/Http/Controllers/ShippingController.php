<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;

use App\Models\ShippingRate;
 
class ShippingController extends Controller

{

    public function calculateShipping(Request $request)

    {

        $shipping_info = [

            // Your shipping info array here

        ];
 
        $weight = 1.5; // Example weight

        $total_order_value = 100; // Example total order value
 
        $shipping_rates = ShippingRate::all();

        $shipping_rate = $this->getShippingRate($weight, $total_order_value, $shipping_rates);
 
        return view('shipping', compact('shipping_info', 'weight', 'total_order_value', 'shipping_rate', 'shipping_rates'));

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

}
 