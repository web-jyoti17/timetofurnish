<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStockAttribute extends Model
{
    protected $fillable = [
        'product_id',
        'product_stock_id',
        'attribute_id',
        'attribute_name',
        'attribute_value',
        'attribute_sort_order',
        'value_sort_order',
        'user_id',
        'category_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productStock()
    {
        return $this->belongsTo(ProductStock::class);
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}
