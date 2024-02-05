<?php

namespace Modules\Contracts\Entities;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Modules\Common\Entities\ProductPivot;

/**
 * @author Abel David.
 *
 * @property Contract $contract
 */
class ContractProduct extends ProductPivot
{
    /**
     * @return BelongsTo
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
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
    protected function getReplacementPriceAttribute(): float
    {
        return $this->product->replacement_price * $this->quantity;
    }
}
