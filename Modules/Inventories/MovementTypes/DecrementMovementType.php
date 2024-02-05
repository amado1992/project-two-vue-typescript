<?php

namespace Modules\Inventories\MovementTypes;

use Illuminate\Support\Facades\DB;
use Modules\Inventories\Entities\Inventory;
use Modules\Inventories\Entities\Movement;
use Modules\Products\Entities\Product;

/**
 * @author Abel David.
 */
class DecrementMovementType extends MutableInventoryMovementType
{
    /**
     * @return string
     */
    static function name(): string
    {
        return 'decrement';
    }

    /**
     * @param Inventory $inventory
     * @param int $quantity
     * @return void
     */
    protected function mutateInventory(Inventory $inventory, int $quantity): void
    {
        $inventory->fill([
            'quantity' => max($inventory->$quantity - $quantity, 0),
            'stock' => max($inventory->stock - $quantity, 0)
        ])->save();
    }

    /**
     * @param Movement $movement
     * @return bool
     */
    function rollback(Movement $movement): bool
    {
        return DB::transaction(function () use ($movement) {

            $movement->products->each(function (Product $product) {

                $quantity = $product->pivot->quantity;

                $product->inventory->fill([
                    'quantity' => $product->inventory->quantity + $quantity,
                    'stock' => $product->inventory->stock + $quantity,
                ])->save();
            });

            return $movement->delete() == true;
        });
    }
}
