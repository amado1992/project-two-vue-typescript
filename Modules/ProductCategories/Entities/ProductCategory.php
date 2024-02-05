<?php

namespace Modules\ProductCategories\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\Common\Entities\WithCrudPermissions;
use Modules\Common\Entities\WithSerializeDate;
use Modules\Common\Traits\HasCreators;

/**
 * @property Collection<ProductCategory> $children
 */
class ProductCategory extends Model
{
    use HasFactory, HasCreators, WithCrudPermissions, WithSerializeDate;

    protected $fillable = [
        'name',
        'active',
        'product_category_id',
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

    /**
     * Scope a query to only include active users.
     *
     * @param Builder $query
     * @return void
     */
    public function scopeActive(Builder $query)
    {
        $query->where('active', 1);
    }

    protected static function newFactory()
    {
        return \Modules\ProductCategories\Database\factories\ProductCategoryFactory::new();
    }

/*    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class, 'product_category_id');
    }*/

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

    public function father(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'product_category_id');
    }
}
