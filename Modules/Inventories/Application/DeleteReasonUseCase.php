<?php

namespace Modules\Inventories\Application;

use Modules\Inventories\Entities\Reason;
use Modules\Inventories\Events\DeletingReason;
use Modules\Inventories\Events\ReasonDeleted;

/**
 * @author Abel David.
 */
class DeleteReasonUseCase
{
    /**
     * @param Reason $reason
     * @return bool
     */
    public function __invoke(Reason $reason): bool
    {
        DeletingReason::dispatch($reason);

        $deleted = $reason->delete() == true;

        if ($deleted) {

            ReasonDeleted::dispatch($reason);
        }

        return $deleted;
    }
}
