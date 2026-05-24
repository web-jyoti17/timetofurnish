<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class ProductStockCollection extends Collection
{
    public function firstWhere($key, $operator = null, $value = null)
    {
        if ($key === 'variant') {
            $val = (func_num_args() === 2) ? $operator : $value;
            return $this->findVariant($val);
        }
        return parent::firstWhere(...func_get_args());
    }

    public function where($key, $operator = null, $value = null)
    {
        if ($key === 'variant') {
            $val = (func_num_args() === 2) ? $operator : $value;
            $stock = $this->findVariant($val);
            return $stock ? new static([$stock]) : new static([]);
        }
        return parent::where(...func_get_args());
    }

    protected function findVariant($variant)
    {
        if ($variant === null) {
            return null;
        }

        // Try exact match first
        $exact = $this->first(function ($item) use ($variant) {
            return $item->variant === $variant;
        });
        if ($exact) {
            return $exact;
        }

        // Handle flat combined string fallback (e.g. 'double4ftbed-Ehb')
        $parts = array_filter(explode('-', $variant));
        if (count($parts) > 1) {
            $matching = $this->filter(function ($item) use ($parts) {
                return in_array($item->variant, $parts);
            });

            if ($matching->count() > 0) {
                $first = $matching->first();
                $virtual = clone $first;
                $virtual->exists = false; // It's a virtual model
                
                $price = 0;
                $qty = 9999;
                $sku_parts = [];
                foreach ($matching as $m) {
                    $price += floatval($m->price);
                    $qty = min($qty, intval($m->qty));
                    $sku_parts[] = $m->sku;
                }

                $virtual->price = $price;
                $virtual->qty = $qty;
                $virtual->sku = implode('-', $sku_parts);
                $virtual->variant = $variant;

                // Sync original attributes so save/dirty detection works
                $virtual->setRawAttributes([
                    'id' => $first->id,
                    'product_id' => $first->product_id,
                    'variant' => $variant,
                    'price' => $price,
                    'qty' => $qty,
                    'sku' => $virtual->sku,
                ], true);

                return $virtual;
            }
        }

        return null;
    }
}
