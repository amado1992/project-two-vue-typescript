<?php

namespace Modules\Traces\Listeners;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Traces\Entities\Trace;

/**
 * @author Abel David.
 */
class UpdatedListener extends UpdateTraceListener
{
    /**
     * Get trace action.
     *
     * @return string
     */
    protected function getAction(): string
    {
        return Trace::UPDATED_ACTION;
    }
}
