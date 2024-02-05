<?php

namespace Modules\Traces\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Traces\Application\CreateTraceUseCase;
use Modules\Traces\Entities\Trace;
use Modules\Traces\Events\TracesCleared;

class TracesClearedListener extends BaseListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        private readonly CreateTraceUseCase $useCase
    )
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TracesCleared  $event
     * @return void
     */
    public function handle(TracesCleared $event): void
    {
        ($this->useCase)([
            'model' => Trace::class,
            'action' => Trace::DELETED_ACTION,
            'module' => $this->getModule($event->model),
            'user_id' => auth()->user()->getAuthIdentifier()
        ]);
    }
}
