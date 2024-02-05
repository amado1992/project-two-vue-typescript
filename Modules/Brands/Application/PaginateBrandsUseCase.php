<?php

namespace Modules\Brands\Application;

use Modules\Common\DataTables\DataTablePaginator;
use Modules\Brands\Entities\Brand;

/**
 * @author cheynerpb.
 */
class PaginateBrandsUseCase
{
    /**
     * @return DataTablePaginator
     */
    public function __invoke(): DataTablePaginator
    {
        return new DataTablePaginator(Brand::with(['created_by', 'updated_by']));
    }
}
