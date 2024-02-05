<?php

namespace Modules\Inventories\MovementTypes;

use Illuminate\Support\Facades\DB;
use Modules\Inventories\Entities\Inventory;
use Modules\Inventories\Entities\Movement;
use Modules\Products\Entities\Product;

/**
 * @author Abel David.
 */
class IncrementMovementType extends MutableInventoryMovementType
{

    /**
     * @return string
     */
    static function name(): string
    {
        return 'increment';
    }

    /**
     * @param Inventory $inventory
     * @param int $quantity
     * @return void
     */
    protected function mutateInventory(Inventory $inventory, int $quantity): void
    {
        $inventory->fill([
            'quantity' => $inventory->quantity + $quantity,
            'stock' => $inventory->stock + $quantity
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
                    'quantity' => max($product->inventory->quantity - $quantity, 0),
                    'stock' => max($product->inventory->stock - $quantity, 0)
                ])->save();
            });

            return $movement->delete() == true;
        });
    }
}
