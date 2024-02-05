<?php

namespace Modules\Contracts\Application;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Modules\Projects\Entities\Project;

/**
 * @author Abel David.
 */
class ReadProjectsUseCase
{
    public function __construct(
        private readonly \Modules\Projects\Application\ReadProjectsUseCase $readProjectsUseCase
    )
    {
        //
    }

    public function __invoke(?int $client_id = null): Collection
    {
        $builder = Project::query();

        $client_id = Auth::user()->client_id;

        if ($client_id) {

            $builder->where('client_id', $client_id);
        }

        return ($this->readProjectsUseCase)($builder);
    }
}
