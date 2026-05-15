<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAddonOptionGlobal extends Model
{
    protected $table = 'product_addon_options_global';

    protected $fillable = [
        'product_addon_id',
        'option_name',
        'price',
        'img',
        'sort_order'

    ];

    // Relationship: Option belongs to addon
    public function addon()
    {
        return $this->belongsTo(ProductAddonGlobal::class, 'product_addon_id');
    }
}
