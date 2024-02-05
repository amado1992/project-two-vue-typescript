<?php

namespace Modules\Contracts\Entities;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Modules\Products\Entities\Product;
use Illuminate\Support\Str;

/**
 * @author Abel David.
 *
 * @property int $mesu_return
 * @property int $re_rent_return
 * @property Product $product
 * @property ContractReturn $contractReturn
 */
class ContractReturnProduct extends Pivot
{
    /**
     * @return BelongsTo
     */
    public function contractReturn(): BelongsTo
    {
        return $this->belongsTo(ContractReturn::class);
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
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
