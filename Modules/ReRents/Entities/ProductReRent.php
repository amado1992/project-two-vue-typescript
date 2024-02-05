<?php

namespace Modules\ReRents\Entities;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Common\Entities\ProductPivot;

/**
 * @author Abel David.
 *
 * @property int $returned
 * @property ReRent $reRent
 */
class ProductReRent extends ProductPivot
{
    /**
     * @return BelongsTo
     */
    public function reRent(): BelongsTo
    {
        return $this->belongsTo(ReRent::class);
    }
}
