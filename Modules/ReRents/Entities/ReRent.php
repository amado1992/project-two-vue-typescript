<?php

namespace Modules\ReRents\Entities;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\Common\Entities\HasContractibleProducts;
use Modules\Common\Entities\WithCrudPermissions;
use Modules\Common\Entities\WithSerializeDate;
use Modules\Common\Traits\HasCreators;
use Modules\Products\Entities\Product;
use Modules\Providers\Entities\Provider;
use Modules\Users\Entities\User;

/**
 * @author Abel David.
 *
 * @property int $id
 * @property DateTime $start
 * @property DateTime $finish
 * @property bool $tax_exempt
 * @property DateTime $finished_at
 * @property DateTime|null $cancelled_at
 * @property string $observations
 * @property string $status
 * @property Collection<Product> $products
 * @property Collection<ReRentReturn> $returns
 *
 * @method static self create(array $data)
 */
class ReRent extends Model implements HasContractibleProducts
{
    use HasFactory, HasCreators, WithCrudPermissions, WithSerializeDate;

    const ACTIVE_STATUS = 'active';
    const PENDING_STATUS = 'pending';
    const FINISHED_STATUS = 'finished';
    const DEFEATED_STATUS = 'defeated';
    const CANCELLED_STATUS = 'cancelled';

    const START_STATUS = 'start';



    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'start',
        'finish',
        'tax_exempt',
        'finished_at',
        'cancelled_at',
        'observations',
        'created_by',
        'updated_by',
        'finished_by',
        'provider_id',
        'cancelled_by',
        'cancelled_reason',
        'active_at',
        'active_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start' => 'datetime:Y-m-d',
        'finish' => 'datetime:Y-m-d',
        'tax_exempt' => 'boolean',
        'finished_at' => 'datetime:Y-m-d H:i:s',
        'cancelled_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'active_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'provider',
        'created_by',
        'updated_by',
        'finished_by',
        'cancelled_by'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'discount',
        'tax',
        'subtotal',
        'total',
        'crud_permissions',
        'status'
    ];

    /**
     * @return BelongsTo
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    /**
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot([
                'price',
                'quantity',
                'returned',
                'discount',
                'percent_discount',
                'tax',
                'subtotal',
                'total'
            ])
            ->using(ProductReRent::class);
    }

    /**
     * @return HasMany
     */
    public function returns(): HasMany
    {
        return $this->hasMany(ReRentReturn::class)
            ->orderBy('date');
    }

    /**
     * @return BelongsTo
     */
    public function finished_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'finished_by');
    }

    /**
     * @return BelongsTo
     */
    public function cancelled_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    /**
     * @return float
     */
    protected function getSubtotalAttribute(): float
    {
        $subtotal = 0;

        $this->products->each(function (Product $product) use (&$subtotal) {
            $subtotal += $product->pivot->subtotal;
        });

        return $subtotal;
    }

    /**
     * @return float
     */
    protected function getTaxAttribute(): float
    {
        $tax = 0;

        $this->products->each(function (Product $product) use (&$tax) {

            $tax += $product->tax;
        });

        return $tax;
    }

    /**
     * @return float
     */
    protected function getDiscountAttribute(): float
    {
        $discount = 0;

        $this->products->each(function ($product) use (&$discount) {

            $subtotal = $product->pivot->price * $product->pivot->quantity;

            $discount += $subtotal * $product->pivot->discount / 100;
        });

        return $discount;
    }

    /**
     * @return float
     */
    protected function getTotalAttribute(): float
    {
        $total = 0;

        $this->products->each(function (Product $product) use (&$total) {

            $total += $product->pivot->total;
        });

        return $total;
    }

    /**
     * @return string
     */
    protected function getStatusAttribute(): string
    {
        if ($this->cancelled_at) {

            return self::CANCELLED_STATUS;
        }

        if ($this->finished_at) {

            return self::FINISHED_STATUS;
        }

        if (now()->gt($this->finish)) {

            return self::DEFEATED_STATUS;
        }

        if ($this->active_at) {

            return self::ACTIVE_STATUS;
        }

        return self::PENDING_STATUS;
    }

    public function active_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'active_by');
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
            'finish' => [
                'method' => 'finish',
                'arg' => $this
            ],
            'cancel' => [
                'method' => 'cancel',
                'arg' => $this
            ],
            'returns' => [
                'method' => 'returns',
                'arg' => $this
            ],
            'add_returns' => [
                'method' => 'addReturns',
                'arg' => $this
            ],
            'start' => [
        'method' => 'start',
        'arg' => $this
    ],
            'can_edit' => [
                'method' => 'edit',
                'arg' => $this
            ]
        ];
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
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<static>
     */
    protected static function newFactory()
    {
        return \Modules\ReRents\Database\factories\ReRentFactory::new();
    }
}
