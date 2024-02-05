<?php

namespace Modules\Products\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'product_category_id' => ['required', 'exists:product_categories,id'],
            'type' => ['required'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'daily_price' => ['required', 'numeric', 'min:0'],
            'weekly_price' => ['required', 'numeric', 'min:0'],
            'biweekly_price' => ['required', 'numeric', 'min:0'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'replacement_price' => ['required', 'numeric', 'min:0'],
            'tax' => ['required', 'numeric', 'min:0']
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
