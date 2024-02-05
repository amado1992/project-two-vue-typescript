<?php

namespace Modules\Contracts\Application;

use Modules\Common\DataTables\DataTablePaginator;
use Modules\Contracts\Entities\Contract;
use Modules\Contracts\Entities\Invoice;

/**
 * @author Abel David.
 */
class PaginateInvoicesUseCase
{
    /**
     * @param Contract $contract
     * @return DataTablePaginator
     */
    public function __invoke(Contract $contract): DataTablePaginator
    {
        return new DataTablePaginator(Invoice::query()
            ->where('contract_id', $contract->id)
        );
    }
}
