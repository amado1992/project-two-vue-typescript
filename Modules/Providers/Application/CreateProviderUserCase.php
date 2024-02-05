<?php

namespace Modules\Providers\Application;

use Modules\Providers\Entities\Provider;
use Modules\Providers\Events\CreatingProvider;
use Modules\Providers\Events\ProviderCreated;
use Modules\Providers\Http\Requests\StoreProviderRequest;

/**
 * Create a user.
 *
 * @author Abel David.
 */
class CreateProviderUserCase
{
    /**
     * @param StoreProviderRequest $request
     * @return Provider
     */
    public function __invoke(StoreProviderRequest $request): Provider
    {
        CreatingProvider::dispatch();
        $provider = Provider::create([
            'name' => $request->input('name'),
            'active' => (bool)$request->input('active'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'ruc' => $request->input('ruc'),
            'dv' => $request->input('dv'),
            'created_by' => auth()->user()->getAuthIdentifier(),
            'contacts' => json_encode( $request->input('contacts'))
        ]);

        ProviderCreated::dispatch($provider);

        return $provider;
    }
}
