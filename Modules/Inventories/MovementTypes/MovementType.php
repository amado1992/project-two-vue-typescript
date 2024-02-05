<?php

namespace Modules\Inventories\MovementTypes;

use Modules\Inventories\Entities\Movement;

/**
 * @author Abel David.
 */
interface MovementType
{
    /**
     * @return string
     */
    static function name(): string;

    /**
     * @param array $data
     * @return Movement
     */
    function run(array $data): Movement;

    /**
     * @param Movement $movement
     * @return bool
     */
    function rollback(Movement $movement): bool;
}
