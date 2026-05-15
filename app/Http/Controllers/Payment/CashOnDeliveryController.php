<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CheckoutController;
use Session;

class CashOnDeliveryController extends Controller
{
    public function pay()
    {
        return (new CheckoutController)->checkout_done(null, null, true);
        // flash(translate("Your order has been placed successfully"))->success();
        // return redirect()->route('order_confirmed');
    }
}
 