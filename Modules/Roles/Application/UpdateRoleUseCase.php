<?php

namespace Modules\Roles\Application;

use Illuminate\Support\Collection;
use Modules\Roles\Entities\Role;
use Modules\Roles\Events\RoleUpdated;
use Modules\Roles\Events\UpdatingRole;
use Modules\Roles\Http\Requests\UpdateRoleRequest;

/**
 * Update a role.
 *
 * @author Abel David.
 */
class UpdateRoleUseCase extends RoleUseCase
{
    /**
     * @param UpdateRoleRequest $request
     * @param Role $role
     * @return bool
     */
    public function __invoke(UpdateRoleRequest $request, Role $role): bool
    {
        $permissions = $this->getActivePermissions(collect($request->input('modules')));

        $oldRole = clone $role;
        $role->forceFill([
            'name' => $request->input('name')
        ]);

        $rolePermissions = $role->permissions()->pluck('name');
        $permissions = $permissions->pluck('name');

        $role->syncPermissions($permissions);

        $permissionsAreNotEquals = $this->permissionsAreNotEquals($permissions, $rolePermissions);

        if ($role->isDirty() || $permissionsAreNotEquals) {

            $role->fill([
                'updated_by' => auth()->user()->getAuthIdentifier()
            ]);

            UpdatingRole::dispatch($role);
        }

        $role->save();

        $wasChanged = $role->wasChanged() || $permissionsAreNotEquals;

        if ($wasChanged) {

            RoleUpdated::dispatch($role, $oldRole);
        }

        return $wasChanged;
    }

    /**
     * @param Collection $permissions
     * @param Collection $rolePermissions
     * @return bool
     */
    private function permissionsAreNotEquals(Collection $permissions, Collection $rolePermissions): bool
    {
        if ($permissions->count() != $rolePermissions->count()) {

            return true;
        }

        $permissions = $permissions->sort();
        $rolePermissions = $rolePermissions->sort();

        for ($i = 0; $i < $permissions->count(); $i++) {

            if ($permissions[$i] != $rolePermissions[$i]) {

                return true;
            }
        }

        return false;
    }
}
