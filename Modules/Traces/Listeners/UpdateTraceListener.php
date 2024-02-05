<?php

namespace Modules\Traces\Listeners;

use Illuminate\Database\Eloquent\Model;
use Modules\Traces\Application\CreateTraceUseCase;
use Modules\Traces\Events\ModelEvent;
use Modules\Traces\Events\UpdatedModelEvent;

/**
 * @author Abel David.
 */
abstract class UpdateTraceListener extends BaseListener
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
     * @param UpdatedModelEvent $event
     * @return void
     */
    public function handle(UpdatedModelEvent $event): void
    {
        $oldModel = $event->getOldModel();
        $model = $event->getModel();

        ($this->useCase)(array_merge([
            'model' => $model::class,
            'model_id' => $model->getKey(),
            'module' => $this->getModule($model::class),
            'action' => $this->getAction(),
            'user_id' => auth()->user()->getAuthIdentifier()
        ], $this->extraFields($model, $oldModel)));
    }

    /**
     * Get extra fields to save into the trace.
     *
     * @param Model $model
     * @param Model $oldModel
     * @return array
     */
    protected function extraFields(Model $model, Model $oldModel): array
    {
        $old_fields = $oldModel->attributesToArray();

        if (isset($old_fields['crud_permissions'])) {
            unset($old_fields['crud_permissions']);
        }

        return [
            'old_fields' => $old_fields,
            'fields' => $model->getChanges()
        ];
    }

    /**
     * Get trace action.
     *
     * @return string
     */
    protected abstract function getAction(): string;
}
