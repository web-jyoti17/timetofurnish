<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    protected $fillable = ['product_id', 'variant', 'sku', 'price', 'qty', 'image'];
    
    public function product(){
    	return $this->belongsTo(Product::class);
    }

    public function wholesalePrices() {
        return $this->hasMany(WholesalePrice::class);
    }

    public function newCollection(array $models = [])
    {
        return new \App\Collections\ProductStockCollection($models);
    }

    public function save(array $options = [])
    {
        if (strpos($this->variant, '-') !== false && !$this->exists) {
            // Virtual stock row saving: propagate changes to all actual flat stocks
            $parts = array_filter(explode('-', $this->variant));
            $actual_stocks = self::where('product_id', $this->product_id)
                                 ->whereIn('variant', $parts)
                                 ->get();
            
            foreach ($actual_stocks as $stock) {
                $diff = intval($this->qty) - intval($this->getOriginal('qty', $this->qty));
                if ($diff != 0) {
                    $stock->qty += $diff;
                    $stock->save();
                }
            }
            return true;
        }
        return parent::save($options);
    }
}
