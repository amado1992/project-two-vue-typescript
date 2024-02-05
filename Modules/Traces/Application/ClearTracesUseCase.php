<?php

namespace Modules\Traces\Application;

use Modules\Traces\Entities\Trace;
use Modules\Traces\Events\TracesCleared;

/**
 * @author Abel David.
 */
class ClearTracesUseCase
{
    /**
     * @param string $model
     * @return void
     */
    public function __invoke(string $model): void
    {
        Trace::query()
            ->where('model', $model)
            ->delete();

        TracesCleared::dispatch($model);
    }
}
