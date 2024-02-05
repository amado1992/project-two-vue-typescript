<?php

namespace Modules\Inventories\Application;

use Modules\Inventories\Entities\Reason;
use Modules\Inventories\Events\CreatingReason;
use Modules\Inventories\Events\ReasonCreated;

/**
 * @author Abel David.
 */
class CreateReasonUseCase
{
    /**
     * @param array $data
     * @return Reason
     */
    public function __invoke(array $data): Reason
    {
        $data['created_by'] = auth()->user()->getAuthIdentifier();

        CreatingReason::dispatch();

        $reason = Reason::create($data);

        ReasonCreated::dispatch($reason);

        return $reason;
    }
}
