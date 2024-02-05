<?php

namespace Modules\Products\Application;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Modules\Common\DataTables\DataTablePaginator;
use Modules\Products\Entities\Product;

/**
 * @author cheynerpb.
 */
class PaginateProductsUseCase
{
    /**
     * @return DataTablePaginator
     */
    public function __invoke(): DataTablePaginator
    {
        return (new DataTablePaginator(Product::with(['created_by', 'updated_by', 'productCategory'])))
            ->addCustomQuery('created_by', function (Builder $builder, string $filter) {

                $builder->join('users', 'products.created_by', 'users.id')
                    ->orWhere('users.name', 'LIKE', "%$filter%");
            });
    }
}
