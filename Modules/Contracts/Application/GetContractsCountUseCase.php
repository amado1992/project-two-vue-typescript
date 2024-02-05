<?php

namespace Modules\Contracts\Application;

use Modules\Contracts\Entities\Contract;

/**
 * @author Abel David.
 */
class GetContractsCountUseCase
{
    /**
     * @return int
     */
    public function __invoke(): int
    {
        return Contract::query()
            ->count();
    }
}
