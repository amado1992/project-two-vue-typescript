<?php

namespace Modules\ProductCategories\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\ProductCategories\Entities\ProductCategory;
use Modules\Traces\Events\ModelEvent;
use Modules\Traces\Events\UpdatedModelEvent;

/**
 * @author Abel David.
 */
class ProductCategoryUpdated implements UpdatedModelEvent
{
    use SerializesModels, Dispatchable;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public readonly ProductCategory $productCategory,
        public readonly ProductCategory $oldProductCategory
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
        return $this->productCategory;
    }

    public function getOldModel(): Model
    {
        return $this->oldProductCategory;
    }
}
