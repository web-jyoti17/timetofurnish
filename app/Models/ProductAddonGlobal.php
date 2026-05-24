<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAddonGlobal extends Model
{
    protected $table = 'product_addons_global';

    protected $fillable = [
        'name',
        'sort_order'
    ];

    // Relationship: One Addon has many options
    public function options()
    {
        return $this->hasMany(ProductAddonOptionGlobal::class, 'product_addon_id')
                    ->orderBy('sort_order', 'asc');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_addon_global_category', 'product_addon_global_id', 'category_id');
    }
}
