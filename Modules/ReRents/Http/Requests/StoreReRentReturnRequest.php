<?php

namespace Modules\ReRents\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Products\Entities\Product;
use Modules\ReRents\Entities\ReRent;

/**
 * @author Abel David.
 *
 * @property ReRent $re_rent
 */
class StoreReRentReturnRequest extends FormRequest
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
            'products' => ['required', 'array'],
            'products.*.id' => ['required', Rule::exists('products', 'id')],
            'products.*.quantity' => Rule::forEach(function (string|null $value, string $attribute, array $data) {

                $index = substr($attribute, 9, 1);

                $idKey = 'products.' . $index . '.id';

                $rules = [
                    'required',
                    'min:0',
                    'numeric'
                ];

                if (isset($data[$idKey])) {

                    if ($product = $this->re_rent->products()->find($data[$idKey])) {

                        $max = min(
                            $product->pivot->quantity - $product->pivot->returned,
                            $product->inventory->re_stock
                        );

                        $rules[] = "max:$max";
                    }
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
}
