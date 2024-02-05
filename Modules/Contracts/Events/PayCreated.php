<?php

namespace Modules\Contracts\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Contracts\Entities\Pay;
use Modules\Traces\Events\ModelEvent;

/**
 * @author Abel David.
 */
class PayCreated implements ModelEvent
{
    use SerializesModels, Dispatchable;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public readonly Pay $pay
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
        return $this->pay;
    }
}
