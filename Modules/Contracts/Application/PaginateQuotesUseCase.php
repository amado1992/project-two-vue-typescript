<?php

namespace Modules\Contracts\Application;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Modules\Common\DataTables\DataTablePaginator;
use Modules\Quotes\Entities\Quote;

/**
 * @author Abel David.
 */
class PaginateQuotesUseCase
{
    /**
     * @return DataTablePaginator
     */
    public function __invoke(): DataTablePaginator
    {
        $builder = Quote::query()
            ->whereNull('contract_id')
            ->where('approved', '=', 1);

        return new DataTablePaginator($builder);
    }
}
