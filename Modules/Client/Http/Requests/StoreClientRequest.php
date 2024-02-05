<?php

namespace Modules\Client\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreClientRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('clients', 'name')],
            'ruc' => ['required', Rule::unique('clients', 'ruc')],
            'dv' => ['required', 'numeric', 'digits:2','min:00', 'max:99'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'regex:/^[0-9]+$/'],
            'mobile' => ['nullable', 'regex:/^[0-9]+$/'],
            'legal_representative' => ['nullable', 'regex:/^[a-zA-Z\s\']+$/'],
            'cedula' => ['nullable', 'regex:/^[a-zA-Z0-9-]+$/'],
            'contacts' => ['nullable', 'array'],
            'contacts.*.name' => ['required', 'string', 'max:255'],
            'contacts.*.phone' => ['required', 'regex:/^[0-9]+$/'],
            'ficha' => ['nullable', 'numeric'],
            'redi' => ['nullable', 'numeric'],
        ];
    }

}
