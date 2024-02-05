<?php

namespace Modules\Projects\Application;

use Modules\Projects\Entities\Project;

/**
 * @author Abel David.
 */
class GetProjectsCountUseCase
{
    /**
     * @return int
     */
    public function __invoke(): int
    {
        return Project::query()->count();
    }
}
