<?php

namespace Modules\Inventories\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\Common\Entities\WithCrudPermissions;
use Modules\Common\Entities\WithSerializeDate;
use Modules\Common\Traits\HasCreators;

/**
 * @author Abel David.
 *
 * @property int $id
 * @property string $name
 * @property bool $active
 * @property Collection<Movement> $movements
 * @method static self create(array $data)
 */
class Reason extends Model
{
    use HasFactory, WithCrudPermissions, HasCreators, SoftDeletes, WithSerializeDate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'active',
        'created_by',
        'updated_by'
    ];
// save data in upperletter
protected static function boot(){
    parent::boot();
    static::saving(function($model){
        foreach($model->getAttributes() as $key=> $value){
            if (is_string($value)){
                $model->{$key}= Str::upper($value);
            }
        }
    });
}
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'crud_permissions'
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'bool',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    /**
     * @return HasMany
     */
    public function movements(): HasMany
    {
        return $this->hasMany(Movement::class);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<static>
     */
    protected static function newFactory()
    {
        return \Modules\Inventories\Database\factories\ReasonFactory::new();
    }

    protected function customCrudPermissions(): array
    {
        return [
            'import' => [
                'method' => 'import',
                'arg' => $this
            ]
        ];
    }
}
