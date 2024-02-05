<?php

namespace Modules\Products\Application;

use Modules\Products\Entities\Product;
use Modules\Products\Events\DeletingProduct;
use Modules\Products\Events\ProductDeleted;

/**
 * @author cheynerpb.
 */
class DeleteProductUseCase
{
    public function __invoke(Product $product): bool
    {
        DeletingProduct::dispatch($product);

        $product->delete();

        ProductDeleted::dispatch($product);

        return true;
    }
}
