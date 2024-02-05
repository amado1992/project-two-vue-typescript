<?php

namespace Modules\Bonos\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Bonos\Entities\Bono;

/**
 * @author Abel David.
 * @property Bono $bono
 */
class UpdateBonoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $credit = $this->bono->client->credit;

        $credit = $credit + ($this->input('credit', 0) - $this->bono->credit);

        $min = 1;

        if ($credit <= 0) {

            $min = $this->input('credit', 0) - $credit + 1;
        }

        return [
            'date' => ['required', 'date_format:Y-m-d'],
            'credit' => ['required', 'numeric', 'min:'.$min]
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
