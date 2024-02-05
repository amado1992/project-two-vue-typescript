<?php

namespace Modules\Reports\Application;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Modules\Client\Entities\Client;
use Modules\Contracts\Entities\Contract;

/**
 * @author Yulylc
 * Get clients contracts information
 */

class GetClientsContractsDataUseCase
{

    public function __invoke($clients_arr): array
    {
        $all_clients = 0;

        if (!$clients_arr) {
            $clients_arr = Client::pluck('id')->toArray();
            $all_clients = 1;
        }

        $clients = Client::whereIn('id', $clients_arr)->get();

        $getContractsByCLientsUseCase = new GetContractsByClientUseCase;
        $active_contracts = collect();
        $all_contracts = collect();

        $clients_arr_name = [];
        foreach ($clients as $client) {
            $active_contracts->push($getContractsByCLientsUseCase($client));
            $all_contracts->push($getContractsByCLientsUseCase($client, null, true));
            array_push($clients_arr_name, $client->name);
        }

        $active_contracts = $active_contracts->flatten(1);
        $all_contracts = $all_contracts->flatten(1);

        $qty_active_contracts = $active_contracts->count();

        $active_contracts_total = 0;
        $discount_total = 0;
        $subtotal = 0;
        $total_taxes = 0;

        $active_contracts->each(function (Contract $contract) use (&$active_contracts_total, &$discount_total, &$subtotal, &$total_taxes) {
            $active_contracts_total += $contract->total;
            $discount_total += $contract->discount;
            $subtotal += $contract->subtotal;
            $total_taxes += $contract->tax;
        });

        return [
            'clients' => $clients_arr_name ?? null,
            'all_clients' => $all_clients,
            'discount_total' => money($discount_total),
            'subtotal' => money($subtotal),
            'total_taxes' => money($total_taxes),
            'qty_active_contracts' =>  $qty_active_contracts,
            'active_contracts_total' => money($subtotal+$total_taxes),
            'all_contracts' => $all_contracts
        ];
    }
}
