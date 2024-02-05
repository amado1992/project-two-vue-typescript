<?php

namespace Modules\Roles\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Common\Permissions\PermissionManager;
use Modules\Roles\Entities\Permission;
use Modules\Roles\Entities\Role;
use Modules\Users\Entities\User;

class RolesDatabaseSeederWithoutPrune extends Seeder
{
    /**
     * @param PermissionManager $permissionManager
     */
    public function __construct(
        private readonly PermissionManager $permissionManager
    )
    {
        //
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {

        $permissions = $this->permissionManager->getAllPermissions();

        foreach ($permissions as $permission) {

            Permission::firstOrCreate([
                'name' => $permission,
                'module' => Str::ucfirst(Str::afterLast($permission, '_'))
            ]);
        }

        $this->command->comment('All permissions seeded.');

        
    }

    /**
     * @return void
     */
    private function pruneAllPermissions(): void
    {
        //Role::query()->delete();
        Permission::query()->delete();

        $this->command->comment('All roles and permissions deleted.');
    }
}
