<?php

namespace Modules\Bonos\Application;

use Modules\Bonos\Entities\Bono;
use Modules\Bonos\Events\BonoCreated;
use Modules\Bonos\Events\CreatingBono;
use Modules\Client\Entities\Client;

/**
 * @author Abel David.
 */
class CreateBonoUseCase
{
    /**
     * @param array $data
     * @return Bono
     */
    public function __invoke(array $data): Bono
    {
        $client = Client::find($data['client_id']);

        $client->fill([
            'credit' => $client->credit + $data['credit']
        ])->save();

        CreatingBono::dispatch();

        $data['created_by'] = auth()->user()->getAuthIdentifier();

        $bono = Bono::create($data);
        
        BonoCreated::dispatch($bono);

        return $bono;
    }
}
