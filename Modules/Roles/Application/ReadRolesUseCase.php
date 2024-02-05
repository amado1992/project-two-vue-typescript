<?php

namespace Modules\Roles\Application;

use Illuminate\Support\Collection;
use Modules\Roles\Entities\Role;

/**
 * @author Abel David.
 */
class ReadRolesUseCase
{
    /**
     * @return Collection<Role>
     */
    public function __invoke(): Collection
    {
        return Role::all();
    }
}
