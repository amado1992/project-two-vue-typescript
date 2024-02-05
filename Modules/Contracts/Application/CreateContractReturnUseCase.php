<?php

namespace Modules\Contracts\Application;

use Modules\Contracts\Entities\Contract;
use Modules\Contracts\Entities\ContractReturn;

/**
 * @author Abel David.
 */
class CreateContractReturnUseCase
{
    /**
     * @param array $data
     * @param Contract $contract
     * @return ContractReturn
     */
    public function __invoke(array $data, Contract $contract): ContractReturn
    {
        $sync = [];

        $contractSync = [];

        foreach ($data['products'] as $product) {

            $sync[$product['id']] = [
                'mesu_return' => $product['mesu'],
                're_rent_return' => $product['rented']
            ];

            $productModel = $contract->products()->findOrFail($product['id']);

            $productModel->inventory->fill([
                'stock' => $productModel->inventory->stock + $product['mesu'],
                'rented' => $productModel->inventory->rented - $product['mesu'],
                're_stock' => $productModel->inventory->re_stock + $product['rented'],
                're_rented' => $productModel->inventory->re_rented - $product['rented']
            ])->save();

            $contractSync[$product['id']] = [
                'mesu_return' => $productModel->pivot->mesu_return + $product['mesu'],
                're_rent_return' => $productModel->pivot->re_rent_return + $product['rented']
            ];
        }

        $contractReturn = ContractReturn::create([
            'contract_id' => $contract->id,
            'return_date' => $data['return_date'],
            'book' => $data['book'],
            'created_by' => auth()->user()->getAuthIdentifier()
        ]);

        $contractReturn->products()->sync($sync);
        $contract->products()->sync($contractSync, false);

        return $contractReturn;
    }
}
