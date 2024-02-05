<?php

namespace Modules\ProductCategories\Application;

use Modules\ProductCategories\Entities\ProductCategory;
use Modules\ProductCategories\Events\DeletingProductCategory;
use Modules\ProductCategories\Events\ProductCategoryDeleted;

/**
 * @author cheynerpb.
 */
class DeleteProductCategoryUseCase
{
    public function __invoke(ProductCategory $productCategory): bool
    {
        DeletingProductCategory::dispatch($productCategory);

        if ($productCategory->children()->count()) {

            return false;
        }

        $productCategory->delete();

        ProductCategoryDeleted::dispatch($productCategory);

        return true;
    }
}
