<?php

use Modules\Common\Permissions\BonoPermissions;
use Modules\Common\Permissions\CompanyPermissions;
use Modules\Common\Permissions\ContractPermissions;
use Modules\Common\Permissions\DashboardPermissions;
use Modules\Common\Permissions\DesignPermissions;
use Modules\Common\Permissions\InvoicePermissions;
use Modules\Common\Permissions\PaymentPermissions;
use Modules\Common\Permissions\ProductCategoryPermissions;
use Modules\Common\Permissions\ProjectPermissions;
use Modules\Common\Permissions\QuotePermissions;
use Modules\Common\Permissions\ReasonPermissions;
use Modules\Common\Permissions\ReRentPermissions;
use Modules\Common\Permissions\RolePermissions;
use Modules\Common\Permissions\SettingPermissions;
use Modules\Common\Permissions\TracePermissions;
use Modules\Common\Permissions\UserPermissions;
use Modules\Common\Permissions\ClientPermissions;
use Modules\Common\Permissions\ProviderPermissions;
use Modules\Common\Permissions\BrandPermissions;
use Modules\Common\Permissions\InventoryPermissions;
use Modules\Common\Permissions\ProductPermissions;
use Modules\Common\Permissions\ReportPermissions;

return [

    //Users
    'Users' => 'Usuarios',
    UserPermissions::CREATE => 'Crear usuarios',
    UserPermissions::READ   => 'Listar usuarios',
    UserPermissions::UPDATE => 'Actualizar usuarios',
    UserPermissions::DELETE => 'Eliminar usuarios',
    UserPermissions::IMPORT => 'Importar usuarios',

    //Roles
    'Roles' => 'Roles',
    RolePermissions::CREATE => 'Crear roles',
    RolePermissions::READ   => 'Listar roles',
    RolePermissions::UPDATE => 'Actualizar roles',
    RolePermissions::DELETE => 'Eliminar roles',

    //Clients
    'Clients' => 'Clientes',
    ClientPermissions::CREATE => 'Crear clientes',
    ClientPermissions::READ   => 'Listar clientes',
    ClientPermissions::UPDATE => 'Actualizar clientes',
    ClientPermissions::DELETE => 'Eliminar clientes',
    ClientPermissions::IMPORT => 'Importar clientes',

    //Providers
    'Providers' => 'Proveedores',
    ProviderPermissions::CREATE => 'Crear proveedores',
    ProviderPermissions::READ   => 'Listar proveedores',
    ProviderPermissions::UPDATE => 'Actualizar proveedores',
    ProviderPermissions::DELETE => 'Eliminar proveedores',    
    ProviderPermissions::IMPORT => 'Importar proveedores',

    //Brands
    'Brands' => 'Marcas',
    BrandPermissions::CREATE => 'Crear marcas',
    BrandPermissions::READ   => 'Listar marcas',
    BrandPermissions::UPDATE => 'Actualizar marcas',
    BrandPermissions::DELETE => 'Eliminar marcas',

    //Products
    'Products' => 'Productos',
    ProductPermissions::CREATE => 'Crear productos',
    ProductPermissions::READ   => 'Listar productos',
    ProductPermissions::UPDATE => 'Actualizar productos',
    ProductPermissions::DELETE => 'Eliminar productos',    
    ProductPermissions::IMPORT => 'Importar productos',

    //Product Categories
    'ProductCategories' => 'Categorías de los Productos',
    ProductCategoryPermissions::CREATE => 'Crear categorías de los productos',
    ProductCategoryPermissions::READ   => 'Listar categorías de los productos',
    ProductCategoryPermissions::UPDATE => 'Actualizar categorías de los productos',
    ProductCategoryPermissions::DELETE => 'Eliminar categorías de los productos',
    ProductCategoryPermissions::IMPORT => 'Importar categorías de los productos',

    //Company
    'Companies' => 'Empresas',
    CompanyPermissions::READ => 'Ver datos de la empresa',
    CompanyPermissions::UPDATE => 'Actualizar datos de la empresa',
    CompanyPermissions::IMPORT => 'Importar datos de la empresa',

    //Settings
    'Settings' => 'Configuraciones',
    SettingPermissions::READ => 'Ver configuraciones',
    SettingPermissions::UPDATE => 'Guardar configuraciones',

    //Quotes
    'Quotes' => 'Cotizaciones',
    QuotePermissions::CREATE => 'Crear cotizaciones',
    QuotePermissions::READ => 'Listar cotizaciones',
    QuotePermissions::UPDATE => 'Actualizar cotizaciones',
    QuotePermissions::DELETE => 'Eliminar cotizaciones',
    QuotePermissions::APPROVE => 'Aprobar cotizaciones',

    //Contracts
    'Contracts' => 'Contratos',
    ContractPermissions::CREATE => 'Crear contratos',
    ContractPermissions::READ => 'Listar contratos',
    ContractPermissions::UPDATE => 'Actualizar contratos',
    ContractPermissions::DELETE => 'Eliminar contratos',
    ContractPermissions::UPDATE_DATE => 'Actualizar fecha de inicio de contratos',
    ContractPermissions::CANCEL => 'Anular contratos',

    //Reasons
    'Reasons' => 'Motivos',
    ReasonPermissions::CREATE => 'Crear motivos',
    ReasonPermissions::READ => 'Listar motivos',
    ReasonPermissions::UPDATE => 'Actualizar motivos',
    ReasonPermissions::DELETE => 'Eliminar permisos',
    ReasonPermissions::IMPORT => 'Importar motivos',

    //ReRents
    'Rents' => 'Re Alquileres',
    ReRentPermissions::CREATE => 'Crear re alquileres',
    ReRentPermissions::READ => 'Listar re alquileres',
    ReRentPermissions::UPDATE => 'Actualizar re alquileres',
    ReRentPermissions::DELETE => 'Eliminar re alquileres',

    //Projects
    'Projects' => 'Proyectos',
    ProjectPermissions::CREATE => 'Crear proyectos',
    ProjectPermissions::READ => 'Listar proyectos',
    ProjectPermissions::UPDATE => 'Actualizar proyectos',
    ProjectPermissions::DELETE => 'Eliminar proyectos',
    ProjectPermissions::IMPORT => 'Importar proyectos',

    //Traces
    'Traces' => 'Trazas',
    TracePermissions::READ => 'Listar trazas',
    TracePermissions::CLEAR => 'Limpiar trazas',

    //Designs
    'Designs' => 'Diseños',
    DesignPermissions::CREATE => 'Crear diseños',
    DesignPermissions::READ => 'Listar diseños',
    DesignPermissions::UPDATE => 'Actualizar diseños',
    DesignPermissions::DELETE => 'Eliminar diseños',
    DesignPermissions::APPROVE => 'Aprobar diseños',

    //Invoices
    'Invoices' => 'Facturas',
    InvoicePermissions::CREATE => 'Crear facturas',
    InvoicePermissions::READ => 'Listar facturas',
    InvoicePermissions::DELETE => 'Eliminar facturas',

    //Bonos
    'Bonos' => 'Abonos',
    BonoPermissions::CREATE => 'Crear abono',
    BonoPermissions::READ => 'Listar abonos',
    BonoPermissions::UPDATE => 'Actualizar abonos',
    BonoPermissions::DELETE => 'Eliminar abonos',

    //Payments
    'Payments' => 'Pagos',
    PaymentPermissions::CREATE => 'Crear pagos',
    PaymentPermissions::READ => 'Listar pagos',
    PaymentPermissions::UPDATE => 'Actualizar pagos',
    PaymentPermissions::DELETE => 'Eliminar pagos',
    PaymentPermissions::DETAILS => 'Ver detalles',

    //Dashboard
    'Dashboard' => 'Dashboard',
    DashboardPermissions::READ => 'Ver el dashboard',

    //Inventories
    'Inventory' => 'Inventarios',
    InventoryPermissions::CREATE => 'Crear inventario',
    InventoryPermissions::READ => 'Listar inventarios',
    InventoryPermissions::UPDATE => 'Actualizar inventarios',
    InventoryPermissions::DELETE => 'Eliminar inventarios',

      //Reports
      'Reports' => 'Reportes',
      ReportPermissions::CREATE => 'Crear reportes',
];
