<?php

namespace Modules\Contracts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Products\Entities\Product;

/**
 * @author Abel David.
 */
class StartContractRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'products' => ['required', 'array'],
            'products.*.id' => ['required', Rule::exists('products', 'id')],
            'products.*.quantity' => ['required', 'numeric'],
            'products.*.mesu' => Rule::forEach(function (string|null $value, string $attribute, array $data) {

                $index = substr($attribute, 9, 1);

                $idKey = 'products.'.$index.'.id';

                $rules = [
                    'required',
                    'numeric'
                ];

                if (isset($data[$idKey])) {

                    $id = $data[$idKey];

                    if ($product = Product::find($id)) {

                        $rules[] = 'max:'.$product->inventory->stock;
                    }
                }

                $rentedKey = 'products.'.$index.'.rented';
                $quantityKey = 'products.'.$index.'.quantity';

                if (isset($data[$rentedKey]) && isset($data[$quantityKey])) {

                    $quantity = $data[$quantityKey];
                    $rented = $data[$rentedKey];

                    $rules[] = 'min:'.($quantity - $rented);
                } else {

                    $rules[] = 'min:0';
                }

                return $rules;
            }),
            'products.*.rented' => Rule::forEach(function (string|null $value, string $attribute, array $data) {

                $index = substr($attribute, 9, 1);

                $idKey = 'products.'.$index.'.id';

                $rules = [
                    'required',
                    'numeric'
                ];

                if (isset($data[$idKey])) {

                    $id = $data[$idKey];

                    if ($product = Product::find($id)) {

                        $rules[] = 'max:'.$product->inventory->re_stock;
                    }
                }

                $mesuKey = 'products.'.$index.'.mesu';
                $quantityKey = 'products.'.$index.'.quantity';

                if (isset($data[$mesuKey]) && isset($data[$quantityKey])) {

                    $quantity = $data[$quantityKey];
                    $mesu = $data[$mesuKey];

                    $rules[] = 'min:'.($quantity - $mesu);
                } else {

                    $rules[] = 'min:0';
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
            'products.*.mesu.max' => __('contracts::validation.mesu_max'),
            'products.*.mesu.min' => __('contracts::validation.start_min'),
            'products.*.rented.min' => __('contracts::validation.start_min')
        ];
    }
}
