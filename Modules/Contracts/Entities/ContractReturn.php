<?php

namespace Modules\Contracts\Entities;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\Common\Entities\WithSerializeDate;
use Modules\Common\Traits\HasCreators;
use Modules\Products\Entities\Product;

/**
 * @author Abel David.
 *
 * @property int $id
 * @property DateTime $return_date
 * @property int $book
 * @property Contract $contract
 * @property Collection<Product> $products
 * @method static self create(array $array)
 */
class ContractReturn extends Model
{
    use HasFactory, HasCreators, WithSerializeDate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'contract_id',
        'return_date',
        'book',
        'created_by'
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'products',
        'created_by'
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
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'return_date' => 'datetime:Y-m-d',
        'created_at' => 'datetime:Y-m-d H:i:s'
    ];

    /**
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot(['mesu_return', 're_rent_return']);
    }

    protected static function newFactory()
    {
        return \Modules\Contracts\Database\factories\ReturnFactory::new();
    }
}
