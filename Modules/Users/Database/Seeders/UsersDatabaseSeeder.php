<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Modules\Users\Entities\User;

class UsersDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $name = config('custom.default_admin_name');
        $email = config('custom.default_admin_email');
        $password = Hash::make(config('custom.default_admin_password'));

        $defaultUser = User::query()
            ->where('email', $email)
            ->first();

        if (! $defaultUser) {

            $defaultUser = User::create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'email_verified_at' => now()
            ]);

            $defaultUser->syncRoles('admin');
        }

        $env = config('app.env');

        if ($env != 'production') {

            User::factory(100)->create();
        }
    }
}
