<?php

namespace Modules\Inventories\Application;

use Illuminate\Support\Collection;
use Modules\Inventories\Entities\Reason;

/**
 * @author Abel David.
 */
class ReadReasonsUseCase
{
    /**
     * @param string $filter
     * @return Collection
     */
    public function __invoke(string $filter = 'all'): Collection
    {
        $builder = Reason::query();

        switch ($filter) {

            case 'actives':
                $builder->where('active', true);
                break;
            case 'inactives':
                $builder->where('active', false);
                break;
        }

        return $builder->get();
    }
}
