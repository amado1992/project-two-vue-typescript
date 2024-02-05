<?php

namespace Modules\Client\Application;


use Modules\Client\Entities\Client;
use Modules\Client\Events\ClientUpdated;
use Modules\Client\Events\UpdatingClient;
use Modules\Client\Http\Requests\UpdateClientRequest;

/**
 * Update a user.
 *
 * @author cheynerpb.
 */
class UpdateClientUseCase
{
    /**
     * @param UpdateClientRequest $request
     * @param Client $client
     * @return bool
     */
    public function __invoke(UpdateClientRequest $request, Client $client): bool
    {
        $oldClient = clone $client;

        $contacts = cleanContactArray($request->input('contacts'));

        $client->forceFill([
            'name' => $request->input('name'),
            'active' => (bool)$request->input('active'),
            'ruc' => $request->input('ruc'),
            'dv' => $request->input('dv'),
            'no_taxes' => (bool)$request->input('no_taxes'),
            'phone' => $request->input('phone'),
            'mobile' => $request->input('mobile'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'legal_representative' => $request->input('legal_representative'),
            'cedula' => $request->input('cedula'),
            'contacts' => json_encode($contacts),
            'ficha' => $request->input('ficha'),
            'redi' => $request->input('redi'),
        ]);

        if ($client->isDirty()) {

            $client->fill([
                'updated_by' => auth()->user()->getAuthIdentifier()
            ]);

            UpdatingClient::dispatch($client);
        }

        $client->save();

        $wasChanged = $client->wasChanged();

        if ($wasChanged) {

            ClientUpdated::dispatch($client, $oldClient);
        }

        return $wasChanged;
    }
}
