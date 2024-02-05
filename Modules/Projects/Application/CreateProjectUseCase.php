<?php

namespace Modules\Projects\Application;

use Modules\Projects\Entities\Project;
use Modules\Projects\Events\CreatingProject;
use Modules\Projects\Events\ProjectCreated;

/**
 * @author Abel David.
 */
class CreateProjectUseCase
{
    /**
     * @param array $data
     * @return Project
     */
    public function __invoke(array $data): Project
    {
        $data['created_by'] = auth()->user()->getAuthIdentifier();

        CreatingProject::dispatch();

        $project = Project::create($data);

        ProjectCreated::dispatch($project);

        return $project;
    }
}
