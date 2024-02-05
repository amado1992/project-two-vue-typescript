<?php

namespace Modules\Payments\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Client\Entities\Client;
use Modules\Contracts\Entities\Invoice;

/**
 * @author Abel David.
 */
class StorePaymentRequest extends FormRequest
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
            'client_id' => ['required', Rule::exists('clients', 'id')],
            'invoices' => ['required', 'array'],
            'invoices.*.id' => ['required', Rule::exists('invoices', 'id')],
            'invoices.*.credit' => Rule::forEach(function (int $value, string $attribute, array $data) {

                $index = substr($attribute, 9, 1);
                $idKey = 'invoices.'.$index.'.id';

                $rules = [
                    'required',
                    'numeric',
                    'gt:0'
                ];

                if (isset($data[$idKey])) {

                    if ($invoice = Invoice::find($data[$idKey])) {

                        $rules[] = 'max:'.$invoice->per_to_pay;
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
