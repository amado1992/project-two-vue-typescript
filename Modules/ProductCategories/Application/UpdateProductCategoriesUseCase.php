<?php

namespace Modules\ProductCategories\Application;


use Modules\ProductCategories\Entities\ProductCategory;
use Modules\ProductCategories\Events\ProductCategoryUpdated;
use Modules\ProductCategories\Events\UpdatingProductCategory;
use Modules\ProductCategories\Http\Requests\UpdateProductCategoryRequest;

/**
 * Update a user.
 *
 * @author cheynerpb.
 */
class UpdateProductCategoriesUseCase
{
    /**
     * @param UpdateProductCategoryRequest $request
     * @param ProductCategory $productCategory
     * @return bool
     */
    public function __invoke(UpdateProductCategoryRequest $request, ProductCategory $productCategory): bool
    {
        $oldProductCategory = clone $productCategory;

        $productCategory->forceFill([
            'name' => $request->input('name'),
            'active' => (bool)$request->input('active'),
            'product_category_id' => $request->input('product_category_id')
        ]);

        if ($productCategory->isDirty()) {

            $productCategory->fill([
                'updated_by' => auth()->user()->getAuthIdentifier()
            ]);

            UpdatingProductCategory::dispatch($productCategory);
        }

        $productCategory->save();

        $wasChanged = $productCategory->wasChanged();

        if ($wasChanged) {

            ProductCategoryUpdated::dispatch($productCategory, $oldProductCategory);
        }

        return $wasChanged;
    }
}
