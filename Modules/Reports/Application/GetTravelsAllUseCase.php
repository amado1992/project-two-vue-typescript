<?php

namespace Modules\Reports\Application;

use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Modules\Contracts\Entities\Contract;

class GetTravelsAllUseCase
{
    public function __invoke(Contract $contract): Dompdf|\Barryvdh\DomPDF\PDF
    {
        $data = $contract->load(['products', 'travels', 'travels.products', 'project', 'project.client']);

        $client_name =$data->project?->client?->name ?? "";
        $project = $data->project;
        $products = $data->products;
        $travels = $data->travels;

        return Pdf::loadView('reports::contracts.travelsAllPdf', [
            'contract_id' => $contract->id,
            'title' => 'Retiro de productos',
            'client_name' => $client_name,
            'project'=> $project,
            'products' => $products,
            'travels' => $travels
        ])->setPaper('a4', 'portrait');
    }
}
