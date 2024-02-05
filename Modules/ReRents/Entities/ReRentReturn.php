<?php

namespace Modules\ReRents\Entities;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\Common\Entities\WithSerializeDate;
use Modules\Products\Entities\Product;

/**
 * @author Abel David.
 *
 * @property int $id
 * @property DateTime $date
 * @property ReRent $reRent
 * @property Collection<Product> $products
 *
 * @method static self create(array $data)
 */
class ReRentReturn extends Model
{
    use HasFactory, WithSerializeDate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'date',
        're_rent_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'datetime:Y-m-d'
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'products'
    ];

    /**
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot(['quantity']);
    }

    protected static function newFactory()
    {
        return \Modules\ReRents\Database\factories\ReRentReturnFactory::new();
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
