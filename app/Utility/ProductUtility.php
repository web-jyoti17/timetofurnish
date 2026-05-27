<?php

namespace App\Utility;

use App\Models\Addon;
use App\Models\Color;

class ProductUtility
{
    public static function get_attribute_options($collection)
    {
        $options = array();
        if (
            isset($collection['colors_active']) &&
            $collection['colors_active'] &&
            $collection['colors'] &&
            count($collection['colors']) > 0
        ) {
            $colors_active = 1;
            array_push($options, $collection['colors']);
        }

        if (isset($collection['choice_no']) && $collection['choice_no']) {
            foreach ($collection['choice_no'] as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = array();
                foreach ((array) request()->input($name, []) as $key => $eachValue) {
                    array_push($data, self::choice_value($eachValue));
                }
                array_push($options, $data);
            }
        }

        return $options;
    }

    public static function get_combination_string($combination, $collection)
    {
        $str = '';
        foreach ($combination as $key => $item) {
            if ($key > 0) {
                $str .= '-' . str_replace(' ', '', $item);
            } else {
                if (isset($collection['colors_active']) && $collection['colors_active'] && $collection['colors'] && count($collection['colors']) > 0) {
                    $color = Color::where('code', $item)->first();
                    if ($color) {
                        $color_name = $color->name;
                        $str .= $color_name;
                    } else {
                        $str .= str_replace(' ', '', $item);
                    }
                } else {
                    $str .= str_replace(' ', '', $item);
                }
            }
        }
        return $str;
    }

    public static function choice_value($value): string
    {
        if (is_array($value)) {
            return trim((string) ($value['value'] ?? $value['label'] ?? ''));
        }

        if (is_object($value)) {
            return trim((string) ($value->value ?? $value->label ?? ''));
        }

        return trim((string) $value);
    }

    public static function choice_value_sort_order($value, int $fallback = 0): int
    {
        if (is_array($value) && isset($value['sort_order'])) {
            return (int) $value['sort_order'];
        }

        if (is_object($value) && isset($value->sort_order)) {
            return (int) $value->sort_order;
        }

        return $fallback;
    }

    public static function variant_metadata(array $combination, $collection): array
    {
        $choiceNos = collect($collection['choice_no'] ?? [])->values();
        $choiceNames = collect(request()->input('choice', []))->values();
        $metadata = [];

        foreach ($combination as $combinationIndex => $value) {
            $cleanValue = self::choice_value($value);
            $matched = null;

            foreach ($choiceNos as $choiceIndex => $attributeId) {
                $field = 'choice_options_' . $attributeId;
                $values = collect(request()->input($field, []))->map(function ($item) {
                    return self::choice_value($item);
                })->values();

                if ($values->contains($cleanValue)) {
                    $matched = [
                        'attribute_id' => $attributeId,
                        'attribute_name' => trim((string) ($choiceNames->get($choiceIndex) ?: get_single_attribute_name($attributeId))),
                        'attribute_sort_order' => $choiceIndex,
                        'value_sort_order' => $values->search($cleanValue),
                    ];
                    break;
                }
            }

            $metadata[] = [
                'attribute_id' => $matched['attribute_id'] ?? null,
                'attribute_name' => $matched['attribute_name'] ?? '',
                'value' => $cleanValue,
                'attribute_sort_order' => (int) ($matched['attribute_sort_order'] ?? $combinationIndex),
                'value_sort_order' => (int) ($matched['value_sort_order'] ?? $combinationIndex),
            ];
        }

        return $metadata;
    }
}
