<?php

namespace Modules\Client\Entities;

use Modules\Common\Entities\WithCrudPermissions;
use Modules\Common\Entities\WithSerializeDate;
use Modules\Common\Traits\HasCreators;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Users\Entities\User;
use Modules\Projects\Entities\Project;

/**
 * @property int $id
 * @property float $credit
 * @method static self|null find(mixed $client_id)
 * @property string $name
 */
class Client extends Model
{
    use HasFactory, HasCreators, WithCrudPermissions, WithSerializeDate;

    protected $fillable = [
        'name',
        'active',
        'ruc',
        'dv',
        'no_taxes',
        'phone',
        'mobile',
        'email',
        'address',
        'legal_representative',
        'cedula',
        'contacts',
        'credit',
        'created_by',
        'updated_by',
        'ficha',
        'redi'
    ];

    protected $casts = [
        "active" => 'bool',
        "no_taxes" => 'bool',
        'created_at' => 'datetime:Y-m-d h:m:s',
        'updated_at' => 'datetime:Y-m-d h:m:s',
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
        return \Modules\Client\Database\factories\ClientFactory::new();
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class);
    }

    public function projects(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Project::class);
    }

    protected static function boot(){
        parent::boot();
        static::saving(function($model){

            foreach($model->getAttributes() as $key=> $value){
                if (is_string($value )){
                    print_r($model->{$key});
                    if($key=='email'){
                             continue;
                    }else{
                    $model->{$key}= Str::upper($value);
                    }
                }
            }
        });
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
            'import' => [
                'method' => 'import',
                'arg' => $this
            ]
        ];
    }
}
