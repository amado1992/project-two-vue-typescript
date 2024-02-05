<?php

namespace Modules\Inventories\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Inventories\Listeners\CreateInventoryListener;
use Modules\Products\Events\ProductCreated;

/**
 * @author Abel David.
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        ProductCreated::class => [
            CreateInventoryListener::class
        ]
    ];
}
