<?php

namespace Modules\Projects\Application;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Modules\Common\DataTables\DataTablePaginator;
use Modules\Projects\Entities\Project;

/**
 * @author Abel David.
 */
class ReadProjectsUseCase
{
    /**
     * @param Builder|null $builder
     * @return Collection<Project>
     */
    public function __invoke(?Builder $builder = null): Collection
    {
        if (! $builder) {

            $builder = Project::query();
        }

        return $builder->get();
    }
}
