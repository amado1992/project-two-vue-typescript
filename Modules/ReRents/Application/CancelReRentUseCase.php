<?php

namespace Modules\ReRents\Application;

use Exception;
use Modules\ReRents\Entities\ReRent;
use Modules\ReRents\Events\ReRentCancelled;

/**
 * @author Abel David.
 */
class CancelReRentUseCase
{
    /**
     * @param ReRent $reRent
     * @param string|null $reason
     * @return void
     * @throws Exception
     */
    public function __invoke(ReRent $reRent, ?string $reason): void
    {
        if ($reRent->cancelled_at) {

            throw new Exception('The re rent is already cancelled.');
        }

        if ($reRent->finished_at) {

            throw new Exception('The re rent can\'t cancel because is already finished.');
        }

        $reRent->fill([
            'cancelled_at' => now(),
            'cancelled_by' => auth()->user()->getAuthIdentifier(),
            'cancelled_reason' => $reason
        ])->save();

        ReRentCancelled::dispatch($reRent);
    }
}
