<?php

namespace Modules\ReRents\Application;

use Exception;
use Modules\ReRents\Entities\ReRent;
use Modules\ReRents\Events\ReRentFinished;

/**
 * @author Abel David.
 */
class FinishReRentUseCase
{
    /**
     * @param ReRent $reRent
     * @return void
     * @throws Exception
     */
    public function __invoke(ReRent $reRent): void
    {
        if ($reRent->finished_at) {

            throw new Exception(__('The reRent is already finished.'));
        }

        $this->ensureCanBeFinish($reRent);

        $reRent->fill([
            'finished_at' => now(),
            'finished_by' => auth()->user()->getAuthIdentifier()
        ])->save();

        ReRentFinished::dispatch($reRent);
    }

    /**
     * @param ReRent $reRent
     * @return void
     * @throws Exception
     */
    private function ensureCanBeFinish(ReRent $reRent): void
    {
        $reRent->products->each(function ($product) {

            if ($product->pivot->quantity > $product->pivot->returned) {

                throw new Exception(__('The reRent cannot be finalized because it still has unreturned products.'));
            }
        });
    }
}
