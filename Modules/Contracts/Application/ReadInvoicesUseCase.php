<?php

namespace Modules\Contracts\Application;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Modules\Contracts\Entities\Invoice;

/**
 * @author Abel David.
 */
class ReadInvoicesUseCase
{
    /**
     * @param Builder|null $builder
     * @return Collection
     */
    public function __invoke(?Builder $builder = null): Collection
    {
        if (! $builder) {

            $builder = Invoice::query();
        }

        return $builder->get();
    }
}
