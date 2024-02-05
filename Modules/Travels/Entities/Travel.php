<?php

namespace Modules\Travels\Entities;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Common\Traits\HasCreators;
use Modules\Contracts\Entities\Contract;
use Modules\Products\Entities\Product;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @author Amado Rafael.
 *
 * @property Collection<TravelProduct> $products
 * @method static self create(array $array)
 * @property int $id
 * @property Contract $contract
 * @property DateTime|null $travel_date
 */

class Travel extends Model
{
    use HasFactory, HasCreators;

    protected $table = "travels";

    protected $fillable = [
        'travel_date',
        'book',
        'contract_id',
        'created_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'travel_date' => 'datetime:Y-m-d',
        'created_at' => 'datetime:Y-m-d H:i:s'
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'created_by'
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class,"travel_product")
            ->withPivot([
                'carried_by_client',
                'travel_id',
                'product_id'
            ])
            ->using(TravelProduct::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function TravelsProducts(): HasMany
    {
        return $this->hasMany(TravelProduct::class);
    }

    protected static function newFactory()
    {
        return \Modules\Travels\Database\factories\TravelFactory::new();
    }
}
