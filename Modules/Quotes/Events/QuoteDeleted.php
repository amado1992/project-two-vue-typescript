<?php

namespace Modules\Quotes\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Quotes\Entities\Quote;
use Modules\Traces\Events\ModelEvent;

/**
 * @author Abel David.
 */
class QuoteDeleted implements ModelEvent
{
    use Dispatchable;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public readonly Quote $quote
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
        return $this->quote;
    }
}
