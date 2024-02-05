<?php

namespace Modules\Client\Application;

use Modules\Common\DataTables\DataTablePaginator;
use Modules\Client\Entities\Client;

/**
 * @author cheynerpb.
 */
class PaginateClientsUseCase
{
    /**
     * @return DataTablePaginator
     */
    public function __invoke(): DataTablePaginator
    {
        return new DataTablePaginator(Client::with(['created_by', 'updated_by']));
    }
}
