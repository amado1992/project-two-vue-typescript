<?php

namespace Modules\Traces\Application;

use Modules\Bonos\Entities\Bono;
use Modules\Brands\Entities\Brand;
use Modules\Client\Entities\Client;
use Modules\Contracts\Entities\Contract;
use Modules\Inventories\Entities\Movement;
use Modules\Inventories\Entities\Reason;
use Modules\ProductCategories\Entities\ProductCategory;
use Modules\Products\Entities\Product;
use Modules\Projects\Entities\Project;
use Modules\Providers\Entities\Provider;
use Modules\Quotes\Entities\Quote;
use Modules\ReRents\Entities\ReRent;
use Modules\Roles\Entities\Role;
use Modules\Traces\Entities\Trace;
use Modules\Users\Entities\User;
use Modules\Designs\Entities\Design;

/**
 * @author Abel David.
 */
class GetModelUrlUseCase
{
    /**
     * @var array
     */
    private array $routeMap = [
        Brand::class => [
            'route' => 'brands.edit',
            'arg' => 'brand'
        ],
        Client::class => [
            'route' => 'clients.edit',
            'arg' => 'client'
        ],
        Contract::class => [
            'route' => 'contracts.show',
            'arg' => 'contract'
        ],
        ProductCategory::class => [
            'route' => 'productCategories.edit',
            'arg' => 'productCategory'
        ],
        Provider::class => [
            'route' => 'providers.edit',
            'arg' => 'provider'
        ],
        Product::class => [
            'route' => 'products.edit',
            'arg' => 'product'
        ],
        Project::class => [
            'route' => 'projects.edit',
            'arg' => 'project'
        ],
        Quote::class => [
            'route' => 'quotes.edit',
            'arg' => 'quote'
        ],
        ReRent::class => [
            'route' => 're-rents.show',
            'arg' => 're_rent'
        ],
        Role::class => [
            'route' => 'roles.edit',
            'arg' => 'role'
        ],
        User::class => [
            'route' => 'users.edit',
            'arg' => 'user'
        ],
        Movement::class => [
            'route' => 'movements.show',
            'arg' => 'movement'
        ],
        Reason::class => [
            'route' => 'reasons.edit',
            'arg' => 'reason'
        ],
        Bono::class => [
            'route' => 'bonos.edit',
            'arg' => 'bono'
        ],
        Design::class => [
            'route' => 'designs.edit',
            'arg' => 'design'
        ]
    ];

    /**
     * @param Trace $trace
     * @return string|null
     */
    public function __invoke(Trace $trace): ?string
    {
        if (isset($this->routeMap[$trace->model])) {

            $routeData = $this->routeMap[$trace->model];

            return route($routeData['route'], [
                $routeData['arg'] => $trace->model_id
            ]);
        }

        return null;
    }
}
