<?php

namespace Modules\Traces\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Traces\Entities\Trace;

/**
 * @author Abel David.
 */
class ApprovedListener extends TraceListener
{
    /**
     * Get trace action.
     *
     * @return string
     */
    protected function getAction(): string
    {
        return Trace::APPROVED_ACTION;
    }
}
