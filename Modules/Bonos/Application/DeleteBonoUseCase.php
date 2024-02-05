<?php

namespace Modules\Bonos\Application;

use Modules\Bonos\Entities\Bono;
use Modules\Bonos\Events\BonoDeleted;
use Modules\Bonos\Events\DeletingBono;

/**
 * @author Abel David.
 */
class DeleteBonoUseCase
{
    /**
     * @param Bono $bono
     * @return bool
     */
    public function __invoke(Bono $bono): bool
    {
        DeletingBono::dispatch($bono);

        $client = $bono->client;

        $client->fill([
            'credit' => max($client->credit - $bono->credit, 0)
        ])->save();

        $deleted = $bono->delete() === true;

        if ($deleted) {

            BonoDeleted::dispatch($bono);
        }

        return $deleted;
    }
}
