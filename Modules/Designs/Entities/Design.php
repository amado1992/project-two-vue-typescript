<?php

namespace Modules\Designs\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\Common\Entities\WithCrudPermissions;
use Modules\Common\Entities\WithSerializeDate;
use Modules\Common\Traits\HasCreators;
use Modules\Quotes\Entities\Quote;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @author Abel David.
 *
 * @property int $id
 * @property Quote $quote
 * @property Collection $media
 *
 * @method static self create(array $array)
 */
class Design extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasCreators, WithCrudPermissions, WithSerializeDate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'quote_id',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'quote',
        'media',
        'created_by',
        'updated_by'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'crud_permissions'
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
     * @return BelongsTo
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
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
            'approve' => [
                'method' => 'approve',
                'arg' => $this
            ]
        ];
    }

    protected static function newFactory()
    {
        return \Modules\Designs\Database\factories\DesignFactory::new();
    }
}
