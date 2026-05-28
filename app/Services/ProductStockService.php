<?php

namespace App\Services;

use AizPackages\CombinationGenerate\Services\CombinationService;
use App\Models\ProductStock;
use App\Utility\ProductUtility;
use Illuminate\Validation\ValidationException;

class ProductStockService
{
    public function validateVariantPrices(array $data): void
    {
        $collection = collect($data);
        $options = ProductUtility::get_attribute_options($collection);
        
        $combinations = array();
        foreach ($options as $option_group) {
            if (is_array($option_group)) {
                foreach ($option_group as $value) {
                    $combinations[] = [$value];
                }
            }
        }

        if (count($combinations) > 0) {
            $errors = [];
            foreach ($combinations as $combination) {
                $str = ProductUtility::get_combination_string($combination, $collection);
                $field_key = str_replace('.', '_', $str);
                $price_key = 'price_' . $field_key;

                $price = request()->input($price_key);
                if (is_null($price) || trim((string)$price) === '' || !is_numeric($price) || (float) $price <= 0) {
                    $errors[$price_key] = [translate('Variant price is required for') . ' ' . $str];
                }
            }

            if (!empty($errors)) {
                throw ValidationException::withMessages($errors);
            }
        }
    }

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
        $saved_count = 0;
        $has_expected_combinations = count($combinations) > 0;

        if ($has_expected_combinations) {
            foreach ($combinations as $key => $combination) {
                $str = ProductUtility::get_combination_string($combination, $collection);
                $field_key = str_replace('.', '_', $str);
                $price_key = 'price_' . $field_key;

                $price = request()->input($price_key);
                
                // Enforce that we MUST have a valid price for all combinations if variations are present
                if (is_null($price) || trim((string)$price) === '' || !is_numeric($price) || (float) $price <= 0) {
                    throw ValidationException::withMessages([
                        $price_key => [translate('Variant price is required and must be greater than 0 for') . ' ' . $str]
                    ]);
                }

                $qty_key = 'qty_' . $field_key;
                $sku_key = 'sku_' . $field_key;
                $img_key = 'img_' . $field_key;

                $product_stock = new ProductStock();
                $product_stock->product_id = $product->id;
                $product_stock->variant = $str;
                $product_stock->price = $price;
                $product_stock->sku = request()->input($sku_key);
                $product_stock->qty = request()->filled($qty_key) ? request()->input($qty_key) : 0;
                $product_stock->image = request()->input($img_key);
                $product_stock->save();

                save_product_stock_attributes($product_stock, $combination, $collection);
                $saved_count++;
            }
        }

        if ($saved_count > 0) {
            $product->variant_product = 1;
            $product->save();
        } else {
            // Save as a simple product ONLY if no expected combinations were submitted at all
            if ($has_expected_combinations) {
                throw ValidationException::withMessages([
                    'choice_no' => [translate('Variant combinations require prices to be entered.')]
                ]);
            }

            $product->variant_product = 0;
            $product->save();

            unset($collection['colors_active'], $collection['colors'], $collection['choice_no']);
            $qty = $collection['current_stock'] ?? 0;
            $price = is_numeric($collection->get('unit_price')) ? $collection->get('unit_price') : 0;
            unset($collection['current_stock']);

            $data = $collection->merge(compact('variant', 'qty', 'price'))->toArray();
            $data['product_id'] = $product->id;
            
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

            duplicate_product_stock_attributes($stock->id, $product_stock->id, $product_new->id);
        }
    }
}
