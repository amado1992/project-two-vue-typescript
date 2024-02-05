<?php

namespace Modules\Quotes\Application;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\Common\DataTables\DataTablePaginator;
use Modules\Quotes\Entities\Quote;

/**
 * @author Abel David.
 */
class PaginateQuotesUseCase
{
    /**
     * @param Builder|null $builder
     * @return DataTablePaginator
     */
    public function __invoke(?Builder $builder = null): DataTablePaginator
    {


            if (!$builder) {

                $builder = Quote::query();
            
            }

            $client_id = Auth::user()->client_id;

            if ($client_id)
            {
                $builder = Quote::query()->withWhereHas('project', function ($query) use ($client_id) {
                    $query->where('client_id', $client_id);
                });
            }

            if($builder){
                $builder = $builder->orderBy('date','desc');
            }

        return (new DataTablePaginator($builder))
            ->addCustomQuery('project', function (Builder $builder, string $filter) {

                $builder->join('projects', 'quotes.project_id', 'projects.id')
                    ->orWhere('projects.name', 'LIKE', "%$filter%");
            });
    }
}
