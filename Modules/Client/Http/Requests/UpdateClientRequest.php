<?php

namespace Modules\Client\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('clients', 'name')->ignore($this->client)],
            'ruc' => ['required', Rule::unique('clients', 'ruc')->ignoreModel($this->client)],
            'dv' => ['required', 'numeric', 'digits:2', 'min:00', 'max:99'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'regex:/^[0-9]+$/'],
            'mobile' => ['nullable', 'regex:/^[0-9]+$/'],
            'contacts' => ['nullable', 'array'],
            'contacts.*.name' => ['required', 'string', 'max:255'],
            'contacts.*.phone' => ['required', 'regex:/^[0-9]+$/'],
            'ficha' => ['nullable', 'numeric'],
            'redi' => ['nullable', 'numeric'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
