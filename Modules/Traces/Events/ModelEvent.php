<?php

namespace Modules\Traces\Events;

use Illuminate\Database\Eloquent\Model;

/**
 * @author Abel David.
 */
interface ModelEvent
{
    /**
     * @return Model
     */
    function getModel(): Model;
}
