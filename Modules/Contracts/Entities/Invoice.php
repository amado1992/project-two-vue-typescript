<?php

namespace Modules\Contracts\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Modules\Common\Entities\WithCrudPermissions;
use Illuminate\Support\Str;
use Modules\Common\Entities\WithSerializeDate;
use Modules\Common\Traits\HasCreators;
use Modules\Payments\Entities\InvoicePayment;
use Modules\Products\Entities\Product;

/**
 * @author Abel David.
 *
 * @property int $id
 * @property bool $was_paid
 * @property float $total
 * @property float $per_to_pay
 * @property Contract $contract
 * @property Collection<Product> $products
 * @method static self create(array $array)
 * @method static self find(mixed $idKey)
 */
class Invoice extends Model
{
    use HasFactory, HasCreators, WithSerializeDate, WithCrudPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'contract_id',
        'created_by',
        'updated_by'
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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'name',
        'subtotal',
        'discount',
        'taxes',
        'total',
        'per_to_pay',
        'was_paid',
        'crud_permissions'
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

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
                'tax',
                'subtotal',
                'total'
            ]);
    }

    protected static function newFactory()
    {
        return \Modules\Contracts\Database\factories\InvoiceFactory::new();
    }

    /**
     * @return string
     */
    protected function getNameAttribute(): string
    {
        return __('contracts::invoices.name', [
            'id' => $this->id,
            'contract' => $this->contract->id,
            //'client' => $this->contract->project->client->name
        ]);
    }

    /**
     * @return float
     */
    protected function getSubtotalAttribute(): float
    {
        $subtotal = 0;

        $this->products->each(function(Product $product) use (&$subtotal) {

            $subtotal += $product->pivot->subtotal;
        });

        return $subtotal;
    }

    /**
     * @return float
     */
    protected function getTaxesAttribute(): float
    {
        $taxes = 0;

        $this->products->each(function(Product $product) use (&$taxes) {

            $taxes += $product->pivot->tax;
        });

        return $taxes;
    }

    /**
     * @return float
     */
    protected function getDiscountAttribute(): float
    {
        $discount = 0;

        $this->products->each(function ($product) use (&$discount) {
            $discount += $product->pivot->discount;
        });

        return $discount;
    }

    protected function getTotalAttribute(): float
    {
        $total = 0;

        $this->products->each(function(Product $product) use (&$total) {

            $total += $product->pivot->total;
        });

        return $total;
    }

    /**
     * @return float
     */
    protected function getPerToPayAttribute(): float
    {
        $paymentsCredit = 0;
        InvoicePayment::query()
            ->where('invoice_id', $this->id)
            ->each(function (InvoicePayment $invoicePayment) use (&$paymentsCredit) {
                $paymentsCredit += $invoicePayment->credit;
            });

        return max($this->total - $paymentsCredit, 0);
    }

    /**
     * @return bool
     */
    protected function getWasPaidAttribute(): bool
    {
        return $this->per_to_pay <= 0;
    }
}
