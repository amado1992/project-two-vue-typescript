<?php

namespace Modules\Roles\Application;

use Illuminate\Support\Collection;

/**
 * @author Abel David.
 */
abstract class RoleUseCase
{
    /**
     * @param Collection $modules
     * @return Collection
     */
    protected function getActivePermissions(Collection $modules): Collection
    {
        $permissions = collect();

        $modules->each(function ($module) use (&$permissions) {

            $actives = array_filter($module['permissions'], fn ($item) => $item['active']);
            $permissions = $permissions->merge($actives);
        });

        return $permissions;
    }
}
