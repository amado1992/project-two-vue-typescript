<?php

namespace Modules\Providers\Entities;

use Modules\Common\Entities\WithCrudPermissions;
use Modules\Common\Entities\WithSerializeDate;
use Modules\Common\Traits\HasCreators;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $ruc
 * @property string $dv
 * @property array $contacts
 * @property bool $active
 *
 * @method static self create(array $array)
 */
class Provider extends Model
{
    use HasFactory, HasCreators, WithCrudPermissions, WithSerializeDate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'address',
        'ruc',
        'dv',
        'contacts',
        'active',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        "active" => 'bool',
        'created_at' => 'datetime:Y-m-d h:m:s',
        'updated_at' => 'datetime:Y-m-d h:m:s',

    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
       
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'crud_permissions'
    ];

    protected static function newFactory()
    {
        return \Modules\Providers\Database\factories\ProviderFactory::new();
    }
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
