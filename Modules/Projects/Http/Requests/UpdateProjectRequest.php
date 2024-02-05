<?php

namespace Modules\Projects\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Projects\Entities\Project;

/**
 * @author Abel David.
 *
 * @property Project $project
 */
class UpdateProjectRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('projects')->ignore($this->project->id)
            ],
            'address' => ['required', 'string', 'max:255'],
            'project_manager' => ['required', 'string', 'max:255'],
            'project_manager_phone' => ['required', 'string', 'max:255', 'regex:/^[0-9]+$/'],
            'construction_manager' => ['required', 'string', 'max:255'],
            'construction_manager_phone' => ['required', 'string', 'max:255', 'regex:/^[0-9]+$/'],
            'in_charge_to_pay' => ['required', 'string', 'max:255'],
            'in_charge_to_pay_phone' => ['required', 'string', 'max:255', 'regex:/^[0-9]+$/'],
            'client_id' => ['required', Rule::exists('clients', 'id')]
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
