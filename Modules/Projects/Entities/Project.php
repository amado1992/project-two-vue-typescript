<?php

namespace Modules\Projects\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Client\Entities\Client;
use Illuminate\Support\Str;
use Modules\Common\Entities\WithCrudPermissions;
use Modules\Common\Entities\WithSerializeDate;
use Modules\Common\Traits\HasCreators;
use Modules\Contracts\Entities\Contract;
use Modules\Users\Entities\User;

/**
 * @author Abel David.
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $project_manager
 * @property string $project_manager_phone
 * @property string $construction_manager
 * @property string $construction_manager_phone
 * @property string $in_charge_to_pay
 * @property string $in_charge_to_pay_phone
 * @property Client $client
 * @method static self create(array $data)
 */
class Project extends Model
{
    use HasFactory, WithCrudPermissions, HasCreators, WithSerializeDate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'address',
        'project_manager',
        'project_manager_phone',
        'construction_manager',
        'construction_manager_phone',
        'in_charge_to_pay',
        'in_charge_to_pay_phone',
        'client_id',
        'created_by',
        'updated_by'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'crud_permissions',
        'created_by_name',
        'updated_by_name'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    protected $with = [
        //'created_by',
        //'updated_by',
        'client'
    ];

    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return HasMany
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
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
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<static>
     */
    protected static function newFactory()
    {
        return \Modules\Projects\Database\factories\ProjectFactory::new();
    }

    public function getCreatedByNameAttribute()
    {
        return User::query()->where('id','=',$this->attributes['created_by'] ?? null)->first()?->name;

    }

    public function getUpdatedByNameAttribute()
    {

        return User::query()->where('id','=', $this->attributes['updated_by'] ?? null)->first()?->name;
    }


}
