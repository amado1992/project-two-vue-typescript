<?php

namespace Modules\Inventories\Application;

use Modules\Inventories\Entities\Inventory;

/**
 * @author Abel David.
 */
class CreateInventoryUseCase
{
    /**
     * @param array $data
     * @return Inventory
     */
    public function __invoke(array $data): Inventory
    {
        return Inventory::create($data);
    }
}
