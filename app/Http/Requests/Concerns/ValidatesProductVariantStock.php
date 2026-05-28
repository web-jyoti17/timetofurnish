<?php

namespace App\Http\Requests\Concerns;

use App\Models\Attribute;
use App\Models\Color;

trait ValidatesProductVariantStock
{
    protected function addSellerVariantStockRules(array $rules): array
    {
        if (!auth()->check()) {
            return $rules;
        }

        // Only enforce variant-related rules if variations are actively selected
        $hasChoiceNo = $this->has('choice_no') && is_array($this->input('choice_no')) && count($this->input('choice_no')) > 0;
        $hasColors = $this->boolean('colors_active') && is_array($this->input('colors')) && count($this->input('colors')) > 0;

        if ($hasChoiceNo || $hasColors) {
            if ($hasChoiceNo) {
                $rules['choice_no'] = 'required|array|min:1';

                foreach ((array) $this->input('choice_no', []) as $choiceNo) {
                    $rules['choice_options_' . $choiceNo] = 'required|array|min:1';
                }
            }

            foreach ($this->expectedVariantStockFields() as $fields) {
                $rules[$fields['price']] = ['required', 'numeric', 'gt:0', 'max:99999'];
                $rules[$fields['qty']] = ['nullable', 'integer', 'min:1', 'max:9999'];
                $rules[$fields['sku']] = ['nullable', 'max:255'];
            }
        }

        $hasVariants = count($this->expectedVariantStockFields()) > 0;

        foreach ($this->all() as $key => $value) {
            if (str_starts_with($key, 'price_')) {
                $rules[$key] = [$hasVariants ? 'required' : 'nullable', 'numeric', 'gt:0', 'max:99999'];
            }

            if (str_starts_with($key, 'qty_')) {
                $rules[$key] = ['nullable', 'integer', 'min:1', 'max:9999'];
            }
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!auth()->check()) {
                return;
            }

            $fields = $this->expectedVariantStockFields();

            if (count($fields) > 0) {
                foreach ($fields as $field) {
                    if (!$this->filled($field['price'])) {
                        $validator->errors()->add(
                            $field['price'],
                            translate('Variant price is required for') . ' ' . $field['label']
                        );
                    }
                }
            }
        });
    }

    protected function addVariantStockMessages(array $messages): array
    {
        foreach ($this->expectedVariantStockFields() as $fields) {
            $messages[$fields['price'] . '.required'] = translate('Variant price is required for') . ' ' . $fields['label'];
            $messages[$fields['price'] . '.numeric'] = translate('Variant price must be numeric for') . ' ' . $fields['label'];
            $messages[$fields['price'] . '.gt'] = translate('Variant price must be greater than 0 for') . ' ' . $fields['label'];
            $messages[$fields['qty'] . '.integer'] = translate('Variant quantity must be a whole number for') . ' ' . $fields['label'];
            $messages[$fields['qty'] . '.min'] = translate('Variant quantity must be greater than 0 for') . ' ' . $fields['label'];
        }

        return $messages;
    }

    protected function addVariantStockAttributes(array $attributes): array
    {
        foreach ($this->expectedVariantStockFields() as $fields) {
            $attributes[$fields['price']] = translate('variant price for') . ' ' . $fields['label'];
            $attributes[$fields['qty']] = translate('variant quantity for') . ' ' . $fields['label'];
            $attributes[$fields['sku']] = translate('variant SKU for') . ' ' . $fields['label'];
        }

        foreach ($this->all() as $key => $value) {
            if (str_starts_with($key, 'price_')) {
                $attributes[$key] = $attributes[$key] ?? translate('variant price');
            }

            if (str_starts_with($key, 'qty_')) {
                $attributes[$key] = $attributes[$key] ?? translate('variant quantity');
            }
        }

        return $attributes;
    }

    protected function addChoiceOptionMessages(array $messages): array
    {
        foreach ((array) $this->input('choice_no', []) as $choiceNo) {
            $attribute = Attribute::find($choiceNo);
            $label = $attribute ? $attribute->getTranslation('name') : translate('attribute');
            $messages['choice_options_' . $choiceNo . '.required'] = translate('Please select at least one value for') . ' ' . $label;
            $messages['choice_options_' . $choiceNo . '.min'] = translate('Please select at least one value for') . ' ' . $label;
        }

        return $messages;
    }

    protected function addChoiceOptionAttributes(array $attributes): array
    {
        foreach ((array) $this->input('choice_no', []) as $choiceNo) {
            $attribute = Attribute::find($choiceNo);
            $label = $attribute ? $attribute->getTranslation('name') : translate('attribute');
            $attributes['choice_options_' . $choiceNo] = translate('values for') . ' ' . $label;
        }

        return $attributes;
    }

    protected function expectedVariantStockFields(): array
    {
        $fields = [];

        foreach ($this->expectedVariantNames() as $variant) {
            $key = str_replace('.', '_', $variant);
            $fields[] = [
                'label' => $variant,
                'price' => 'price_' . $key,
                'qty' => 'qty_' . $key,
                'sku' => 'sku_' . $key,
            ];
        }

        return $fields;
    }

    protected function expectedVariantNames(): array
    {
        $variants = [];
        $colorsActive = $this->boolean('colors_active');

        if ($colorsActive && is_array($this->input('colors')) && count($this->input('colors')) > 0) {
            foreach ($this->input('colors') as $colorCode) {
                $variants[] = $this->variantNameFromValue($colorCode, true);
            }
        }

        foreach ((array) $this->input('choice_no', []) as $choiceNo) {
            foreach ((array) $this->input('choice_options_' . $choiceNo, []) as $value) {
                $variants[] = $this->variantNameFromValue($value, $colorsActive);
            }
        }

        return array_values(array_unique(array_filter($variants, function ($variant) {
            return trim((string) $variant) !== '';
        })));
    }

    protected function hasAnyVariantPrice(array $fields): bool
    {
        foreach ($fields as $field) {
            if ($this->filled($field['price'])) {
                return true;
            }
        }

        return false;
    }

    protected function variantNameFromValue($value, bool $colorsActive): string
    {
        if ($colorsActive) {
            $color = Color::where('code', $value)->first();

            if ($color) {
                return $color->name;
            }
        }

        return str_replace(' ', '', (string) $value);
    }
}
