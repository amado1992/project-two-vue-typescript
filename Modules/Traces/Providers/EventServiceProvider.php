<?php

namespace Modules\Traces\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Bonos\Events\BonoCreated;
use Modules\Bonos\Events\BonoDeleted;
use Modules\Bonos\Events\BonoUpdated;
use Modules\Brands\Events\BrandCreated;
use Modules\Brands\Events\BrandDeleted;
use Modules\Brands\Events\BrandUpdated;
use Modules\Client\Events\ClientCreated;
use Modules\Client\Events\ClientDeleted;
use Modules\Client\Events\ClientUpdated;
use Modules\Contracts\Events\ContractCancelled;
use Modules\Contracts\Events\ContractCreated;
use Modules\Contracts\Events\ContractDeleted;
use Modules\Contracts\Events\ContractFinished;
use Modules\Contracts\Events\ContractStarted;
use Modules\Contracts\Events\ContractUpdated;
use Modules\Designs\Events\DesignApproved;
use Modules\Designs\Events\DesignCreated;
use Modules\Designs\Events\DesignDeleted;
use Modules\Designs\Events\DesignUpdated;
use Modules\Inventories\Events\MovementCreated;
use Modules\Inventories\Events\ReasonCreated;
use Modules\Inventories\Events\ReasonDeleted;
use Modules\Inventories\Events\ReasonUpdated;
use Modules\ProductCategories\Events\ProductCategoryCreated;
use Modules\ProductCategories\Events\ProductCategoryDeleted;
use Modules\ProductCategories\Events\ProductCategoryUpdated;
use Modules\Products\Events\ProductCreated;
use Modules\Products\Events\ProductDeleted;
use Modules\Products\Events\ProductUpdated;
use Modules\Projects\Events\ProjectCreated;
use Modules\Projects\Events\ProjectDeleted;
use Modules\Projects\Events\ProjectUpdated;
use Modules\Providers\Events\ProviderCreated;
use Modules\Providers\Events\ProviderDeleted;
use Modules\Providers\Events\ProviderUpdated;
use Modules\Quotes\Events\QuoteApproved;
use Modules\Quotes\Events\QuoteCreated;
use Modules\Quotes\Events\QuoteDeleted;
use Modules\Quotes\Events\QuoteUpdated;
use Modules\ReRents\Events\ReRentCancelled;
use Modules\ReRents\Events\ReRentCreated;
use Modules\ReRents\Events\ReRentDeleted;
use Modules\ReRents\Events\ReRentFinished;
use Modules\ReRents\Events\ReRentUpdated;
use Modules\Roles\Events\RoleCreated;
use Modules\Roles\Events\RoleDeleted;
use Modules\Roles\Events\RoleUpdated;
use Modules\Traces\Events\TracesCleared;
use Modules\Traces\Listeners\ApprovedListener;
use Modules\Traces\Listeners\CancelledListener;
use Modules\Traces\Listeners\CreatedListener;
use Modules\Traces\Listeners\DeletedListener;
use Modules\Traces\Listeners\FinishedListener;
use Modules\Traces\Listeners\StartedListener;
use Modules\Traces\Listeners\TracesClearedListener;
use Modules\Traces\Listeners\UpdatedListener;
use Modules\Users\Events\UserCreated;
use Modules\Users\Events\UserDeleted;
use Modules\Users\Events\UserUpdated;

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
        BrandCreated::class => [
            CreatedListener::class
        ],
        ClientCreated::class => [
            CreatedListener::class
        ],
        ContractCreated::class => [
            CreatedListener::class
        ],
        ProductCategoryCreated::class => [
            CreatedListener::class
        ],
        ProductCreated::class => [
            CreatedListener::class
        ],
        ProjectCreated::class => [
            CreatedListener::class
        ],
        ProviderCreated::class => [
            CreatedListener::class
        ],
        QuoteCreated::class => [
            CreatedListener::class
        ],
        ReRentCreated::class => [
            CreatedListener::class
        ],
        RoleCreated::class => [
            CreatedListener::class
        ],
        UserCreated::class => [
            CreatedListener::class
        ],
        ReasonCreated::class => [
            CreatedListener::class
        ],
        MovementCreated::class => [
            CreatedListener::class
        ],
        DesignCreated::class => [
            CreatedListener::class
        ],
        BonoCreated::class => [
            CreatedListener::class
        ],
        BrandDeleted::class => [
            DeletedListener::class
        ],
        ClientDeleted::class => [
            DeletedListener::class
        ],
        ContractDeleted::class => [
            DeletedListener::class
        ],
        ProductCategoryDeleted::class => [
            DeletedListener::class
        ],
        ProductDeleted::class => [
            DeletedListener::class
        ],
        ProjectDeleted::class => [
            DeletedListener::class
        ],
        ProviderDeleted::class => [
            DeletedListener::class
        ],
        QuoteDeleted::class => [
            DeletedListener::class
        ],
        ReRentDeleted::class => [
            DeletedListener::class
        ],
        RoleDeleted::class => [
            DeletedListener::class
        ],
        UserDeleted::class => [
            DeletedListener::class
        ],
        ReasonDeleted::class => [
            DeletedListener::class
        ],
        DesignDeleted::class => [
            DeletedListener::class
        ],
        BonoDeleted::class => [
            DeletedListener::class
        ],
        BrandUpdated::class => [
            UpdatedListener::class
        ],
        ClientUpdated::class => [
            UpdatedListener::class
        ],
        ContractUpdated::class => [
            UpdatedListener::class
        ],
        ProductCategoryUpdated::class => [
            UpdatedListener::class
        ],
        ProductUpdated::class => [
            UpdatedListener::class
        ],
        ProjectUpdated::class => [
            UpdatedListener::class
        ],
        ProviderUpdated::class => [
            UpdatedListener::class
        ],
        QuoteUpdated::class => [
            UpdatedListener::class
        ],
        ReRentUpdated::class => [
            UpdatedListener::class
        ],
        RoleUpdated::class => [
            UpdatedListener::class
        ],
        UserUpdated::class => [
            UpdatedListener::class
        ],
        ReasonUpdated::class => [
            UpdatedListener::class
        ],
        DesignUpdated::class => [
            UpdatedListener::class
        ],
        BonoUpdated::class => [
            UpdatedListener::class
        ],
        ContractStarted::class => [
            StartedListener::class
        ],
        ContractFinished::class => [
            FinishedListener::class
        ],
        ContractCancelled::class => [
            CancelledListener::class
        ],
        ReRentFinished::class => [
            FinishedListener::class
        ],
        ReRentCancelled::class => [
            CancelledListener::class
        ],
        TracesCleared::class => [
            TracesClearedListener::class
        ],
        QuoteApproved::class => [
            ApprovedListener::class
        ],
        DesignApproved::class => [
            ApprovedListener::class
        ]
    ];
}
