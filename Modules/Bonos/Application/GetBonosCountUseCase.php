<?php

namespace Modules\Bonos\Application;

use Modules\Bonos\Entities\Bono;

/**
 * @author Abel David.
 */
class GetBonosCountUseCase
{
    /**
     * @return int
     */
    public function __invoke(): int
    {
        return Bono::query()->count();
    }
}
