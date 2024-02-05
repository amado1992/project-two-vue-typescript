<?php

namespace Modules\Bonos\Application;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Modules\Bonos\Entities\Bono;
use Modules\Common\DataTables\DataTablePaginator;

/**
 * @author Abel David.
 */
class PaginateBonosUseCase
{
    /**
     * @param Builder|null $builder
     * @return DataTablePaginator
     */
    public function __invoke(?Builder $builder = null): DataTablePaginator
    {
        if (! $builder) {

            $builder = Bono::query();
        }

        return new DataTablePaginator($builder, 'date', true);
    }
}
