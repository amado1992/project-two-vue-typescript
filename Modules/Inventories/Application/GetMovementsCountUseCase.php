<?php

namespace Modules\Inventories\Application;

use Modules\Inventories\Entities\Movement;

/**
 * @author Abel David.
 */
class GetMovementsCountUseCase
{
    /**
     * @return int
     */
    public function __invoke(): int
    {
        return Movement::query()->count();
    }
}
