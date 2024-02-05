<?php

namespace Modules\ReRents\Application;

use Modules\ReRents\Entities\ReRent;
use Modules\ReRents\Events\DeletingReRent;
use Modules\ReRents\Events\ReRentDeleted;

/**
 * @author Abel David.
 */
class DeleteReRentUseCase
{
    /**
     * @param ReRent $reRent
     * @return bool
     */
    public function __invoke(ReRent $reRent): bool
    {
        DeletingReRent::dispatch($reRent);

        $deleted = $reRent->delete() == true;

        ReRentDeleted::dispatch($reRent);

        return $deleted;
    }
}
