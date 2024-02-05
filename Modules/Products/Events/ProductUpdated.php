<?php

namespace Modules\Products\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Products\Entities\Product;
use Modules\Traces\Events\ModelEvent;
use Modules\Traces\Events\UpdatedModelEvent;

/**
 * @author Abel David.
 */
class ProductUpdated implements UpdatedModelEvent
{
    use SerializesModels, Dispatchable;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public readonly Product $product,
        public readonly Product $oldProduct
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
        return $this->product;
    }

    public function getOldModel(): Model
    {
        return $this->oldProduct;
    }
}
