<?php

namespace Modules\Designs\Application;

use Abdavid92\LaravelQuasarPaginator\DataTablePaginator;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\Designs\Entities\Design;

/**
 * @author Abel David.
 */
class PaginateDesignsUseCase
{
    /**
     * @param Builder|null $builder
     * @return DataTablePaginator
     */
    public function __invoke(?Builder $builder = null): DataTablePaginator
    {
        if (!$builder) {

            $builder = Design::query()->join('quotes', 'designs.quote_id', 'quotes.id')->orderBy('quotes.date','desc');
        }

        $client_id = Auth::user()->client_id;

        $quotesJoined = false;
        $projectsJoined = false;
        $usersJoined = false;

        if ($client_id)
        {
            $builder->join('quotes', 'designs.quote_id', 'quotes.id')
                ->join('projects', 'quotes.project_id', 'projects.id')
                ->where('projects.client_id', $client_id)->orderBy('quotes.date','desc');

            $quotesJoined = true;
            $projectsJoined = true;
        }

        $wasQuery = false;

        $columnQuery = function (Builder $builder, string $filter) use (&$quotesJoined, &$projectsJoined, &$usersJoined, &$wasQuery) {

            //TODO: review
            /*if ($quotesJoined == false) {

                $builder->join('quotes', 'designs.quote_id', 'quotes.id')->orderByDesc('quotes.date');

                $quotesJoined = true;
            }*/

            if ($projectsJoined == false) {
                $builder->join('projects', 'quotes.project_id', 'projects.id');
                $projectsJoined = true;
            }

            if ($usersJoined == false) {

                $builder->join('users', 'quotes.user_id', 'users.id');
                $usersJoined = true;
            }

            $builder->where(function (Builder $builder) use ($filter) {

                $builder->orWhere('users.name', 'LIKE', "%$filter%")
                    ->orWhere('projects.name', 'LIKE', "%$filter%")
                    ->orWhere('designs.id', $filter);
            });

            $wasQuery = true;
        };


        return (new DataTablePaginator($builder))
            ->addCustomFilter('commercial', function (Builder $builder, string $filter) use (&$wasQuery, $columnQuery) {

                if (! $wasQuery) {

                    $columnQuery($builder, $filter);
                }
            })
            ->addCustomFilter('project', function (Builder $builder, string $filter) use (&$wasQuery, $columnQuery) {

                if (! $wasQuery) {

                    $columnQuery($builder, $filter);
                }
            })
            ->addCustomFilter('id', function (Builder $builder, string $filter) use (&$wasQuery, $columnQuery) {

                if (! $wasQuery) {

                    $columnQuery($builder, $filter);
                }
            });
    }
}
