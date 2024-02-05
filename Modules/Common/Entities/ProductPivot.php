<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Modules\Products\Entities\Product;

/**
 * @author Abel David.
 *
 * @property int $id
 * @property float $price
 * @property int $quantity
 * @property float $discount
 * @property float $percent_discount
 * @property float $tax
 * @property float $subtotal
 * @property float $total
 * @property Product $product
 */
abstract class ProductPivot extends Pivot
{
    use WithSerializeDate;

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
