<?php

namespace Modules\ProductCategories\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\ProductCategories\Entities\ProductCategory;

class ProductCategoriesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $father = ProductCategory::create([
            'name' => fake()->word,
            'active' => true,
        ]);

        ProductCategory::factory(50)->create([
            'product_category_id' => $father->id
        ]);
    }
}
