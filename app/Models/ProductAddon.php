<?php
namespace App\Models;
use App;
use Illuminate\Database\Eloquent\Model;

class ProductAddon extends Model
{
    protected $table = 'product_addons';
    protected $fillable = ['product_id', 'name', 'sort_order'];

    public function options()
    {
        return $this->hasMany(ProductAddonOption::class)
            ->orderBy('sort_order', 'asc');
    }
}
