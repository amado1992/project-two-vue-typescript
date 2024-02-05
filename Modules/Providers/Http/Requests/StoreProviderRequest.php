<?php

namespace Modules\Providers\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProviderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('providers', 'name')],
            'phone' => ['required', 'regex:/^[0-9]+$/'],
            'address' => ['required'],
            'ruc' => ['required'],
            'dv' => ['required', 'numeric', 'digits:2','min:00', 'max:99'],
            'contacts' => ['nullable', 'array'],
            'contacts.*.name' => ['required', 'string', 'max:255'],
            'contacts.*.email' => ['required', 'email'],
            'contacts.*.position' => ['required', 'string', 'max:255'],
            'contacts.*.phone' => ['required', 'regex:/^[0-9]+$/'],
            'contacts.*.mobile' => ['required', 'regex:/^[0-9]+$/']
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
