<?php

namespace Modules\Traces\Listeners;

use Modules\Traces\Entities\Trace;

/**
 * @author Abel David.
 */
abstract class BaseListener
{
    /**
     * Get the module name of the given model.
     *
     * @param string $model
     * @return string
     */
    protected function getModule(string $model): string
    {
        $map = [];

        $values = array_values(Trace::MODELS_MAP);
        $keys = array_keys(Trace::MODELS_MAP);

        for ($i = 0; $i < count($values); $i++) {

            $map[$values[$i]] = $keys[$i];
        }

        return $map[$model];
    }
}
