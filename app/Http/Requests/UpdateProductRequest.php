<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use App\Models\Attribute;
use App\Http\Requests\Concerns\ValidatesProductVariantStock;


class UpdateProductRequest extends FormRequest
{
    use ValidatesProductVariantStock;

    protected function prepareForValidation()
    {
        if (!$this->has('unit_price') || trim((string) $this->input('unit_price')) === '') {
            $this->merge(['unit_price' => 0]);
        }

        if (
            !$this->boolean('discount_enabled') ||
            !$this->has('discount') ||
            trim((string) $this->input('discount')) === '' ||
            (float) $this->input('discount') <= 0
        ) {
            $this->merge(['discount' => null]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [];
      $rules['name'] = 'required|string|max:255|regex:/^[A-Za-z0-9\s\-(),+&*]+$/';


        $rules['category_ids']  = 'required';
        $rules['category_id']   = ['required', Rule::in($this->category_ids)];

        $rules['min_qty']      = 'sometimes|required|numeric|max:99999';
        $rules['unit_price'] = [
            'nullable',
            'numeric',
            'min:0',
            'max:99999',
        ];
        $rules['thumbnail_img']='required';
        $rules['discount'] = ['nullable', 'numeric', 'min:0'];
        if ($this->filled('discount') && $this->filled('unit_price') && (float) $this->input('unit_price') > 0) {
            $rules['discount'][] = 'lt:unit_price';
        }
        $rules['unit'] = 'required|integer|between:1,10';


        $rules['current_stock'] = 'sometimes|nullable|numeric|min:1';
        $rules['starting_bid']  = 'sometimes|required|numeric|min:1';
        $rules['auction_date_range']  = 'sometimes|required';
        $rules['weight'] ='nullable|max:4';
        $rules['low_stock_quantity'] ='nullable|max:3';
        $rules['description'] = 'nullable';
        $rules['specification'] = 'nullable';
        $rules['dimensions_enabled'] = 'nullable|boolean';

        $rules = $this->addSellerVariantStockRules($rules);



    foreach ($this->all() as $key => $value) {

        if (str_starts_with($key, 'sku_')) {

            // sku_red-large → red-large
            $variant = str_replace('sku_', '', $key);

            // find existing stock for this variant
            $stock = $this->product->stocks
                        ->where('variant', $variant)
                        ->first();

            $rules[$key] = [
                'nullable',
                'max:255',
            ];
        }
    }




        return $rules;
    }

    public function messages()
    {
        $messages = [
            'name.required'             => translate('Product name is required'),
            'category_ids.required'     => translate('Product category is required'),
            'category_id.required'      => translate('Main Category is required'),
            'category_id.in'            => translate('Main Category must be within selected categories'),
            'unit.required'             => translate('Product unit is required'),
            'min_qty.required'          => translate('Minimum purchase quantity is required'),
            'min_qty.numeric'           => translate('Minimum purchase must be numeric'),
            'description.required'      => translate('Description is required'),
            'specification.required'    => translate('Specification is required'),
            'unit_price.required'       => translate('Unit price is required'),
            'unit_price.numeric'        => translate('Unit price must be numeric'),
            'discount.required'         => translate('Discount is required'),
            'discount.numeric'          => translate('Discount must be numeric'),
            'discount.lt:unit_price'    => translate('Discount cannot be gretaer than unit price'),
            'current_stock.required'    => translate('Current stock is required'),
            'current_stock.numeric'     => translate('Current stock must be numeric'),
            'starting_bid.required'     => translate('Starting Bid is required'),
            'starting_bid.numeric'      => translate('Starting Bid must be numeric'),
            'starting_bid.required'     => translate('Minimum Starting Bid is 1'),
            'auction_date_range.required' => translate('Auction Date Range is required'),
            'choice_no.required' => translate('At least one product attribute is required'),
            'price_*.required' => translate('Variant price is required'),
            'price_*.gt' => translate('Variant price must be greater than 0'),
            'qty_*.required' => translate('Variant quantity is required'),
            'qty_*.min' => translate('Variant quantity must be greater than 0'),


            'unit'=> translate( 'Unit  must be a 10-digit number'),
             'min_qty'                 =>  translate( 'Minimum Purchase Qty must be a 5-digit number'),
            'low_stock_quantity'         =>translate('Low stock quantity must be a 3-digit number'),
            'weight'                     =>translate('weight must be a 4 digit number'),
            'unit_price'                 =>translate(' unit price must be a 5 digit number'),


            'thumbnail_img.required'  => translate('Display Image is required'),
            'photos.required'=> translate('Gallery Image is required'),

           // 'sku.unique' => translate('SKU already exists'),
        ];

        return $this->addVariantStockMessages($this->addChoiceOptionMessages($messages));
    }

    public function attributes()
    {
        $attributes = [];

        return $this->addVariantStockAttributes($this->addChoiceOptionAttributes($attributes));
    }

    /**
     * Get the error messages for the defined validation rules.*
     * @return array
     */
    public function failedValidation(Validator $validator)
    {
        // dd($this->expectsJson());
        if ($this->expectsJson()) {
            throw new HttpResponseException(response()->json([
                'message' => translate('Please fix the highlighted errors.'),
                'errors' => $validator->errors(),
                'result' => false
            ], 422));
        } else {
            throw (new ValidationException($validator))
                ->errorBag($this->errorBag)
                ->redirectTo($this->getRedirectUrl());
        }
    }
}
