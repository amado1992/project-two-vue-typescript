<?php

use Modules\Common\Permissions\ClientPermissions;
use Modules\Common\Permissions\RolePermissions;
use Modules\Common\Permissions\UserPermissions;

return [
    //Users
    'Users' => 'Usuarios',
    UserPermissions::CREATE => 'Create users',
    UserPermissions::READ   => 'Read users',
    UserPermissions::UPDATE => 'Update users',
    UserPermissions::DELETE => 'Delete users',

    //Roles
    'Roles' => 'Roles',
    RolePermissions::CREATE => 'Create roles',
    RolePermissions::READ   => 'Read roles',
    RolePermissions::UPDATE => 'Update roles',
    RolePermissions::DELETE => 'Delete roles',

    //Clients
    'Clients' => 'Clientes',
    ClientPermissions::CREATE => 'Create clients',
    ClientPermissions::READ   => 'Read clients',
    ClientPermissions::UPDATE => 'Update clients',
    ClientPermissions::IMPORT => 'Import clients',
    ClientPermissions::DELETE => 'Delete clients'


];
