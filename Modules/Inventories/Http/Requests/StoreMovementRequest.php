<?php

namespace Modules\Inventories\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Inventories\MovementTypes\DecrementMovementType;
use Modules\Inventories\MovementTypes\IncrementMovementType;

/**
 * @author Abel David.
 */
class StoreMovementRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'date' => ['required', 'date_format:Y-m-d'],
            'reason_id' => ['required', Rule::exists('reasons', 'id')],
            'type' => ['required', Rule::in([
                IncrementMovementType::name(),
                DecrementMovementType::name()
            ])],
            'observations' => ['nullable', 'string'],
            'products' => ['required', 'array'],
            'products.*.id' => ['required', Rule::exists('products', 'id')],
            'products.*.quantity' => ['required', 'numeric', 'min:1']
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'products.required' => __('You must select one product at least'),
            'products.*.quantity' => __('validation.min', ['attribute' => __('fields.quantity'), 'min' => 1]),
        ];
    }
}
