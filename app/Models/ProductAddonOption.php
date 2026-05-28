<?php

namespace App\Models;
use App;
use Illuminate\Database\Eloquent\Model;
class ProductAddonOption extends Model
{
    protected $fillable = ['product_addon_id', 'option_name','img', 'price', 'quantity', 'sort_order'];

    public function addon()
    {
        return $this->belongsTo(ProductAddon::class, 'product_addon_id');
    }
}
