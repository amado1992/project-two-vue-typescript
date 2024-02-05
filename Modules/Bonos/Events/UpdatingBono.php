<?php

namespace Modules\Bonos\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Bonos\Entities\Bono;
use Modules\Traces\Events\ModelEvent;

/**
 * @author Abel David.
 */
class UpdatingBono implements ModelEvent
{
    use SerializesModels, Dispatchable;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public readonly Bono $bono
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
        return $this->bono;
    }
}
