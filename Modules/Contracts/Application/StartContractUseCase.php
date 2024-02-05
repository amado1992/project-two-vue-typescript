<?php

namespace Modules\Contracts\Application;

use InvalidArgumentException;
use Modules\Contracts\Entities\Contract;
use Modules\Contracts\Events\ContractStarted;
use Modules\Products\Entities\Product;

/**
 * @author Abel David.
 */
class StartContractUseCase
{
    /**
     * @param array $products
     * @param Contract $contract
     * @throws InvalidArgumentException
     */
    public function __invoke(array $products, Contract $contract): void
    {
        if ($contract->active_at) {

            throw new InvalidArgumentException(__('This contract is already active.'));
        }

        $sync = [];

        foreach ($products as $product) {

            if (! $this->validateData($product)) {

                throw new InvalidArgumentException(__('Inconsistent data.'));
            }

            $productModel = Product::findOrFail($product['id']);

            if ($productModel->inventory->stock - $product['mesu'] < 0) {

                throw new InvalidArgumentException(__('MESU deliver can not be greater that inventory stock.'));
            }

            if ($productModel->inventory->re_stock - $product['rented'] < 0) {

                throw new InvalidArgumentException(__('Rented deliver can not be greater that inventory re stock.'));
            }

            $productModel->inventory->fill([
                'stock' => $productModel->inventory->stock - $product['mesu'],
                'rented' => $productModel->inventory->rented + $product['mesu'],
                're_stock' => $productModel->inventory->re_stock - $product['rented'],
                're_rented' => $productModel->inventory->re_rented + $product['rented']
            ])->save();

            $sync[$product['id']] = [
                'mesu_delivered' => $product['mesu'],
                're_rent_delivered' => $product['rented']
            ];
        }

        $contract->products()->sync($sync, false);
        $contract->fill([
            'active_at' => now(),
            'active_by' => auth()->user()->getAuthIdentifier()
        ])->save();

        ContractStarted::dispatch($contract);
    }

    /**
     * @param array $product
     * @return bool
     */
    private function validateData(array $product): bool
    {
        $quantity = $product['quantity'];

        if ($quantity - $product['mesu'] < $product['rented'] || $quantity - $product['rented'] < $product['mesu']) {

            return false;
        }

        return true;
    }
}
