<?php

namespace Modules\Payments\Application;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Modules\Common\DataTables\DataTablePaginator;
use Modules\Payments\Entities\Payment;

/**
 * @author Abel David.
 */
class PaginatePaymentsUseCase
{
    /**
     * @param Builder|null $builder
     * @return DataTablePaginator
     */
    public function __invoke(?Builder $builder = null): DataTablePaginator
    {
        if (! $builder) {

            $builder = Payment::query();
        }

        return new DataTablePaginator($builder);
    }
}
