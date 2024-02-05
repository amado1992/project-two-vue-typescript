<?php

namespace Modules\ProductCategories\Application;

use Modules\ProductCategories\Entities\ProductCategory;
use Modules\ProductCategories\Events\CreatingProductCategory;
use Modules\ProductCategories\Events\ProductCategoryCreated;
use Modules\ProductCategories\Http\Requests\StoreProductCategoryRequest;

/**
 * Create a product category.
 *
 * @author cheynerpb.
 */
class CreateProductCategoryUseCase
{
    /**
     * @param StoreProductCategoryRequest $request
     * @return ProductCategory
     */
    public function __invoke(StoreProductCategoryRequest $request): ProductCategory
    {
        CreatingProductCategory::dispatch();

        $productCategory = ProductCategory::create([
            'name' => $request->input('name'),
            'active' => (bool)$request->input('active'),
            'product_category_id' => $request->input('product_category_id'),
            'created_by' => auth()->user()->getAuthIdentifier(),
        ]);

        ProductCategoryCreated::dispatch($productCategory);

        return $productCategory;
    }
}
