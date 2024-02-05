<?php

namespace Modules\Payments\Entities;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\Client\Entities\Client;
use Modules\Common\Entities\WithCrudPermissions;
use Modules\Common\Entities\WithSerializeDate;
use Modules\Common\Traits\HasCreators;
use Modules\Contracts\Entities\Invoice;

/**
 * @author Abel David.
 *
 * @property int $id
 * @property DateTime $date
 * @property float $credit
 * @property Client $client
 * @property Collection<Invoice> $invoices
 * @method static self create(array $data)
 */
class Payment extends Model
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
        'created_by',
        'updated_by',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'created_by',
        'updated_by',
        'client'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'crud_permissions',
        'credit'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'datetime:Y-m-d',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return BelongsToMany
     */
    public function invoices(): BelongsToMany
    {
        return $this->belongsToMany(Invoice::class)
            ->withPivot(['credit'])
            ->using(InvoicePayment::class);
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
    protected function getCreditAttribute(): float
    {
        $credit = 0;

        $this->invoices()->each(function ($invoice) use (&$credit) {

            $credit += $invoice->pivot->credit;
        });

        return $credit;
    }

    protected static function newFactory()
    {
        return \Modules\Payments\Database\factories\PaymentFactory::new();
    }
}
