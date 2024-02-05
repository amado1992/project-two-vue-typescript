<?php

namespace Modules\Roles\Application;

use Modules\Common\DataTables\DataTablePaginator;
use Modules\Roles\Entities\Role;

/**
 * @author Abel David.
 */
class PaginateRolesUseCase
{
    /**
     * @return DataTablePaginator
     */
    public function __invoke(): DataTablePaginator
    {
        return new DataTablePaginator(Role::with(['created_by', 'updated_by']));
    }
}
