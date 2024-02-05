<?php

namespace Modules\Quotes\Entities;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\Common\Entities\HasContractibleProducts;
use Modules\Common\Entities\WithCrudPermissions;
use Modules\Common\Entities\WithSerializeDate;
use Modules\Common\Traits\HasCreators;
use Modules\Contracts\Entities\Contract;
use Modules\Designs\Entities\Design;
use Modules\Products\Entities\Product;
use Modules\Projects\Entities\Project;
use Modules\Client\Entities\Client;
use Modules\Users\Entities\User;

/**
 * @author Abel David.
 *
 * @property int $id
 * @property DateTime $date
 * @property int $period
 * @property bool $tax_exempt
 * @property string $observations
 * @property float $tax
 * @property float $subtotal
 * @property float $total
 * @property bool $approved
 * @property User $commercial
 * @property Project $project
 * @property Contract|null $contract
 * @property Collection<ProductQuote> $products
 * @property User|null $created_by
 * @property User|null $updated_by
 * @method static self create(array $data)
 * @method static self|null find(mixed $quote_id)
 */
class Quote extends Model implements HasContractibleProducts
{
    use HasFactory, WithCrudPermissions, HasCreators, WithSerializeDate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'date',
        'client_id',
        'period',
        'tax_exempt',
        'observations',
        'approved',
        'user_id',
        'project_id',
        'contract_id',
        'created_by',
        'updated_by'
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
        'approved' => 'bool'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'crud_permissions',
        'tax',
        'subtotal',
        'total',
        'no_contract',
        'design_id',
        'mediadesing'
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'commercial',
        'project',
        'client',
        'products',
        'created_by',
        'updated_by',
    ];

    /**
     * @return BelongsTo
     */
    public function commercial(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
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
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return BelongsTo
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
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
                'total'
            ])->using(ProductQuote::class);
    }

    /**
     * @return float
     */
    protected function getTaxAttribute(): float
    {
        $tax = 0;

        $this->products()->each(function (Product $product) use (&$tax) {

            $tax += $product->pivot->tax;
        });

        return $tax;
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

    public  function getMediadesingAttribute(){
        $id = $this->attributes['id'];
        $desing =Design::with('media')->where('quote_id','=',$id)->first();
        return $desing != null ? $desing->media : [];
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
     * @return int|null
     */
    protected function getNoContractAttribute(): ?int
    {
        return $this->contract()->first()?->getKey();
    }

    /**
     * @return int|null
     */
    protected function getDesignIdAttribute(): ?int
    {
        return Design::query()
            ->without('quote')
            ->where('quote_id', $this->id)
            ->first()
            ?->getKey();
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
            'approve' => [
                'method' => 'approve',
                'arg' => $this
            ]
        ];
    }

    protected static function newFactory()
    {
        return \Modules\Quotes\Database\factories\QuoteFactory::new();
    }
}
