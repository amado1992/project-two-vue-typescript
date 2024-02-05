<?php

namespace Modules\ReRents\Application;

use Abdavid92\LaravelQuasarPaginator\DataTablePaginator;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Modules\ReRents\Entities\ReRent;

/**
 * @author Abel David.
 */
class PaginateReRentsUseCase
{
    /**
     * @param Builder|null $builder
     * @return DataTablePaginator
     */
    public function __invoke(?Builder $builder = null): DataTablePaginator
    {
        if (! $builder) {

            $builder = ReRent::query();
        }

        return (new DataTablePaginator($builder, 'created_at', true))
            ->addCustomFilter('provider', function (Builder $builder, string $filter) {

                $builder->join('providers', 're_rents.provider_id', 'providers.id')
                    ->orWhere('providers.name', 'LIKE', "%$filter%");
            });
    }
}
