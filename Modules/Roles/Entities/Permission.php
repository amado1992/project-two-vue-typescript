<?php

namespace Modules\Roles\Entities;

use Modules\Common\Entities\WithSerializeDate;
use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * @author Abel David.
 *
 * @property string $translate_name
 * @property bool $active
 */
class Permission extends SpatiePermission
{
    use WithSerializeDate;

    /**
     * @var bool|mixed
     */
    public bool $active = false;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'translate_name',
        'active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'bool'
    ];

    /**
     * @return string
     */
    protected function getTranslateNameAttribute(): string
    {
        return __("permissions.$this->name");
    }

    /**
     * @return bool
     */
    protected function getActiveAttribute(): bool
    {
        return $this->active;
    }
}
