<?php

namespace Modules\Roles\Application;

use Modules\Roles\Entities\Role;
use Modules\Roles\Events\DeletingRole;
use Modules\Roles\Events\RoleDeleted;

/**
 * @author Abel David.
 */
class DeleteRoleUseCase
{
    /**
     * @param Role $role
     * @return bool
     */
    public function __invoke(Role $role): bool
    {
        DeletingRole::dispatch($role);

        $role->delete();

        RoleDeleted::dispatch($role);

        return true;
    }
}
