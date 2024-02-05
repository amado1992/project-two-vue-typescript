<?php

namespace Modules\Travels\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTravelRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'travel_date' => ['required', 'date_format:Y-m-d'],
            'products' => ['required', 'array',  function($attribute, array $value, Closure $fail) {

                $has_value = false;

                foreach ($value as $item) {

                    if ($item['carried_by_client'] < 0) {
                        $has_value = false;
                        break;
                    }

                    if ($item['carried_by_client'] > 0) {
                        $has_value = true;
                    }
                }

                if (!$has_value) {

                    $fail('travels::validation.return_min')->translate();
                }
            }],

            'products.*.id' => ['required', Rule::exists('products')],
            'products.*.carried_by_client' => Rule::forEach(function ($value, $attribute, array $data) {

        $index = substr($attribute, 9, 1);

        $product = $this->contract->products()->find($data['products.' . $index . '.id']);

        $rules = [
            'required',
            'numeric'
        ];

        if ($value && $product) {
            $max = $product->pivot->quantity - $product->pivot->carried_by_client;
            $rules[] = 'lte:'.$max;
        }
        return $rules;
            })
        ];
    }

    public function messages()
    {
        return [
            'travel_date.required' => 'El campo fecha de viaje es obligatorio.'
        ];
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
}
