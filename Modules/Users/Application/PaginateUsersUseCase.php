<?php

namespace Modules\Users\Application;

use Modules\Common\DataTables\DataTablePaginator;
use Modules\Users\Entities\User;

/**
 * @author Abel David.
 */
class PaginateUsersUseCase
{
    /**
     * @return DataTablePaginator
     */
    public function __invoke(): DataTablePaginator
    {
        return new DataTablePaginator(User::with(['created_by', 'updated_by']));
    }
}
