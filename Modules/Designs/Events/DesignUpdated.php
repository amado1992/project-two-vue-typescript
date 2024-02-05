<?php

namespace Modules\Designs\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Designs\Entities\Design;
use Modules\Traces\Events\ModelEvent;
use Modules\Traces\Events\UpdatedModelEvent;

/**
 * @author Abel David.
 */
class DesignUpdated implements UpdatedModelEvent
{
    use SerializesModels, Dispatchable;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public readonly Design $design,
        public readonly Design $oldDesign
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

    public function getOldModel(): Model
    {
        return $this->oldDesign;
    }
}
