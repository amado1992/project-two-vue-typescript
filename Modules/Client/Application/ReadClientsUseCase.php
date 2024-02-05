<?php

namespace Modules\Client\Application;

use Illuminate\Support\Collection;
use Modules\Client\Entities\Client;

/**
 * @author cheynerpb.
 */
class ReadClientsUseCase
{

    /**
     * @param string|null $filter
     * @param int|null $count
     * @return Collection
     */
    public function __invoke(?string $filter = null, ?int $count = null, ?array $fields = null): Collection
    {
        $builder =  Client::query()
            ->where('active',true)
            ->orderBy('name');

        if ($filter) {

            $builder->where('name', 'LIKE', '%'.$filter.'%');
        }

        if ($count) {

            $builder->limit($count);
        }

        if($fields){
            $builder->select($fields);
        }

        return $builder->get();
    }
}
