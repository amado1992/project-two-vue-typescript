<?php

namespace Modules\Inventories\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Modules\Common\Entities\WithSerializeDate;
use Modules\Products\Entities\Product;


/**
 * @author Abel David.
 *
 * @property int $id
 * @property int $quantity
 * @property float $value
 * @property int $stock
 * @property int $rented
 * @property int $re_quantity
 * @property int $re_stock
 * @property int $re_rented
 *
 * @method static self create(array $data)
 */
class Inventory extends Model
{
    use HasFactory, WithSerializeDate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'quantity',
        'value',
        'stock',
        'product_id',
        'rented',
        're_quantity',
        're_stock',
        're_rented'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'value'
    ];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return float
     */
    protected function getValueAttribute(): float
    {
        return $this->product()->first()->cost_price * $this->quantity;
    }

    protected static function newFactory()
    {
        return \Modules\Inventories\Database\factories\InventoryFactory::new();
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
