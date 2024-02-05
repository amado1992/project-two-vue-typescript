<?php

namespace Modules\Traces\Application;

use Modules\Traces\Entities\Trace;

/**
 * @author Abel David.
 */
class CreateTraceUseCase
{
    /**
     * @param array $data
     * @return Trace
     */
    public function __invoke(array $data): Trace
    {
        return Trace::create($data);
    }
}
