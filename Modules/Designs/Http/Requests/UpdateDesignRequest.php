<?php

namespace Modules\Designs\Http\Requests;

use Modules\Quotes\Http\Requests\UpdateQuoteRequest;

/**
 * @author Abel David.
 */
class UpdateDesignRequest extends UpdateQuoteRequest
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
            'files.*' => ['file', 'max:2048'],
            'files_to_remove' => ['nullable', 'array']
        ]);
    }
}
