<?php

namespace Modules\Products\Application;

use Illuminate\Support\Collection;
use Modules\Products\Entities\Product;
use Modules\Providers\Entities\Provider;


class GetReRentsByProviderUseCase
{
    /**
     * @param Product $product
     * @return Collection
     */
    public function __invoke(int $provider): Collection
    {
      
        return Product::query(

        )->join('product_re_rent','products.id','product_re_rent.product_id'
        )->join('re_rents','product_re_rent.re_rent_id','re_rents.id'
        )->where('re_rents.provider_id',$provider)->get();
    }
}
