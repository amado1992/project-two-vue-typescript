<?php

namespace Modules\Inventories\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Inventories\Application\CreateInventoryUseCase;
use Modules\Products\Events\ProductCreated;

class CreateInventoryListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        private readonly CreateInventoryUseCase $createInventoryUseCase,
    )
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ProductCreated $event
     * @return void
     */
    public function handle(ProductCreated $event): void
    {
        ($this->createInventoryUseCase)([
            'product_id' => $event->product->id
        ]);
    }
}
