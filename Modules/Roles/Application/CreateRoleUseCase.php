<?php

namespace Modules\Roles\Application;

use Illuminate\Support\Collection;
use Modules\Roles\Entities\Role;
use Modules\Roles\Events\CreatingRole;
use Modules\Roles\Events\RoleCreated;
use Modules\Roles\Http\Requests\StoreRoleRequest;

/**
 * @author Abel David.
 */
class CreateRoleUseCase extends RoleUseCase
{
    /**
     * @param StoreRoleRequest $request
     * @return Role
     */
    public function __invoke(StoreRoleRequest $request): Role
    {
        CreatingRole::dispatch();

        $modules = collect($request->input('modules'));

        $permissions = $this->getActivePermissions($modules);

        $role = Role::create([
            'name' => $request->input('name'),
            'created_by' => auth()->user()->getAuthIdentifier(),
        ]);

        $role->syncPermissions($permissions->pluck('name'));

        RoleCreated::dispatch($role);

        return $role;
    }
}
