<?php

namespace Modules\Reports\Application;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Modules\Client\Entities\Client;
use Modules\Common\DataTables\DataTablePaginator;

/**
 * @author ylc.
 */
class ReadFiltersClientsUseCase
{
    /**
     * Must return all productos filters for product report.
     * @return Collection<Clients>
     */
    public function __invoke($arr = null): DataTablePaginator
    {
        if ($arr != null) {

            $builder = Client::whereIn('id', $arr);
        } else {
            $builder = Client::query();
        }


        return new DataTablePaginator($builder);
    }
}
