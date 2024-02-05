<?php

namespace Modules\Inventories\Entities;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\Common\Entities\WithSerializeDate;
use Modules\Common\Traits\HasCreators;
use Modules\Products\Entities\Product;

/**
 * @author Abel David.
 *
 * @property int $id
 * @property DateTime $date
 * @property string $type
 * @property string $observations
 * @property float $total
 * @property Collection<Product> $products
 * @method static self create(array $data)
 */
class Movement extends Model
{
    use HasFactory, HasCreators, WithSerializeDate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'date',
        'type',
        'observations',
        'reason_id',
        'created_by',
        'updated_by',
        'observations'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'total'
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'reason',
        'created_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s'
    ];

    /**
     * @return BelongsTo
     */
    public function reason(): BelongsTo
    {
        return $this->belongsTo(Reason::class)
            ->withoutGlobalScope(SoftDeletingScope::class);
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
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot(['price', 'quantity']);
    }

    /**
     * @return float
     */
    protected function getTotalAttribute(): float
    {
        $total = 0;

        $this->products->each(function (Product $product) use (&$total) {
            $total += $product->pivot->price * $product->pivot->quantity;
        });

        return $total;
    }

    protected static function newFactory()
    {
        return \Modules\Inventories\Database\factories\MovementFactory::new();
    }
}
