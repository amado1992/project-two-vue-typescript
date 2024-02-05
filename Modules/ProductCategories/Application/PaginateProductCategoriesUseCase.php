<?php

namespace Modules\ProductCategories\Application;

use Modules\Common\DataTables\DataTablePaginator;
use Modules\ProductCategories\Entities\ProductCategory;

/**
 * @author cheynerpb.
 */
class PaginateProductCategoriesUseCase
{
    /**
     * @return DataTablePaginator
     */
    public function __invoke(): DataTablePaginator
    {
        return new DataTablePaginator(ProductCategory::with(['created_by', 'updated_by', 'father']));
    }
}
