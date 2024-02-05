<?php

namespace Modules\Inventories\Application;

use Modules\Inventories\Entities\Movement;

/**
 * @author Abel David.
 */
class DeleteMovementUseCase
{
    /**
     * @param Movement $movement
     * @return bool
     */
    public function __invoke(Movement $movement): bool
    {
        $movementType = app($movement->type);

        return $movementType->rollback($movement);
    }
}
