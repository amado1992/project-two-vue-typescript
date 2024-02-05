<?php

namespace Modules\Client\Application;

use Modules\Client\Entities\Client;
use Modules\Client\Events\ClientCreated;
use Modules\Client\Events\CreatingClient;
use Modules\Client\Http\Requests\StoreClientRequest;

/**
 * Create a user.
 *
 * @author Abel David.
 */
class CreateClientUseCase
{
    /**
     * @param StoreClientRequest $request
     * @return Client
     */
    public function __invoke(StoreClientRequest $request): Client
    {
        CreatingClient::dispatch();

        $contacts = cleanContactArray($request->input('contacts'));

        $client = Client::create([
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
            'credit' => 0,
            'created_by' => auth()->user()->getAuthIdentifier(),
            'ficha' => $request->input('ficha'),
            'redi' => $request->input('redi'),
        ]);

        ClientCreated::dispatch($client);

        return $client;
    }
}
