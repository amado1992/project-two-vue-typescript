<?php

namespace Modules\Users\Entities;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Modules\Client\Entities\Client;
use Modules\Common\Entities\WithCrudPermissions;
use Modules\Common\Entities\WithSerializeDate;
use Modules\Common\Traits\HasCreators;
use Modules\Roles\Entities\Role;
use Modules\Users\Database\factories\UserFactory;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $name
 * @property string $lastname
 * @property string $email
 * @property string $password
 * @property Role|null $role
 * @property bool $active
 * @method static User create(array $array)
 */
class User extends Authenticatable
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        HasRoles,
        WithCrudPermissions,
        HasCreators,
        WithSerializeDate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'lastname',
        'email',
        'password',
        'client_id',
        'created_by',
        'updated_by',
        'active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
        'created_at' => 'datetime:Y-m-d h:m:s',
        'updated_at' => 'datetime:Y-m-d h:m:s',
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'role',
        'crud_permissions'
    ];

    /**
     * @return Role|null
     */
    public function getRoleAttribute(): ?Role
    {
        return $this->roles()->first();
    }

    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return UserFactory|Factory
     */
    protected static function newFactory(): UserFactory|Factory
    {
        return new UserFactory();
    }
    protected static function boot(){
        parent::boot();
        static::saving(function($model){
            
            foreach($model->getAttributes() as $key=> $value){
                if (is_string($value )){
                    print_r($model->{$key});
                    if($key=='email' || $key=='password'){
                             continue;
                    }else{
                    $model->{$key}= Str::upper($value); 
                    }
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


