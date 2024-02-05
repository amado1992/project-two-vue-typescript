<?php

namespace Modules\Inventories\Application;

use Modules\Inventories\Entities\Movement;
use Modules\Inventories\Events\CreatingMovement;
use Modules\Inventories\Events\MovementCreated;

/**
 * @author Abel David.
 */
class CreateMovementUseCase
{
    /**
     * @param array $data
     * @return Movement
     */
    public function __invoke(array $data): Movement
    {
        $data['created_by'] = auth()->user()->getAuthIdentifier();

        CreatingMovement::dispatch();

        $movement = app($data['type'])->run($data);

        MovementCreated::dispatch($movement);

        return $movement;
    }
}
