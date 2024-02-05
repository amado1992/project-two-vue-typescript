<?php

namespace Modules\Inventories\MovementTypes;

use Modules\Inventories\Entities\Inventory;
use Modules\Inventories\Entities\Movement;
use Modules\Products\Entities\Product;

/**
 * @author Abel David.
 */
abstract class MutableInventoryMovementType implements MovementType
{

    function run(array $data): Movement
    {
        $movement = Movement::create($data);

        $products = [];

        foreach ($data['products'] as $product) {

            $productModel = Product::findOrFail($product['id']);

            $this->mutateInventory($productModel->inventory, $product['quantity']);

            $products[$product['id']] = [
                'price' => $productModel->cost_price,
                'quantity' => $product['quantity']
            ];
        }

        $movement->products()->sync($products);

        return $movement;
    }

    protected abstract function mutateInventory(Inventory $inventory, int $quantity): void;
}
