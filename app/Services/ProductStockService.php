<?php

namespace App\Services;

use AizPackages\CombinationGenerate\Services\CombinationService;
use App\Models\ProductStock;
use App\Utility\ProductUtility;
use Illuminate\Validation\ValidationException;

class ProductStockService
{
    public function store(array $data, $product)
    {
        $collection = collect($data);

        $options = ProductUtility::get_attribute_options($collection);
        
        //Generates the flat combinations of customer choice options
        $combinations = array();
        foreach ($options as $option_group) {
            if (is_array($option_group)) {
                foreach ($option_group as $value) {
                    $combinations[] = [$value];
                }
            }
        }
        
        $variant = '';
        if (count($combinations) > 0) {
            $product->variant_product = 1;
            $product->save();
            $has_variant_price = false;

            foreach ($combinations as $combination) {
                $variant = ProductUtility::get_combination_string($combination, $collection);
                $price_key = 'price_' . str_replace('.', '_', $variant);

                if (request()->filled($price_key)) {
                    $has_variant_price = true;
                    break;
                }
            }

            if (!$has_variant_price) {
                $first_variant = ProductUtility::get_combination_string($combinations[0], $collection);
                $first_price_key = 'price_' . str_replace('.', '_', $first_variant);

                throw ValidationException::withMessages([
                    $first_price_key => translate('Please enter at least one variant price.'),
                ]);
            }

            foreach ($combinations as $key => $combination) {
                $str = ProductUtility::get_combination_string($combination, $collection);
                $field_key = str_replace('.', '_', $str);
                $price_key = 'price_' . $field_key;
                $qty_key = 'qty_' . $field_key;
                $sku_key = 'sku_' . $field_key;
                $img_key = 'img_' . $field_key;

                $product_stock = new ProductStock();
                $product_stock->product_id = $product->id;
                $product_stock->variant = $str;
                $product_stock->price = request()->filled($price_key) ? request()->input($price_key) : 0;
                $product_stock->sku = request()->input($sku_key);
                $product_stock->qty = request()->filled($qty_key) ? request()->input($qty_key) : 0;
                $product_stock->image = request()->input($img_key);
                $product_stock->save();
            }
        } else {
            unset($collection['colors_active'], $collection['colors'], $collection['choice_no']);
            $qty = $collection['current_stock'];
            $price = is_numeric($collection->get('unit_price')) ? $collection->get('unit_price') : 0;
            unset($collection['current_stock']);

            $data = $collection->merge(compact('variant', 'qty', 'price'))->toArray();
            
            ProductStock::create($data);
        }
    }

    public function product_duplicate_store($product_stocks , $product_new)
    {
        foreach ($product_stocks as $key => $stock) {
            $product_stock              = new ProductStock;
            $product_stock->product_id  = $product_new->id;
            $product_stock->variant     = $stock->variant;
            $product_stock->price       = $stock->price;
            $product_stock->sku         = $stock->sku;
            $product_stock->qty         = $stock->qty;
            $product_stock->save();
        }
    }
}
