<?php

namespace Modules\Inventories\Application;

use Modules\Inventories\Entities\Reason;
use Modules\Inventories\Events\ReasonUpdated;
use Modules\Inventories\Events\UpdatingReason;

/**
 * @author Abel David.
 */
class UpdateReasonUseCase
{
    /**
     * @param array $data
     * @param Reason $reason
     * @return bool
     */
    public function __invoke(array $data, Reason $reason): bool
    {
        $oldReason = clone $reason;

        $reason->fill($data);

        if ($reason->isDirty()) {

            $reason->fill([
                'updated_by' => auth()->user()->getAuthIdentifier()
            ]);

            UpdatingReason::dispatch($reason);
        }

        $reason->save();

        $wasChanged = $reason->wasChanged();

        if ($wasChanged) {

            ReasonUpdated::dispatch($reason, $oldReason);
        }

        return $wasChanged;
    }
}
