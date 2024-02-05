<?php

namespace Modules\Products\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\Common\Entities\WithCrudPermissions;
use Modules\Common\Entities\WithSerializeDate;
use Modules\Common\Traits\HasCreators;
use Modules\Contracts\Entities\Contract;
use Modules\Inventories\Entities\Inventory;
use Modules\ProductCategories\Entities\ProductCategory;
use Modules\Travels\Entities\Travel;
use Modules\Travels\Entities\TravelProduct;

/**
 * @property int $id
 * @property float $tax
 * @property Inventory $inventory
 * @property Collection<Contract> $contracts
 *
 * @method static self findOrFail(mixed $id)
 * @method static self|null find(mixed $idKey)
 * @method static self create(mixed $product)
 */
class Product extends Model
{
    use HasFactory, HasCreators, WithCrudPermissions, WithSerializeDate;

    protected $fillable = [
        'name',
        'active',
        'product_category_id',
        'type',
        'cost_price',
        'daily_price',
        'weekly_price',
        'biweekly_price',
        'monthly_price',
        'replacement_price',
        'tax',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        "active" => 'bool',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'crud_permissions',
        'period_prices'
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'inventory'
    ];

    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function travels(): BelongsToMany
    {
        return $this->belongsToMany(Travel::class/*, "travel_product"*/);
    }

    public function TravelsProducts(): HasMany
    {
        return $this->hasMany(TravelProduct::class);
    }

    /**
     * @return HasOne
     */
    public function inventory(): HasOne
    {
        return $this->hasOne(Inventory::class);
    }

    /**
     * @return array
     */
    protected function getPeriodPricesAttribute(): array
    {
        return [
            PeriodPrices::DAILY => $this->daily_price,
            PeriodPrices::WEEKLY => $this->weekly_price,
            PeriodPrices::BIWEEKLY => $this->biweekly_price,
            PeriodPrices::MONTHLY => $this->monthly_price
        ];
    }

    protected static function newFactory()
    {
        return \Modules\Products\Database\factories\ProductFactory::new();
    }

    /**
     * @return BelongsToMany
     */
    public function contracts(): BelongsToMany
    {
        return $this->belongsToMany(Contract::class)
            ->withPivot(['price', 'quantity', 'discount', 'subtotal', 'total', 'mesu_return', 're_rent_return']);
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
