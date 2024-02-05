<?php

namespace Modules\Contracts\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Contracts\Entities\Contract;

/**
 * @author Abel David.
 *
 * @property Contract $contract
 */
class StoreContractReturnRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'return_date' => ['required', 'date_format:Y-m-d'],
            'book' => ['required', 'numeric'],
            'products' => ['required', 'array', function(string $attribute, array $value, Closure $fail) {

                $has_value = false;

                foreach ($value as $item) {

                    if ($item['mesu'] > 0 || $item['rented'] > 0) {

                        $has_value = true;
                        break;
                    }
                }

                if (! $has_value) {

                    $fail('contracts::validation.return_min')->translate();
                }
            }],
            'products.*.id' => ['required', Rule::exists('products')],
            'products.*.mesu' => Rule::forEach(function (string|null $value, string $attribute, array $data) {

                $index = substr($attribute, 9, 1);

                $product = $this->contract->products()->find($data['products.' . $index . '.id']);

                $rules = [
                    'required',
                    'numeric'
                ];

                if ($value && $product) {
                    $max = $product->pivot->mesu_delivered - $product->pivot->mesu_return;
                    $rules[] = 'lte:'.$max;
                }

                return $rules;
            }),
            'products.*.rented' => Rule::forEach(function (string|null $value, string $attribute, array $data) {

                $index = substr($attribute, 9, 1);

                $product = $this->contract->products()->find($data['products.' . $index . '.id']);

                $rules = [
                    'required',
                    'numeric'
                ];

                if ($value && $product) {
                    $max = $product->pivot->re_rent_delivered - $product->pivot->re_rent_return;
                    $rules[] = 'lte:'.$max;
                }

                return $rules;
            })
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
            'products.*.mesu.min' => __('contracts::validation.return_min'),
            'products.*.rented.min' => __('contracts::validation.return_min')
        ];
    }
}
