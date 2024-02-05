<?php

namespace Modules\Quotes\Entities;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Common\Entities\ProductPivot;

/**
 * @author Abel David.
 *
 * @property Quote $quote
 */
class ProductQuote extends ProductPivot
{
    /**
     * @return BelongsTo
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }
}
