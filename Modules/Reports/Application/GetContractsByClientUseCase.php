<?php

namespace Modules\Reports\Application;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Modules\Client\Entities\Client;
use Modules\Common\DataTables\DataTablePaginator;
use Modules\Contracts\Entities\Contract;
use Modules\Projects\Entities\Project;

/**
 * @author ylc.
 */
class GetContractsByClientUseCase
{
    /**
     * Return actives contracts, filtered by projects and client.
     * @return Collection<Clients>
     */
    public function __invoke($client, $projects_selected = null, $all = null): Collection
    {

        if ($projects_selected) {
            $projects = Project::whereIn('id', $projects_selected)->where('client_id', $client->id)->get();
        } else {
            $projects = Project::where('client_id', $client->id)->get();
        }

        $collection = collect();

        foreach ($projects as $project) {
            $contracts = $project->contracts;
            $contracts->load('products');
            $collection->push($contracts);
        }

        $allContracts = $collection->flatten(2);

        $only_actives_contracts = $allContracts->where('status', 'active');

        if (!$all){

            return  $only_actives_contracts;

        } else {

            return $allContracts;
        }


    }
}
