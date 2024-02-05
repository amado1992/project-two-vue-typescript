<?php

namespace Modules\Payments\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Str;
use Modules\Common\Entities\WithSerializeDate;

/**
 * @author Abel David.
 *
 * @property float $credit
 */
class InvoicePayment extends Pivot
{
    use WithSerializeDate;

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
}
