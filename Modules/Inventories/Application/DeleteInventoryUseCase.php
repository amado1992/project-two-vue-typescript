<?php

namespace Modules\Inventories\Application;

use Modules\Inventories\Entities\Inventory;

/**
 * @author Abel David.
 */
class DeleteInventoryUseCase
{
    /**
     * @param Inventory $inventory
     * @return bool
     */
    public function __invoke(Inventory $inventory): bool
    {
        return $inventory->delete() == true;
    }
}
