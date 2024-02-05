<?php

namespace Modules\Providers\Application;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Modules\Common\DataTables\DataTablePaginator;
use Modules\Providers\Entities\Provider;

/**
 * @author cheynerpb.
 */
class PaginateProvidersUseCase
{
    /**
     * @return DataTablePaginator
     */
    public function __invoke(): DataTablePaginator
    {
        return (new DataTablePaginator(Provider::query(

        )->with([
            'created_by'=>function ($query) {
                $query->select('id','name');
            },
            'updated_by'=>function ($query) {
                $query->select('id','name');
            },
        ])))
            ->addCustomQuery('created_by', function (Builder $builder, string $filter) {

                $builder->join('users', 'providers.created_by', 'users.id')
                    ->orWhere('users.name', 'LIKE', "%$filter%");
            });
    }
}
