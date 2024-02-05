<?php

namespace Modules\Common\Application;
use Illuminate\Support\Facades\Cache;
use Modules\Users\Entities\User;

/**
 * Summary of GetClientDataUseCase
 */
class ReadCacheUserPermission
{


    public function __invoke(?User $user, array $permissionsLoad = [])
    {
        $permissions = $user ? Cache::remember("permission-$user->name", 5, function () use ($permissionsLoad) {
            return $permissionsLoad;
        }) : $permissionsLoad;

        return $permissions;
    }

}
