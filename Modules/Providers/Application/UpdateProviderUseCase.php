<?php

namespace Modules\Providers\Application;


use Modules\Providers\Entities\Provider;
use Modules\Providers\Events\ProviderUpdated;
use Modules\Providers\Events\UpdatingProvider;
use Modules\Providers\Http\Requests\UpdateProviderRequest;

/**
 * Update a user.
 *
 * @author cheynerpb.
 */
class UpdateProviderUseCase
{
    /**
     * @param UpdateProviderRequest $request
     * @param Provider $provider
     * @return bool
     */
    public function __invoke(UpdateProviderRequest $request, Provider $provider): bool
    {
        $oldProvider = clone $provider;

        $provider->fill([
            'name' => $request->input('name'),
            'active' => $request->boolean('active'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'ruc' => $request->input('ruc'),
            'dv' => $request->input('dv'),
            'contacts' => $request->input('contacts')
        ]);

        if ($provider->isDirty()) {

            $provider->fill([
                'updated_by' => auth()->user()->getAuthIdentifier()
            ]);

            UpdatingProvider::dispatch($provider);
        }

        $provider->save();

        $wasChanged = $provider->wasChanged();

        if ($wasChanged) {

            ProviderUpdated::dispatch($provider, $oldProvider);
        }

        return $wasChanged;
    }
}
