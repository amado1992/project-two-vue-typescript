<?php

namespace Modules\Designs\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Modules\Designs\Entities\Design;
use Modules\Traces\Events\ModelEvent;

/**
 * @author Abel David
 */
class DesignDeleted implements ModelEvent
{
    use Dispatchable;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public readonly Design $design
    )
    {
        //
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }

    function getModel(): Model
    {
        return $this->design;
    }
}
