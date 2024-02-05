<?php

namespace Modules\Traces\Events;

use Illuminate\Database\Eloquent\Model;

/**
 * @author Abel David.
 */
interface UpdatedModelEvent extends ModelEvent
{
    /**
     * @return Model
     */
    public function getOldModel(): Model;
}
