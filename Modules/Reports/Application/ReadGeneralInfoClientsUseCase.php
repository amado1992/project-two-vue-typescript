<?php

namespace Modules\Reports\Application;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Modules\Client\Entities\Client;
use Modules\Products\Entities\Product;
use Modules\Projects\Entities\Project;

/**
 * @author ylc.
 */
class ReadGeneralInfoClientsUseCase
{
    /**
     * 
     * @return Collection<Clients>
     */
    public function __invoke($clients_arr = null, $products_arr = null, $projects = null)
    {
    
        if (!$products_arr) {
            $products_arr = Product::pluck('id')->toArray();
        } 
       
        if (!$projects) {
            $projects = Project::pluck('id')->toArray();
        }

        if (!$clients_arr) {
            $clients_arr = Client::pluck('id')->toArray();
        }

        $clients = Client::query()->withWhereHas('projects', function ($query) use ($projects, $clients_arr) {
            $query->whereIn('id', $projects)->whereIn('client_id', $clients_arr)->with('contracts');
        })->get();

        if ($clients_arr) {
            $clients = $clients->whereIn('id', $clients_arr);
        }

        foreach ($clients as $key => $client) {

            $quantity = 0;
            $total_month_price = 0;
            $contracts_quantity = 0;
            $mesu_return = 0;
            foreach ($client->projects as $project) {

                $contracts_quantity  += $project->contracts->where('status', 'active')->count();

                foreach ($project->contracts as $contract) {

                    if ($contract->status == 'active') {

                        $contract->products->each(function (Product $product) use (&$quantity, &$total_month_price,  &$mesu_return, $products_arr) {
                        
                            if (in_array($product->id, $products_arr)) {
                                $quantity += $product->pivot->quantity;
                                $mesu_return += $product->pivot->mesu_return;
                                $total_month_price += $product->pivot->subtotal ?? 0;
                            }            
                        });
                    }
                }
             
                $rented = $quantity - $mesu_return;
                $client->quantity = $rented;
                $client->total_month_price = money($total_month_price);
                $client->contracts_quantity =  $contracts_quantity;
            }
        }

        return $clients->where('contracts_quantity', '!=', 0); 
       /*  return $clients; */
    }
}
