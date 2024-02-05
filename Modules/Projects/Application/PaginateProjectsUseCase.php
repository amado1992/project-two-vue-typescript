<?php

namespace Modules\Projects\Application;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Modules\Common\DataTables\DataTablePaginator;
use Modules\Projects\Entities\Project;

/**
 * @author Abel David.
 */
class PaginateProjectsUseCase
{
    /**
     * @param Builder|null $builder
     * @return DataTablePaginator
     */
    public function __invoke(?Builder $builder = null): DataTablePaginator
    {
        if (! $builder) {

            $builder = Project::query()->orderByDesc('created_at');
        }

        return (new DataTablePaginator($builder))
            ->addCustomQuery('client', function (Builder $builder, string $filter) {

                $builder->join('clients', 'projects.client_id', 'clients.id')
                    ->orWhere('clients.name', 'LIKE', "%$filter%");
            });
    }
}
