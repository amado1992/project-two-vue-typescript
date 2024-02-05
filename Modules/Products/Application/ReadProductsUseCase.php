<?php

namespace Modules\Products\Application;

use Illuminate\Support\Collection;
use Modules\Products\Entities\Product;

/**
 * @author cheynerpb.
 */
class ReadProductsUseCase
{
    /**
     * @return Collection<Product>
     */
    public function __invoke(?array $select): Collection
    {
        if($select){
            return Product::query()->select($select)->get();
        }
        //return Product::with('brand')->get();
        return Product::all();
    }
}
