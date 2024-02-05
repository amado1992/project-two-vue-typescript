<?php

return [
    /*
     *  Automatic registration of routes will only happen if this setting is `true`
     */
    'enabled' => true,

    /*
     * Controllers in these directories that have routing attributes
     * will automatically be registered.
     *
     * Optionally, you can specify group configuration by using key/values
     */
    'directories' => [
        base_path('Modules/Users/Http/Controllers') => [
            'namespace' => 'Modules\Users\Http\Controllers',
            'middleware' => [
                'web',
                'auth',
                'verified'
            ]
        ],
        base_path('Modules/Roles/Http/Controllers') => [
            'namespace' => 'Modules\Roles\Http\Controllers',
            'middleware' => [
                'web',
                'auth',
                'verified'
            ]
        ],
        base_path('Modules/Client/Http/Controllers') => [
            'namespace' => 'Modules\Client\Http\Controllers',
            'middleware' => [
                'web',
                'auth',
                'verified'
            ]
        ],
        base_path('Modules/Providers/Http/Controllers') => [
            'namespace' => 'Modules\Providers\Http\Controllers',
            'middleware' => [
                'web',
                'auth',
                'verified'
            ]
        ],
        base_path('Modules/Brands/Http/Controllers') => [
            'namespace' => 'Modules\Brands\Http\Controllers',
            'middleware' => [
                'web',
                'auth',
                'verified'
            ]
        ],
        base_path('Modules/Products/Http/Controllers') => [
            'namespace' => 'Modules\Products\Http\Controllers',
            'middleware' => [
                'web',
                'auth',
                'verified'
            ]
        ],
        base_path('Modules/Companies/Http/Controllers') => [
            'namespace' => 'Modules\Companies\Http\Controllers',
            'middleware' => [
                'web',
                'auth',
                'verified'
            ]
        ],
        base_path('Modules/ProductCategories/Http/Controllers') => [
            'namespace' => 'Modules\ProductCategories\Http\Controllers',
            'middleware' => [
                'web',
                'auth',
                'verified'
            ]
        ],
        base_path('Modules/Quotes/Http/Controllers') => [
            'namespace' => 'Modules\Quotes\Http\Controllers',
            'middleware' => [
                'web',
                'auth',
                'verified'
            ]
        ],
        base_path('Modules/Settings/Http/Controllers') => [
            'namespace' => 'Modules\Settings\Http\Controllers',
            'middleware' => [
                'web',
                'auth',
                'verified'
            ]
        ],
        base_path('Modules/Contracts/Http/Controllers') => [
            'namespace' => 'Modules\Contracts\Http\Controllers',
            'middleware' => [
                'web',
                'auth',
                'verified'
            ]
        ],
        base_path('Modules/Travels/Http/Controllers') => [
            'namespace' => 'Modules\Travels\Http\Controllers',
            'middleware' => [
                'web',
                'auth',
                'verified'
            ]
        ],
        base_path('Modules/Inventories/Http/Controllers') => [
            'namespace' => 'Modules\Inventories\Http\Controllers',
            'middleware' => [
                'web',
                'auth',
                'verified'
            ]
        ],
        base_path('Modules/ReRents/Http/Controllers') => [
            'namespace' => 'Modules\ReRents\Http\Controllers',
            'middleware' => [
                'web',
                'auth',
                'verified'
            ]
        ],
        base_path('Modules/Projects/Http/Controllers') => [
            'namespace' => 'Modules\Projects\Http\Controllers',
            'middleware' => [
                'web',
                'auth',
                'verified'
            ]
        ],
        base_path('Modules/Reports/Http/Controllers') => [
            'namespace' => 'Modules\Reports\Http\Controllers',
            'middleware' => [
                'web',
                'auth',
                'verified'
            ]
        ],
        base_path('Modules/Traces/Http/Controllers') => [
            'namespace' => 'Modules\Traces\Http\Controllers',
            'middleware' => [
                'web',
                'auth',
                'verified'
            ]
        ],
        base_path('Modules/Designs/Http/Controllers') => [
            'namespace' => 'Modules\Designs\Http\Controllers',
            'middleware' => [
                'web',
                'auth',
                'verified'
            ]
        ],
        base_path('Modules/Bonos/Http/Controllers') => [
            'namespace' => 'Modules\Bonos\Http\Controllers',
            'middleware' => [
                'web',
                'auth',
                'verified'
            ]
        ],
        base_path('Modules/Payments/Http/Controllers') => [
            'namespace' => 'Modules\Payments\Http\Controllers',
            'middleware' => [
                'web',
                'auth',
                'verified'
            ]
        ],
        base_path('Modules/Common/Http/Controllers') => [
            'namespace' => 'Modules\Common\Http\Controllers',
            'middleware' => [
                'web',
                'auth',
                'verified'
            ]
        ]
    ],

    /**
     * This middleware will be applied to all routes.
     */
    'middleware' => [
        \Illuminate\Routing\Middleware\SubstituteBindings::class
    ]
];
