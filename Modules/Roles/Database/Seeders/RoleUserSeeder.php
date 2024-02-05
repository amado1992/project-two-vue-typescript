<?php

namespace Modules\Roles\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Common\Permissions\PermissionManager;
use Modules\Roles\Entities\Permission;
use Modules\Roles\Entities\Role;
use Modules\Users\Entities\User;

class RoleUserSeeder extends Seeder
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

        $role = Role::firstOrCreate([
            'name' => 'admin'
        ])->syncPermissions($permissions);

        $this->command->comment('Admin role seeded.');

        $email = config('custom.default_admin_email');

        $defaultUser = User::query()
            ->where('email', $email)
            ->orWhere('email','admin@gmail.com')
            ->first();

        $defaultUser?->syncRoles($role);
    }
}
