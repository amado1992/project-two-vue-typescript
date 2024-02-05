<?php

namespace Modules\Payments\Application;

use Modules\Payments\Entities\Payment;

/**
 * @author Abel David.
 */
class GetPaymentsCountUseCase
{
    /**
     * @return int
     */
    public function __invoke(): int
    {
        return Payment::query()->count();
    }
}
