<?php

namespace Modules\Common\Permissions;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @author cheynerpb.
 */
class ProductCategoryPermissions
{
    const CREATE = 'create_productCategories';
    const READ = 'read_productCategories';
    const UPDATE = 'update_productCategories';
    const DELETE = 'delete_productCategories';
    const IMPORT = 'import_productCategories';
}
