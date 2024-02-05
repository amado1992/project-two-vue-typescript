<?php

namespace Modules\Traces\Listeners;

use Illuminate\Database\Eloquent\Model;
use Modules\Traces\Application\CreateTraceUseCase;
use Modules\Traces\Entities\Trace;
use Modules\Traces\Events\ModelEvent;

/**
 * @author Abel David.
 */
abstract class TraceListener extends BaseListener
{
    /**
     * Create the event listener.
     *
     * @param CreateTraceUseCase $useCase
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
     * @param ModelEvent $event
     * @return void
     */
    public function handle(ModelEvent $event): void
    {
        $model = $event->getModel();

        ($this->useCase)(array_merge([
            'model' => $model::class,
            'model_id' => $model->getKey(),
            'module' => $this->getModule($model::class),
            'action' => $this->getAction(),
            'user_id' => auth()->user()->getAuthIdentifier()
        ], $this->extraFields($model)));
    }

    /**
     * Get extra fields to save into the trace.
     *
     * @param Model $model
     * @return array
     */
    protected function extraFields(Model $model): array
    {
        return [];
    }

    /**
     * Get trace action.
     *
     * @return string
     */
    protected abstract function getAction(): string;
}
