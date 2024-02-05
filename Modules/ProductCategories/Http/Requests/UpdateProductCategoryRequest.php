<?php

namespace Modules\ProductCategories\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\ProductCategories\Entities\ProductCategory;

/**
 * @property ProductCategory $productCategory
 */
class UpdateProductCategoryRequest extends FormRequest
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
                Rule::unique('product_categories', 'name')->ignore($this->productCategory->id)
            ],
            'active' => ['required', 'boolean'],
            'product_category_id' => ['nullable', Rule::exists('product_categories', 'id')]
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
