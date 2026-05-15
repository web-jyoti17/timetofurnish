<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckoutService extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'price',
        'description',
        'status',
        'sort_order',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'checkout_service_product'
        );
    }
    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'checkout_service_categories'
        );
    }
}
