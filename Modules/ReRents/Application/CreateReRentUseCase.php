<?php

namespace Modules\ReRents\Application;

use Modules\Common\Application\WithContractibleProducts;
use Modules\Products\Entities\Product;
use Modules\ReRents\Entities\ReRent;
use Modules\ReRents\Events\CreatingReRent;
use Modules\ReRents\Events\ReRentCreated;

/**
 * @author Abel David.
 */
class CreateReRentUseCase
{
    use WithContractibleProducts;

    /**
     * @param array $data
     * @return ReRent
     */
    public function __invoke(array $data): ReRent
    {
        CreatingReRent::dispatch();

        $data['created_by'] = auth()->user()->getAuthIdentifier();

        $reRent = ReRent::create($data);

        $this->syncContractibleProducts(
            $reRent,
            $data['products'],
            $data['tax_exempt'],
            function (Product $product, array $data) {

                $product->inventory->fill([
                    're_quantity' => $product->inventory->re_quantity + $data['quantity'],
                    're_stock' => $product->inventory->re_stock + $data['quantity']
                ])->save();
            }
        );

        ReRentCreated::dispatch($reRent);

        return $reRent;
    }
}
