<?php

namespace Modules\Inventories\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Inventories\Entities\Inventory;
use Modules\Products\Entities\Product;

class InventoriesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Product::all()->each(function (Product $product) {

            if (! $product->inventory) {

                Inventory::create([
                    'product_id' => $product->id
                ]);
            }
        });
    }
}
