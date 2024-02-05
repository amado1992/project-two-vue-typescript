<?php

namespace Modules\Reports\Application;

use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Modules\Contracts\Entities\Contract;

class TemplateTravelsUseCase
{
    public function __invoke(Contract $contract): Dompdf|\Barryvdh\DomPDF\PDF
    {
        $data = $contract->load(['products', 'project', 'project.client']);

        $client_name =$data->project?->client?->name ?? "";
        $project = $data->project;
        $products = $data->products;

        return Pdf::loadView('reports::contracts.travelsTemplatePdf', [
            'contract_id' => $contract->id,
            'title' => 'Retiro de productos',
            'client_name' => $client_name,
            'project'=> $project,
            'products' => $products
        ])->setPaper('a4', 'portrait');
    }
}
