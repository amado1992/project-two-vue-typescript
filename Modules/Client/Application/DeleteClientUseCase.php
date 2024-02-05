<?php

namespace Modules\Client\Application;

use Illuminate\Support\Facades\Auth;
use Modules\Client\Entities\Client;
use Modules\Client\Events\ClientDeleted;
use Modules\Client\Events\DeletingClient;

/**
 * @author cheynerpb.
 */
class DeleteClientUseCase
{
    public function __invoke(Client $client): bool
    {
        DeletingClient::dispatch($client);

        $client->delete();

        ClientDeleted::dispatch($client);

        return true;
    }
}
