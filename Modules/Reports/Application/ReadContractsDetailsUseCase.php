<?php

namespace Modules\Reports\Application;


use Illuminate\Support\Collection;
use Modules\Client\Entities\Client;
use Modules\Projects\Entities\Project;

/**
 * @author ylc.
 */
class ReadContractsDetailsUseCase
{
    /**
     * esto no se usa, eliminar
     * Must return all productos filters for product report.
     * @return Collection<Product>
     */
    public function __invoke($client, $clients = null): array
    {
       /*  $data = [];

        foreach ($client->projects as $project) {

            foreach ($project->contracts as $contract){

                array_push($data, ['id' => $contract->id, 'name' => $project->client->name, 'client_id' => $contract->project->client->id, 'quantity' => $contract->pivot->quantity]);

            }
        }

        $data = collect($data);

        dd($data);

        $quantity = $data->groupBy('client_id')->map(function ($row) {
            return $row->sum('quantity');
        });


        $arrs = [];
        $contracts = $project->contracts->groupBy('project_id');
        foreach ($contracts as $key => $contract) {
         
            $project = Project::whereId($key)->first();

            $client = Client::whereId($project->client_id)->first();

            array_push($arrs, ['id' => $client->id, 'name' => $client->name, 'quantity' => $quantity[$client->id]]); 
        }

        return $arrs; */


        $quantity = 0;
        $total_month_price = 0;
        $contracts_quantity = 0;

     /*    $projects_name = Project::where('client_id', $client->id)->pluck('name')->toArray(); */

        foreach ($client->projects as $project) {

         

            foreach ($project->contracts as $contract) {

                $contract->products->each(function (Product $product) use (&$quantity, &$total_month_price, $products_filters_arr_id) {

                    if (in_array($product->id, $products_filters_arr_id)) {
                        $quantity += $product->pivot->quantity;
                        //  $total_month_price += ($product->pivot->price * $product->pivot->quantity) ?? 0; 
                        $total_month_price += $product->pivot->subtotal ?? 0;
                    }

                    if (!$products_filters_arr_id) {
                        $quantity += $product->pivot->quantity;
                        // $total_month_price += ($product->pivot->price * $product->pivot->quantity); 
                        $total_month_price += $product->pivot->subtotal;
                    }
                });
            }

            $client->quantity = $quantity;
            $client->total_month_price = money($total_month_price);
            $client->contracts_quantity =  $contracts_quantity;
        }
    }
}
