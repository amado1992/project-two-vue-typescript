<?php

namespace Modules\Products\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\ProductCategories\Entities\ProductCategory;
use Modules\Products\Entities\Product;
use Modules\Users\Entities\User;

class ProductsDatabaseSeeder extends Seeder
{
    /**
     * @var array|array[]
     */
    private array $products = [
        [
            'name' => 'Martillo',
            'cost_price' => 100,
            'daily_price' => 20,
            'weekly_price' => 30,
            'biweekly_price' => 40,
            'monthly_price' => 50,
            'replacement_price' => 120,
            'tax' => 10
        ],
        [
            'name' => 'Andamio',
            'cost_price' => 150,
            'daily_price' => 30,
            'weekly_price' => 40,
            'biweekly_price' => 50,
            'monthly_price' => 60,
            'replacement_price' => 170,
            'tax' => 10
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $category = ProductCategory::query()
            ->whereNotNull('product_category_id')
            ->firstOrFail();

        $user = User::first();

        foreach ($this->products as $product) {

            $product['product_category_id'] = $category->id;
            $product['created_by'] = $user->id;

            Product::create($product);
        }
    }
}
