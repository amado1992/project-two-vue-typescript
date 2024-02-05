<?php

namespace Modules\Contracts\Application;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Common\DataTables\DataTablePaginator;
use Modules\Contracts\Entities\Contract;

/**
 *
 */
class PaginateContractsUseCase
{
    /**
     * @param Builder|null $builder
     * @return DataTablePaginator
     */

     public function __invoke(?Builder $builder = null, ?Request $request = null)
    {

        if (!$builder) {
        $input = null;
        if($request){
            $input = $request->input("filter");

        }
        $builder = Contract::with(
            ['project' => function ($query){
                $query->join('clients','projects.client_id','clients.id'
                )->select('projects.id',
                    'projects.name',
                    'clients.name as client_name',
                    'projects.created_by',
                    'projects.updated_by',
                );

            },
            'commercial' => function ($query) {
                $query->select('id','name');
            },
            'created_by'=>function ($query) {
                $query->select('id','name');
            },
            'updated_by'=>function ($query) {
                $query->select('id','name');
            },
            'returns',
            'products'
            ]
        )->without(['quote','renovations']);//->without(['quote','products','renovations']);

        if($input){
            $builder->join('projects','contracts.project_id','projects.id')
            ->join('clients','projects.client_id','clients.id')
                    ->where('clients.name','LIKE',"%$input%");



        }
    }
        $client_id = Auth::user()->client_id;


        if ($client_id) {

            $builder = Contract::query()->withWhereHas('project', function ($query) use ($client_id) {
                $query->where('client_id', $client_id);
            });
        }



        return (new DataTablePaginator(
            $builder
        ));


    }





}
