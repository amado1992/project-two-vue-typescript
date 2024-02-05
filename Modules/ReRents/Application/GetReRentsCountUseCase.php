<?php

namespace Modules\ReRents\Application;

use Modules\ReRents\Entities\ReRent;

/**
 * @author Abel David.
 */
class GetReRentsCountUseCase
{
    /**
     * @return int
     */
    public function __invoke(): int
    {
        return ReRent::query()
            ->count();
    }
}
