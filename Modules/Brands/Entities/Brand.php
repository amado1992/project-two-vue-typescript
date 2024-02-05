<?php

namespace Modules\Brands\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Modules\Common\Entities\WithCrudPermissions;
use Modules\Common\Traits\HasCreators;

class Brand extends Model
{
    use HasFactory, HasCreators, WithCrudPermissions;

    protected $fillable = [
        'name',
        'active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        "active" => 'bool',
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
        return \Modules\Brands\Database\factories\BrandFactory::new();
    }

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
}
