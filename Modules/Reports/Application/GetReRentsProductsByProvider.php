<?php

namespace Modules\Reports\Application;
use Modules\Products\Entities\Product;
class GetReRentsProductsByProvider
{

    public function __invoke($provider,$products_ids)
    {


        $product = Product::query(
            )->join('product_re_rent','products.id','product_re_rent.product_id'
            )->join('re_rents','product_re_rent.re_rent_id','re_rents.id'
            )->leftJoin('contract_product','products.id','contract_product.product_id'
            )->join('providers','re_rents.provider_id','providers.id'
            )->groupBy('products.id'
            )->selectRaw(
                '(SUM(product_re_rent.quantity)-SUM(COALESCE(contract_product.quantity,0))) as disponible,
                (SUM(product_re_rent.quantity)-SUM(product_re_rent.returned)) as rerents,
                (SUM(COALESCE(contract_product.quantity, 0))-SUM(COALESCE(contract_product.re_rent_return, 0))-SUM(COALESCE(contract_product.mesu_return, 0))) as rented,
                SUM(product_re_rent.total) as cost,
                products.name'
            );



            if(!is_null($provider)){
                $product->where('providers.id',$provider);

            }
            if(!is_null($products_ids)){

                $product->whereIn('products.id',$products_ids);
            }

        return $product->get();
    }

}
