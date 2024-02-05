<?php

namespace Modules\Contracts\Http\Requests;

use Closure;
use Illuminate\Validation\Rule;
use Modules\Common\Http\Requests\ContractibleRequest;
use Modules\Products\Entities\PeriodPrices;
use Modules\Quotes\Entities\Quote;

/**
 * @author Abel David.
 */
class StoreContractRequest extends ContractibleRequest
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
            'project_id' => ['required', Rule::exists('projects', 'id')],
            'user_id' => ['required', Rule::exists('users', 'id')],
            'quote_id' => [
                'nullable',
                Rule::exists('quotes', 'id'),
                function (string|null $value, string $attribute, Closure $fail) {

                    $count = Quote::query()
                        ->where('id', $value)
                        ->whereNotNull('contract_id')
                        ->count();

                    if ($count) {

                        $fail()->translate('contracts::validation.quote_has_contract');
                    }
                }],
            'period' => ['required', Rule::in([
                PeriodPrices::DAILY,
                PeriodPrices::WEEKLY,
                PeriodPrices::BIWEEKLY,
                PeriodPrices::MONTHLY
            ])],
            'tax_exempt' => ['required', 'boolean'],
            'warranty_deposit' => ['required', 'numeric'],
            'legal_representative' => ['required', 'string', 'max:255'],
            'legal_representative_id' => ['required', 'string', 'max:255'],
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
