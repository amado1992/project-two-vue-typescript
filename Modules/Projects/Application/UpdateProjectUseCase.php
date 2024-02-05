<?php

namespace Modules\Projects\Application;

use Modules\Projects\Entities\Project;
use Modules\Projects\Events\ProjectUpdated;
use Modules\Projects\Events\UpdatingProject;

/**
 * @author Abel David.
 */
class UpdateProjectUseCase
{
    /**
     * @param array $data
     * @param Project $project
     * @return bool
     */
    public function __invoke(array $data, Project $project): bool
    {
        $oldProject = clone $project;

        $project->fill($data);

        if ($project->isDirty()) {

            $project->fill([
                'updated_by' => auth()->user()->getAuthIdentifier()
            ]);

            UpdatingProject::dispatch($project);
        }

        $project->save();

        $wasChanged = $project->wasChanged();

        if ($wasChanged) {

            ProjectUpdated::dispatch($project, $oldProject);
        }

        return $wasChanged;
    }
}
