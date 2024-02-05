<?php

namespace Modules\Products\Application;

use Illuminate\Support\Collection;
use Modules\Products\Entities\Product;
use Modules\ReRents\Entities\ProductReRent;
use Modules\ReRents\Entities\ReRent;

/**
 * @author Abel David.
 */
class GetReRentsUseCase
{
    /**
     * @param Product $product
     * @return Collection
     */
    public function __invoke(Product $product): Collection
    {
        return ProductReRent::query()
            ->with('reRent')
            ->where('product_id', $product->id)
            ->get();
    }
}
