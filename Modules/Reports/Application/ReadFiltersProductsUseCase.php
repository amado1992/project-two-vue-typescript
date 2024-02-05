<?php

namespace Modules\Reports\Application;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Modules\Client\Entities\Client;
use Modules\Contracts\Entities\Contract;
use Modules\Products\Entities\Product;

/**
 * @author ylc.
 */
class ReadFiltersProductsUseCase
{
    /**
     * Must return all productos filters for product report.
     * @return Collection<Product>
     */
    public function __invoke($clients_arr = null, $products_arr = null)
    {

        if (!$clients_arr) {
            $clients_arr = Client::pluck('id')->toArray();
        }

        $products = Product::query()->withWhereHas('contracts', function ($query) use ($clients_arr) {
            $query->withWhereHas('project', function ($q) use ($clients_arr) {
                $q->whereIn('client_id', $clients_arr);
            });
        })->get();

        if ($products_arr) {
            $products = $products->whereIn('id', $products_arr);
        }

        $productsData = [];

        foreach ($products as $product) {
            $quantity = 0;

            foreach ($product->contracts as $contract) {

                if (in_array($contract->project->client_id, $clients_arr) && ($contract->status == Contract::ACTIVE_STATUS))

                    $quantity = $quantity + ($contract->pivot->quantity - $contract->pivot->mesu_return - $contract->pivot->re_rent_return);
            }

            if ($quantity != 0) {
                array_push($productsData, ['id' => $product->id, 'name' => $product->name, 'quantity' => $quantity, 'stock' => $product->inventory->stock + $product->inventory->re_stock]);
            }
        }
        
        return $productsData;

    }
}
