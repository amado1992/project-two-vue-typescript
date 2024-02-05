<?php

namespace Modules\Bonos\Application;

use Modules\Bonos\Entities\Bono;
use Modules\Bonos\Events\BonoUpdated;
use Modules\Bonos\Events\UpdatingBono;

/**
 * @author Abel David.
 */
class UpdateBonoUseCase
{
    /**
     * @param array $data
     * @param Bono $bono
     * @return bool
     */
    public function __invoke(array $data, Bono $bono): bool
    {
        if ($data['credit'] != $bono->credit || $data['date'] != $bono->date->format('Y-m-d')) {

            $oldBono = clone $bono;

            UpdatingBono::dispatch($bono);

            $client = $bono->client;

            $credit = $client->credit;

            $client->fill([
                'credit' => $credit + $data['credit'] - $bono->credit //$credit - $bono->credit + $data['credit']
            ])->save();

            $data['updated_by'] = auth()->user()->getAuthIdentifier();

            $bono->fill($data)->save();

            BonoUpdated::dispatch($bono, $oldBono);

            return true;
        }

        return false;
    }
}
