<?php

namespace Modules\Common\Permissions;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @author cheynerpb.
 */
class ProviderPermissions
{
    const CREATE = 'create_providers';    
    const READ = 'read_providers';
    const UPDATE = 'update_providers';
    const DELETE = 'delete_providers';
    const IMPORT = 'import_providers'; 
}
