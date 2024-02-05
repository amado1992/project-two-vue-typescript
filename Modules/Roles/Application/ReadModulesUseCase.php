<?php

namespace Modules\Roles\Application;

use Illuminate\Support\Collection;
use Modules\Roles\Entities\Permission;
use Modules\Roles\Entities\Role;

/**
 * @author Abel David.
 */
class ReadModulesUseCase
{
    /**
     * @param Role|null $role
     * @return Collection
     */
    public function __invoke(Role $role = null): Collection
    {
        $modules = Permission::query()
            ->orderBy('module')
            ->get(['id', 'name', 'module'])
            ->groupBy('module');

        $permissionsModules = collect();

        foreach ($modules as $key => $permissions) {
            try {
                if ($role) {

                    $permissions->each(function (Permission $permission) use ($role) {

                        $permission->active = $role->hasPermissionTo($permission->name);
                    });
                }

            } catch (\Throwable $th) {
                continue;
            }

            $permissionsModules->add([
                'name' => __("permissions.$key"),
                'permissions' => $permissions
            ]);
        }

        return $permissionsModules;
    }
}
