<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'status',
        'sort_order',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'shipping_charge_categories');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_shipping_charge');
    }
}
