<?php

namespace Modules\Common\Permissions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Users\Policies\ProductPermissions;
use ReflectionClass;
use Spatie\Permission\Traits\HasRoles;
use Throwable;

/**
 * @author Abel David.
 *
 * Permission name convention must be [name_module]. For example: create_users.
 */
class PermissionManager
{
    /**
     * Permissions classes list.
     *
     * @var array<class-string>
     */
    private array $permissionClasses = [
        UserPermissions::class,
        RolePermissions::class,
        ClientPermissions::class,
        ProviderPermissions::class,
        ProductCategoryPermissions::class,
        ProductPermissions::class,
        ProductPermissions::class,
        CompanyPermissions::class,
        SettingPermissions::class,
        QuotePermissions::class,
        ContractPermissions::class,
        ReasonPermissions::class,
        ReRentPermissions::class,
        ProjectPermissions::class,
        TracePermissions::class,
        DesignPermissions::class,
        InvoicePermissions::class,
        PaymentPermissions::class,
        BonoPermissions::class,
        DashboardPermissions::class,
        InventoryPermissions::class,
        ReportPermissions::class
        
    ];

    /**
     * @return array
     */
    function getAuthUserPermissions(): array
    {
        $user = Auth::user();

        if ($user && in_array(HasRoles::class, class_uses_recursive($user))) {

            return $user->getAllPermissions()->toArray();
        }

        return [];
    }

    /**
     * @return array
     */
    function getAllPermissions(): array
    {
        $permissions = [];

        foreach ($this->permissionClasses as $permissionClass) {

            $permissions = array_merge($permissions, $this->getPermissionsFromClass($permissionClass));
        }

        return $permissions;
    }

    /**
     * @param string $class
     * @return array
     */
    function getPermissionsFromClass(string $class): array
    {
        try {

            $reflection = new ReflectionClass($class);

            return array_values($reflection->getConstants());
        } catch (Throwable $e) {

            Log::error($e->getMessage());
        }

        return [];
    }
}
