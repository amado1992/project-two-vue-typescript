<?php

namespace Modules\Common\Permissions;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @author cheynerpb.
 */
class ClientPermissions
{
    const CREATE = 'create_clients';    
    const READ = 'read_clients';
    const UPDATE = 'update_clients';
    const DELETE = 'delete_clients';
    const IMPORT = 'import_clients';
}
