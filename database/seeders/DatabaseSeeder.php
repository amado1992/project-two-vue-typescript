<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Client\Database\Seeders\ClientDatabaseSeeder;
use Modules\Inventories\Database\Seeders\InventoriesDatabaseSeeder;
use Modules\ProductCategories\Database\Seeders\ProductCategoriesDatabaseSeeder;
use Modules\Products\Database\Seeders\ProductsDatabaseSeeder;
use Modules\Roles\Database\Seeders\RolesDatabaseSeeder;
use Modules\Users\Database\Seeders\UsersDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $env = config('app.env');

        $this->call(RolesDatabaseSeeder::class);
        $this->call(UsersDatabaseSeeder::class);

        if ($env != 'production') {

            $this->call(ProductCategoriesDatabaseSeeder::class);
            $this->call(ProductsDatabaseSeeder::class);
            $this->call(ClientDatabaseSeeder::class);
            $this->call(InventoriesDatabaseSeeder::class);
        }
    }
}
