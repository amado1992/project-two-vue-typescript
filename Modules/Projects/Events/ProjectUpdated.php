<?php

namespace Modules\Projects\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Projects\Entities\Project;
use Modules\Traces\Events\ModelEvent;
use Modules\Traces\Events\UpdatedModelEvent;

/**
 * @author Abel David.
 */
class ProjectUpdated implements UpdatedModelEvent
{
    use SerializesModels, Dispatchable;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public readonly Project $project,
        public readonly Project $oldProject
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
        return $this->project;
    }

    public function getOldModel(): Model
    {
        return $this->oldProject;
    }
}
