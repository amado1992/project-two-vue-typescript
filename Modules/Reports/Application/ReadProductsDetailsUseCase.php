<?php

namespace Modules\Reports\Application;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Modules\Client\Entities\Client;
use Modules\Products\Entities\Product;

/**
 * @author ylc.
 */
class ReadProductsDetailsUseCase
{
    /**
     * Must return all productos filters for product report.
     * @return Collection<Product>
     */
    public function __invoke($product_id, $clients = null): array
    {
        $data = [];

        $product = Product::find($product_id);
        foreach ($product->contracts as $contract) {

            if ($contract->status == 'active') {

                $quantity = $contract->pivot->quantity - $contract->pivot->mesu_return - $contract->pivot->re_rent_return;
                array_push($data, ['id' => $contract->id, 'name' => $contract->project->client->name, 'client_id' => $contract->project->client->id, 'quantity' => $quantity, 'mesu_return' => $contract->pivot->mesu_return]);
            }
        }

        $data = collect($data);

        $quantity = $data->groupBy('client_id')->map(function ($row) {
            return $row->sum('quantity');
        });

        $arrs = [];

        if ($clients == null) {
            $clients = Client::pluck('id')->toArray();
        }

        foreach ($quantity as $key => $q) {

            $client = Client::whereId($key)->first();
            if (in_array($client->id, $clients) && $q !== 0) {
                array_push($arrs, ['id' => $client->id, 'name' => $client->name, 'quantity' => $q]);
            }
        }

        return $arrs;
    }
}
