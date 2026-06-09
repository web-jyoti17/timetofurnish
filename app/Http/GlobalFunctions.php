<?php

if (!function_exists('save_product_stock_attributes')) {
    /**
     * Save product stock attributes in the custom relational table.
     *
     * @param  \App\Models\ProductStock  $product_stock
     * @param  array  $combination
     * @param  \Illuminate\Support\Collection|array  $collection
     * @return void
     */
    function save_product_stock_attributes($product_stock, array $combination, $collection)
    {
        $metadata = \App\Utility\ProductUtility::variant_metadata($combination, $collection);

        foreach ($metadata as $item) {
            $attributeId = $item['attribute_id'];
            if ($attributeId < 0) {
                $attributeId = null;
            }

            \App\Models\ProductStockAttribute::create([
                'product_id' => $product_stock->product_id,
                'product_stock_id' => $product_stock->id,
                'attribute_id' => $attributeId,
                'attribute_name' => $item['attribute_name'] ?? get_single_attribute_name($item['attribute_id']),
                'attribute_value' => $item['value'],
                'attribute_sort_order' => $item['attribute_sort_order'] ?? 0,
                'value_sort_order' => $item['value_sort_order'] ?? 0,
                'user_id' => auth()->id(),
                'category_id' => $product_stock->product->categories()->first()->id ?? null,
            ]);
        }
    }
}

if (!function_exists('duplicate_product_stock_attributes')) {
    /**
     * Duplicate mapped variant attributes and values.
     *
     * @param  int  $old_stock_id
     * @param  int  $new_stock_id
     * @param  int  $new_product_id
     * @return void
     */
    function duplicate_product_stock_attributes($old_stock_id, $new_stock_id, $new_product_id)
    {
        $old_attrs = \App\Models\ProductStockAttribute::where('product_stock_id', $old_stock_id)->get();

        foreach ($old_attrs as $old_attr) {
            \App\Models\ProductStockAttribute::create([
                'product_id' => $new_product_id,
                'product_stock_id' => $new_stock_id,
                'attribute_id' => $old_attr->attribute_id,
                'attribute_name' => $old_attr->attribute_name,
                'attribute_value' => $old_attr->attribute_value,
                'attribute_sort_order' => $old_attr->attribute_sort_order,
                'value_sort_order' => $old_attr->value_sort_order,
                'user_id' => $old_attr->user_id,
                'category_id' => $old_attr->category_id,
            ]);
        }
    }
}

if (!function_exists('get_product_choice_values')) {
    /**
     * Overrides and returns product choice values sorted by the seller's custom sort_order.
     *
     * @param  object|array  $choice
     * @return array
     */
    function get_product_choice_values($choice)
    {
        $values = [];

        foreach ((array) ($choice->values ?? []) as $index => $value) {
            $values[] = [
                'value' => \App\Utility\ProductUtility::choice_value($value),
                'sort_order' => \App\Utility\ProductUtility::choice_value_sort_order($value, $index),
            ];
        }

        // Sort variant options by sort_order ascending
        usort($values, function ($left, $right) {
            return ($left['sort_order'] <=> $right['sort_order']) ?: strcmp($left['value'], $right['value']);
        });

        return $values;
    }
}

if (!function_exists('get_product_stock_choices')) {
    /**
     * Get choice options grouped and formatted from product_stock_attributes.
     * Fallback to the product's choice_options if none exist in the relational table.
     *
     * @param  \App\Models\Product  $product
     * @return array
     */
    function get_product_stock_choices($product)
    {
        $attributes = \App\Models\ProductStockAttribute::where('product_id', $product->id)
            ->get();
        if ($attributes->isEmpty()) {
            $decoded = json_decode($product->choice_options ?? '[]');
            $choices = [];
            foreach ((array) $decoded as $choice) {
                if (isset($choice->attribute_id)) {
                    $choices[] = (object) [
                        'attribute_id' => $choice->attribute_id,
                        'name'         => $choice->name ?? get_single_attribute_name($choice->attribute_id),
                        'sort_order'   => $choice->sort_order ?? 0,
                        'values'       => get_product_choice_values($choice),
                    ];
                }
            }
            usort($choices, function ($left, $right) {
                return ((int) ($left->sort_order ?? 0)) <=> ((int) ($right->sort_order ?? 0));
            });
            return $choices;
        }

        $grouped = $attributes->sortBy('attribute_sort_order')->groupBy(function ($item) {
            return $item->attribute_id ? (string) $item->attribute_id : (string) $item->attribute_name;
        });
        $choices = [];

        foreach ($grouped as $groupKey => $items) {
            $namedItem = $items->firstWhere('attribute_name', '!=', '');
            $attributeId = $items->first()->attribute_id;

            if (empty($attributeId) && $namedItem) {
                $attributeId = -abs(crc32($namedItem->attribute_name));
            }

            $name = optional($namedItem)->attribute_name
    ?? ($attributeId ? get_single_attribute_name($attributeId) : null);

            // Detect color attribute
            if (
                empty($name) &&
                $items->first() &&
                preg_match('/^#[A-Fa-f0-9]{3,8}$/', trim($items->first()->attribute_value))
            ) {
                $name = 'Color';
            }

            $values = $items->sortBy('value_sort_order')
                ->unique('attribute_value')
                ->map(function ($item) {
                    return [
                        'value' => $item->attribute_value,
                        'sort_order' => $item->value_sort_order,
                    ];
                })
                ->values()
                ->all();

            $choices[] = (object) [
                'attribute_id' => $attributeId,
                'name'         => $name,
                'values'       => $values,
            ];
        }

        return $choices;
    }
}