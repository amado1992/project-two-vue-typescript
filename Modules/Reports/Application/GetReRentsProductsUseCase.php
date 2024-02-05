<?php

namespace Modules\Reports\Application;
use Modules\Providers\Entities\Provider;
class GetReRentsProductsUseCase
{

    public function __invoke(?array $provider, ?array $products)
    {


        $providers = Provider::query(
            )->join('re_rents','providers.id','re_rents.provider_id'
            )->join('product_re_rent','re_rents.id','product_re_rent.re_rent_id'
            )->join('products','product_re_rent.product_id','products.id'
            )->leftJoin('contract_product','products.id','contract_product.product_id'
            )->groupBy('providers.id'
            )->selectRaw(
                '(SUM(product_re_rent.quantity)-SUM(COALESCE(contract_product.quantity,0))) as disponible,
                (SUM(COALESCE(contract_product.quantity, 0))-SUM(COALESCE(contract_product.re_rent_return, 0))-SUM(COALESCE(contract_product.mesu_return, 0))) as rented,
                (SUM(product_re_rent.quantity)-SUM(product_re_rent.returned)) as rerents,
                SUM(product_re_rent.total) as cost,
                providers.name,providers.id'

            );

            if($provider){
               $providers->whereIn('providers.id',$provider);
            }

            if($products){

                $providers->whereIn('products.id',$products);
            }


        return $providers->get();
    }

}
