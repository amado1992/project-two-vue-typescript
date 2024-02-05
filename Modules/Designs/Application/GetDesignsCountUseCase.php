<?php

namespace Modules\Designs\Application;

use Modules\Designs\Entities\Design;

/**
 * @author Abel David.
 */
class GetDesignsCountUseCase
{
    /**
     * @return int
     */
    public function __invoke(): int
    {
        return Design::query()
            ->count();
    }
}
