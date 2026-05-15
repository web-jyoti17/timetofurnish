<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionHistory extends Model
{
   public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Customer (who placed the order)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Seller
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
