<?php

namespace Modules\Users\Policies;

/**
 * @author cheynerpb.
 */
class ProductPermissions
{
    const CREATE = 'create_products';
    const READ = 'read_products';
    const UPDATE = 'update_products';
    const DELETE = 'delete_products';
    const IMPORT = 'import_products';
}
