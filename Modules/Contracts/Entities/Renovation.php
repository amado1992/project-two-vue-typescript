<?php

namespace Modules\Contracts\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\Common\Entities\WithSerializeDate;
use Modules\Products\Entities\Product;
use Modules\Users\Entities\User;

/**
 * @author Abel David.
 *
 * @property int $id
 * @property float $discount
 * @property float $tax
 * @property float $subtotal
 * @property float $total
 * @property string $start
 * @property string $finish
 * @property bool $active
 * @property Contract $contract
 * @property User $commercial
 * @property Collection<Product> $products
 * @method static self create(array $array)
 */
class Renovation extends Model
{
    use HasFactory, WithSerializeDate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'discount',
        'tax',
        'subtotal',
        'total',
        'contract_id',
        'user_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'start',
        'finish'
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'commercial',
        'products'
    ];

    /**
     * @return BelongsTo
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * @return BelongsTo
     */
    public function commercial(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot([
                'price',
                'mesu_delivered',
                're_rent_delivered'
            ]);
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
     * @return string
     */
    protected function getStartAttribute(): string
    {
        return Carbon::make($this->created_at)
            ->format('Y-m-d');
    }

    /**
     * @return string
     */
    protected function getFinishAttribute(): string
    {
        return Carbon::make($this->created_at)
            ->addDays($this->contract->period)
            ->format('Y-m-d');
    }

    /**
     * @return bool
     */
    protected function getActiveAttribute(): bool
    {
        $start = Carbon::make($this->start);
        $finish = Carbon::make($this->finish);

        $now = now();

        return $now->gte($start) && $now->lte($finish);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<static>
     */
    protected static function newFactory()
    {
        return \Modules\Contracts\Database\factories\RenovationFactory::new();
    }
}
