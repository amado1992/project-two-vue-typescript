<?php

namespace Modules\Traces\Application;

use Abdavid92\LaravelQuasarPaginator\DataTablePaginator;
use Modules\Traces\Entities\Trace;

/**
 * @author Abel David.
 */
class PaginateTracesUseCase
{
    /**
     * @param string $model
     * @return DataTablePaginator
     */
    public function __invoke(string $model): DataTablePaginator
    {
        return new DataTablePaginator(
            Trace::query()->where('model', $model),
            'created_at',
            true
        );
    }
}
