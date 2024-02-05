<?php

namespace Modules\ReRents\Application;

use Modules\Products\Entities\Product;
use Modules\ReRents\Entities\ReRent;
use Modules\ReRents\Entities\ReRentReturn;
use Modules\ReRents\Events\CreatingReRentReturn;
use Modules\ReRents\Events\ReRentReturnCreated;

/**
 * @author Abel David.
 */
class CreateReRentReturnUseCase
{
    /**
     * @param array $data
     * @param ReRent $reRent
     * @return ReRentReturn|null
     */
    public function __invoke(array $data, ReRent $reRent): ?ReRentReturn
    {

        if ($this->someHasValue($data['products'])) {

            CreatingReRentReturn::dispatch();

            $data['re_rent_id'] = $reRent->id;

            $reRentReturn = ReRentReturn::create($data);

            $this->syncProducts($reRentReturn, $reRent, $data['products']);

            ReRentReturnCreated::dispatch($reRentReturn);

            return $reRentReturn;
        }

        return null;
    }

    /**
     * @param ReRentReturn $reRentReturn
     * @param ReRent $reRent
     * @param array $products
     * @return void
     */
    private function syncProducts(ReRentReturn $reRentReturn, ReRent $reRent, array $products): void
    {
        $sync = [];

        $reRentSync = [];

        foreach ($products as $product) {

            $quantity = $product['quantity'];

            if ($quantity) {

                $productModel = $reRent->products()->find($product['id']);

                if ($productModel->inventory->re_stock >= $quantity) {

                    $productModel->inventory->fill([
                        're_quantity' => $productModel->inventory->re_quantity - $quantity,
                        're_stock' => $productModel->inventory->re_stock - $quantity
                    ])->save();

                    $reRentSync[$product['id']] = [
                        'returned' => $productModel->pivot->returned + $product['quantity']
                    ];

                    $sync[$product['id']] = [
                        'quantity' => $product['quantity']
                    ];
                }
            }
        }

        $reRentReturn->products()->sync($sync);
        $reRent->products()->sync($reRentSync, false);
    }

    /**
     * @param array $products
     * @return bool
     */
    private function someHasValue(array $products): bool
    {
        foreach ($products as $product) {

            if ($product['quantity'] > 0) {

                return true;
            }
        }

        return false;
    }
}
