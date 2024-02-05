<?php

namespace Modules\Common\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @author Able David.
 */
abstract class ContractibleRequest extends FormRequest
{
    /**
     * @return array
     */
    protected function productsRules(): array
    {
        return [
            'products' => ['required', 'array'],
            'products.*.id' => ['required', 'distinct', Rule::exists('products', 'id')],
            'products.*.price' => ['required', 'numeric', 'gt:0'],
            'products.*.quantity' => ['required', 'numeric', 'min:1'],
            'products.*.discount' => ['required', 'numeric', 'between:0,100'],
            'products.*.tax' => ['required', 'numeric', 'min:0']
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'products.*.price.required' => __('common::validation.required'),
            'products.*.price.gt' => __('common::validation.gt', ['value' => 'cero']),
            'products.*.quantity.required' => __('common::validation.required'),
            'products.*.quantity.min' => __('common::validation.min:1'),
            'products.*.discount.required' => __('common::validation.required'),
            'products.*.discount.between' => __('common::validation.percentage')
        ];
    }
}
