<?php

namespace Modules\Inventories\Application;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Modules\Common\DataTables\DataTablePaginator;
use Modules\Inventories\Entities\Movement;

/**
 * @author Abel David.
 */
class PaginateMovementsUseCase
{
    /**
     * @param Builder|null $builder
     * @return DataTablePaginator
     */
    public function __invoke(?Builder $builder = null): DataTablePaginator
    {
        if (! $builder) {

            $builder = Movement::query();
        }

        return (new DataTablePaginator($builder, 'date', true))
            ->addCustomQuery('reason', function (Builder $builder, string $filter) {

                $builder->join('reasons', 'movements.reason_id', 'reasons.id')
                    ->orWhere('reasons.name', 'LIKE', "%$filter%");
            })
            ->addCustomQuery('type', function (Builder $builder, string $filter) {

                $translation = __('inventories::types.'.strtolower($filter));

                $builder->orWhere('type', 'LIKE', "%$translation%");
            });
    }
}
