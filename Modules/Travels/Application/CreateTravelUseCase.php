<?php

namespace Modules\Travels\Application;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Travels\Entities\Travel;
use Modules\Contracts\Entities\Contract;

/**
 * @author Amado Rafael.
 */
class CreateTravelUseCase
{
    /**
     * @param array $data
     * @param Contract $contract
     * @return Travel
     */
    public function __invoke(array $data, Contract $contract): Travel
    {
        $rowCount = $this->row_count_table();

        if($rowCount == 0){
            $rowCount = 1;
        }

        $sync = [];
        $contractSync = [];

        foreach ($data['products'] as $product) {

            $sync[$product['id']] = [
                'carried_by_client' => $product['carried_by_client'],
            ];

            $productModel = $contract->products()->findOrFail($product['id']);
            $contractSync[$product['id']] = [
                'carried_by_client' => $productModel->pivot->carried_by_client + $product['carried_by_client']
            ];
        }

            $travelProduct = Travel::create([
                'travel_date' => $data['travel_date'],
                'book' => $rowCount,
                'contract_id' => $contract->id,
                'created_by' => auth()->user()->getAuthIdentifier()
            ]);

            $travelProduct->products()->sync($sync);
            $contract->products()->sync($contractSync, false);
            return $travelProduct;
    }

    public function row_count_table(){
        $rowCount = DB::table('travels')->count();
        return $rowCount;
    }
}
