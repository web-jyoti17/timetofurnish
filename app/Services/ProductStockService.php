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
                $product_stock->is_virtual = false; // Explicitly ensure it's not virtual
                $product_stock->product_id = $product->id;
                $product_stock->variant = $str;
                $product_stock->price = $price;
                $product_stock->sku = request()->input($sku_key);
                $product_stock->qty = request()->filled($qty_key) ? request()->input($qty_key) : 0;
                $product_stock->image = request()->input($img_key);
                $product_stock->save();

                // Verification 1: Eloquent model state check
                if (!$product_stock->exists) {
                    $product_stock->exists = true; // force exists flag
                    $product_stock->save();
                    if (!$product_stock->exists) {
                        throw new \Exception("Eloquent model failed to save stock for variant: " . $str);
                    }
                }

                // Verification 2: Direct database existence verification
                if (!ProductStock::where('id', $product_stock->id)->exists()) {
                    $product_stock->save();
                    if (!ProductStock::where('id', $product_stock->id)->exists()) {
                        throw new \Exception("Database insertion check failed for variant: " . $str);
                    }
                }

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
            
            $product_stock = new ProductStock();
            $product_stock->is_virtual = false;
            $product_stock->fill($data);
            $product_stock->save();

            // Verification 1: Eloquent model state check
            if (!$product_stock->exists) {
                throw new \Exception("Eloquent model failed to save simple product stock.");
            }

            // Verification 2: Direct database existence verification
            if (!ProductStock::where('id', $product_stock->id)->exists()) {
                throw new \Exception("Database insertion check failed for simple product stock.");
            }
        }
    }

    public function product_duplicate_store($product_stocks , $product_new)
    {
        foreach ($product_stocks as $key => $stock) {
            $product_stock              = new ProductStock;
            $product_stock->is_virtual  = false;
            $product_stock->product_id  = $product_new->id;
            $product_stock->variant     = $stock->variant;
            $product_stock->price       = $stock->price;
            $product_stock->sku         = $stock->sku;
            $product_stock->qty         = $stock->qty;
            $product_stock->save();

            // Verification 1: Eloquent model state check
            if (!$product_stock->exists) {
                throw new \Exception("Eloquent model failed to duplicate stock for variant: " . $stock->variant);
            }

            // Verification 2: Direct database existence verification
            if (!ProductStock::where('id', $product_stock->id)->exists()) {
                throw new \Exception("Database insertion check failed for duplicated variant: " . $stock->variant);
            }

            duplicate_product_stock_attributes($stock->id, $product_stock->id, $product_new->id);
        }
    }
}
