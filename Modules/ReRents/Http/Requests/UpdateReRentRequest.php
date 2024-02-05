<?php

namespace Modules\ReRents\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Common\Http\Requests\ContractibleRequest;

/**
 * @author Abel David.
 */
class UpdateReRentRequest extends ContractibleRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'provider_id' => ['required', Rule::exists('providers', 'id')],
            'start' => ['required', 'date_format:Y-m-d'],
            'finish' => ['required', 'date_format:Y-m-d'],
            'tax_exempt' => ['required', 'boolean'],
            'observations' => ['nullable', 'string'],
            ...$this->productsRules()
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
