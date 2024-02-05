<?php

namespace Modules\Users\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Traces\Events\ModelEvent;
use Modules\Traces\Events\UpdatedModelEvent;
use Modules\Users\Entities\User;

/**
 * @author Abel David.
 */
class UserUpdated implements UpdatedModelEvent
{
    use SerializesModels, Dispatchable;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public readonly User $user,
        public readonly User $oldUser
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
        return $this->user;
    }

    public function getOldModel(): Model
    {
        return $this->oldUser;
    }
}
