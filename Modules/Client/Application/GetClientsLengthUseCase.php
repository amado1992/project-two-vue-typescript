<?php

namespace Modules\Client\Application;

use Modules\Client\Entities\Client;

/**
 * @author Abel David.
 */
class GetClientsLengthUseCase
{
    /**
     * @param string|null $filter
     * @return int
     */
    public function __invoke(?string $filter = null): int
    {
        $builder = Client::query();

        if ($filter) {

            $builder->where('name', 'LIKE', '%'.$filter.'%');
        }

        return $builder->count();
    }
}
