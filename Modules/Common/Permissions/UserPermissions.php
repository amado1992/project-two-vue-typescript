<?php

namespace Modules\Common\Permissions;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @author Abel David.
 */
class UserPermissions
{
    const CREATE = 'create_users';
    const READ = 'read_users';
    const UPDATE = 'update_users';
    const DELETE = 'delete_users';
    const IMPORT = 'import_users';
}
