<?php

namespace Modules\Common\Entities;

use Illuminate\Support\Facades\Gate;

/**
 * @author Abel David.
 */
trait WithCrudPermissions
{
    /**
     * Get common permissions for the model (read, create, edit, delete).
     *
     * @return array
     */
    protected function getCrudPermissionsAttribute(): array
    {
        $permissions = [];

        foreach ($this->commonCrudPermissions() as $permission => $policy) {

            $permissions[$permission] = Gate::inspect($policy['method'], $policy['arg'])->allowed();
        }

        return $permissions;
    }

    /**
     * Add custom permissions data.
     * Format: [
     *      'name' => [
     *          'method' => method policy,
     *          'arg' => method argument (can be an object or an array)
     *      ]
     * ]
     *
     * @return array
     */
    protected function customCrudPermissions(): array
    {
        return [
            'details' => [
                'method' => 'details',
                'arg' => $this
            ]
        ];
    }

    /**
     * Get common permissions with their arguments.
     *
     * @return array
     */
    private function commonCrudPermissions(): array
    {
        $commonPermissions = [
            'read' => [
                'method' => 'view',
                'arg' => $this
            ],
            'create' => [
                'method' => 'create',
                'arg' => self::class
            ],
            'edit' => [
                'method' => 'update',
                'arg' => $this
            ],
            'delete' => [
                'method' => 'delete',
                'arg' => $this
            ]
        ];

        return array_merge($commonPermissions, $this->customCrudPermissions());
    }
}
