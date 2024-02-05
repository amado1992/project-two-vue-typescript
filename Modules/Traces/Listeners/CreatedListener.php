<?php

namespace Modules\Traces\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Traces\Application\CreateTraceUseCase;
use Modules\Traces\Entities\Trace;
use Modules\Traces\Events\ModelEvent;

/**
 * @author Abel David.
 */
class CreatedListener extends TraceListener
{
    /**
     * Get trace action.
     *
     * @return string
     */
    protected function getAction(): string
    {
        return Trace::CREATED_ACTION;
    }
}
