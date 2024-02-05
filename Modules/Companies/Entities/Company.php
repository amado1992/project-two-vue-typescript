<?php

namespace Modules\Companies\Entities;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Common\Entities\WithCrudPermissions;
use Modules\Common\Entities\WithSerializeDate;
use Modules\Common\Traits\HasCreators;
use Illuminate\Support\Str;
use Modules\Common\Traits\HasImage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Company extends Model implements HasMedia
{
    use HasFactory, HasCreators, InteractsWithMedia, HasImage, WithCrudPermissions, WithSerializeDate;

    protected $fillable = [
        'name',
        'social_reason',
        'ruc',
        'dv',
        'contact_information',
        'email',
        'color',
        'address',
        'created_by',
        'updated_by'
    ];

    protected $appends = [
        'logo',
        'download_logo',
        'crud_permissions'
    ];


    protected static function newFactory()
    {
        return \Modules\Companies\Database\factories\CompanyFactory::new();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile();
    }


    public function registerMediaConversions(Media $media = null) : void
    {
        $this->addMediaConversion('thumb')
            ->width(200)
            ->height(200)
            ->sharpen(10);
    }

    /**
     * Get the user's first name.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function logo(): Attribute
    {
        return Attribute::make(
            get: function  ($value) {
                if($this->downloadImage()){
                   return $this->downloadImage()->getUrl();
                }
                return null;
            },
        );
    }

    protected function downloadLogo(): Attribute
    {
        return Attribute::make(
            get: function  ($value) {
                return $this->downloadImage();
            },
        );
    }

    protected static function boot(){
        parent::boot();
        static::saving(function($model){
            foreach($model->getAttributes() as $key=> $value){

                if($key=='email'){
                    $model->{$key}= Str::lower($value);
                    continue;
                }

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
