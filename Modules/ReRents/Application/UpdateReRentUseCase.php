<?php

namespace Modules\ReRents\Application;

use Modules\Common\Application\WithContractibleProducts;
use Modules\ReRents\Entities\ReRent;
use Modules\ReRents\Events\ReRentUpdated;
use Modules\ReRents\Events\UpdatingReRent;

/**
 * @author Abel David.
 */
class UpdateReRentUseCase
{
    use WithContractibleProducts;

    /**
     * @param array $data
     * @param ReRent $reRent
     * @return bool
     */
    public function __invoke(array $data, ReRent $reRent): bool
    {
        $oldReRent = clone $reRent;

        $reRent->fill($data);
        $wasChanged = $this->syncContractibleProducts($reRent, $data['products'], $data['tax_exempt']);

        if ($reRent->isDirty() || $wasChanged) {

            $reRent->fill([
                'updated_by' => auth()->user()->getAuthIdentifier()
            ]);

            UpdatingReRent::dispatch($reRent);
        }

        $reRent->save();

        $wasChanged = $reRent->wasChanged() || $wasChanged;

        if ($wasChanged) {

            ReRentUpdated::dispatch($reRent, $oldReRent);
        }

        return $wasChanged;
    }
}
