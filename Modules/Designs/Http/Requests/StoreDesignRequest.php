<?php

namespace Modules\Designs\Http\Requests;

use Modules\Quotes\Http\Requests\StoreQuoteRequest;

/**
 * @author Abel David.
 */
class StoreDesignRequest extends StoreQuoteRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'files' => ['nullable', 'array'],
            'files.*' => ['file', 'max:2048']
        ]);
    }
}
