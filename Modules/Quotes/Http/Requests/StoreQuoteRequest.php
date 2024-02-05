<?php

namespace Modules\Quotes\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Common\Http\Requests\ContractibleRequest;
use Modules\Products\Entities\PeriodPrices;

class StoreQuoteRequest extends ContractibleRequest
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
            'project_id' => [],
            'client_id' => ['required'],
            'period' => ['required', Rule::in([
                PeriodPrices::DAILY,
                PeriodPrices::WEEKLY,
                PeriodPrices::BIWEEKLY,
                PeriodPrices::MONTHLY
            ])],
            'tax_exempt' => ['required', 'boolean'],
            'observations' => ['nullable', 'string'],
            'approved' => ['required', 'boolean'],
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
