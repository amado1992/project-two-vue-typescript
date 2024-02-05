<?php

namespace Modules\Projects\Application;

use Modules\Projects\Entities\Project;
use Modules\Projects\Events\DeletingProject;
use Modules\Projects\Events\ProjectDeleted;

/**
 * @author Abel David.
 */
class DeleteProjectUseCase
{
    /**
     * @param Project $project
     * @return bool
     */
    public function __invoke(Project $project): bool
    {
        DeletingProject::dispatch($project);

        $deleted = $project->delete() == true;

        if ($deleted) {

            ProjectDeleted::dispatch($project);
        }

        return $deleted;
    }
}
