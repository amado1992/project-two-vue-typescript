<?php

namespace Modules\Roles\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Common\Permissions\PermissionManager;
use Modules\Roles\Entities\Permission;
use Modules\Roles\Entities\Role;
use Modules\Users\Entities\User;

class RolesDatabaseSeeder extends Seeder
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
        $this->pruneAllPermissions();

        $permissions = $this->permissionManager->getAllPermissions();

        foreach ($permissions as $permission) {

            Permission::firstOrCreate([
                'name' => $permission,
                'module' => Str::ucfirst(Str::afterLast($permission, '_'))
            ]);
        }

        $this->command->comment('All permissions seeded.');

        //Default role. Can be deleted.
        $role = Role::firstOrCreate([
            'name' => 'admin'
        ])->syncPermissions($permissions);

        $this->command->comment('Admin role seeded.');

        $email = config('custom.default_admin_email');

        $defaultUser = User::query()
            ->where('email', $email)
            ->first();

        $defaultUser?->syncRoles($role);
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
