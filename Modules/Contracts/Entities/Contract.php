<?php

namespace Modules\Contracts\Entities;

use Carbon\Carbon;
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
use Modules\Projects\Entities\Project;
use Modules\Quotes\Entities\Quote;
use Modules\Travels\Entities\Travel;
use Modules\Users\Entities\User;

/**
 * @author Abel David.
 *
 * @property int $id
 * @property DateTime $date
 * @property int $period
 * @property bool $tax_exempt
 * @property float $warranty_deposit
 * @property string $legal_representative
 * @property string $legal_representative_id
 * @property float $subtotal
 * @property float $total
 * @property float $tax
 * @property float $discount
 * @property DateTime|null $finished_at
 * @property DateTime|null $cancelled_at
 * @property string|null $cancelled_reason
 * @property DateTime|null $active_at
 * @property string|null $expire_at
 * @property string $status
 * @property User $commercial
 * @property Project $project
 * @property Quote|null $quote
 * @property Collection<ContractProduct> $products
 * @property Collection<ContractReturn> $returns
 * @property Collection<Renovation> $renovations
 * @method static self create(array $data)
 * @property Collection<Travel> $travels
 */
class Contract extends Model implements HasContractibleProducts
{
    use HasFactory, WithCrudPermissions, HasCreators, WithSerializeDate;

    const ACTIVE_STATUS = 'active';
    const PENDING_STATUS = 'pending';
    const FINISHED_STATUS = 'finished';
    const DEFEATED_STATUS = 'defeated';
    const RENOVATED_STATUS = 'renovated';
    const CANCELLED_STATUS = 'cancelled';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'date',
        'period',
        'tax_exempt',
        'warranty_deposit',
        'legal_representative',
        'legal_representative_id',
        'user_id',
        'client_id',
        'quote_id',
        'active_at',
        'active_by',
        'finished_at',
        'finished_by',
        'cancelled_at',
        'created_by',
        'updated_by',
        'cancelled_by',
        'cancelled_reason',
        'project_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tax_exempt' => 'bool',
        'date' => 'datetime:Y-m-d',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'active_at' => 'datetime:Y-m-d H:i:s',
        'finished_at' => 'datetime:Y-m-d H:i:s',
        'cancelled_at' => 'datetime:Y-m-d H:i:s'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'crud_permissions',
        'total',
        'status',
        'subtotal',
        'tax',
        'discount',
        'expire_at',
        //'renovations_count',
        'remainder_days'
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'commercial',
        'quote',
        'project',
        //'products',
        //'created_by',
        //'updated_by',
        //'active_by',
        //'finished_by',
        //'cancelled_by'
    ];

    /**
     * @return BelongsTo
     */
    public function commercial(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function travels(): HasMany
    {
        return $this->hasMany(Travel::class);
    }

    /**
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return BelongsTo
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
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
                'discount',
                'percent_discount',
                'tax',
                'subtotal',
                'total',
                'mesu_delivered',
                're_rent_delivered',
                'mesu_return',
                're_rent_return',
                'carried_by_client'
            ])
            ->using(ContractProduct::class);
    }

    /**
     * @return HasMany
     */
    public function returns(): HasMany
    {
        return $this->hasMany(ContractReturn::class);
    }

    /**
     * @return HasMany
     */
    public function renovations(): HasMany
    {
        return $this->hasMany(Renovation::class);
    }

    /**
     * @return BelongsTo
     */
    public function active_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'active_by');
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
    protected function getTotalAttribute(): float
    {
        $total = 0;

        $this->products()->each(function(Product $product) use (&$total) {

            $total += $product->pivot->total;
        });

        return $total;
    }

    /**
     * @return float
     */
    protected function getSubtotalAttribute(): float
    {
        $subtotal = 0;

        $this->products()->each(function (Product $product) use (&$subtotal) {
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

        $this->products()->each(function (Product $product) use (&$tax) {

            //$tax += $product->pivot->tax;
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

        $this->products()->each(function ($product) use (&$discount) {

            $discount += $product->pivot->discount;
        });

        return $discount;
    }

    /**
     * @return string
     */
    protected function getStatusAttribute(): string
    {
        if ($this->finished_at) {

            return self::FINISHED_STATUS;
        }

        if ($this->cancelled_at) {

            return self::CANCELLED_STATUS;
        }

        if ($this->getRenovationsActive()) {

            return self::RENOVATED_STATUS;
        }

        if ($this->expire_at && now()->gt($this->expire_at)) {

            return self::DEFEATED_STATUS;
        }

        if ($this->active_at) {

            return self::ACTIVE_STATUS;
        }

        return self::PENDING_STATUS;
    }

    public function getStatus(){
        return $this->getStatusAttribute();
    }

    protected function getRenovationsActive(){
        $renovations = Renovation::query()->where(
            "contract_id","=",$this->id
        )->get();

        return $renovations->some(fn (Renovation $renovation) => $renovation->active);
    }

    /**
     * @return string|null
     */
    protected function getExpireAtAttribute(): ?string
    {
        if ($this->date) {

            return Carbon::make($this->date)
                ->addDays($this->period-1)
                ->format('Y-m-d');
        }

        return null;
    }

    /**
     * @return int
     */
    /*protected function getRenovationsCountAttribute(): int
    {
        return $this->renovations()->count();
    }

    /**
     * @return int
     */
    protected function getRemainderDaysAttribute(): int
    {
        $now = Carbon::make($this->date);
        $expire_at = Carbon::make($this->expire_at);

        if ($expire_at && $now->lte($expire_at)) {

            return $expire_at->diffInDays($now);
        }

        return 0;
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
            'start' => [
                'method' => 'start',
                'arg' => $this
            ],
            'finish' => [
                'method' => 'finish',
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
            'renovations' => [
                'method' => 'renovations',
                'arg' => $this
            ],
            'add_renovations' => [
                'method' => 'addRenovations',
                'arg' => $this
            ],
            'cancel' => [
                'method' => 'cancel',
                'arg' => $this
            ],
            'update_date' => [
                'method' => 'updateDate',
                'arg' => $this
            ]
        ];
    }

    protected static function newFactory()
    {
        return \Modules\Contracts\Database\factories\ContractFactory::new();
    }
}
