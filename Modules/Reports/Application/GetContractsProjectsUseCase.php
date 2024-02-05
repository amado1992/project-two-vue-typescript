<?php

namespace Modules\Reports\Application;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Modules\Client\Entities\Client;
use Modules\Common\DataTables\DataTablePaginator;
use Modules\Projects\Entities\Project;

/**
 * @author ylc.
 */
class GetContractsProjectsUseCase
{
    /**
     * proyectos relacionados con los clientes filtrados.
     * @return Collection<Clients>
     */
    public function __invoke($clients_arr = null): Collection
    {
        if (!$clients_arr) {
            return Project::all();
        }
        return Project::whereIn('client_id', $clients_arr)->get();
        /* and whereIn('id',$projects_selected) */
    }
}
