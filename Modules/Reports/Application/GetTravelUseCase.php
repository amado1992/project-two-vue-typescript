<?php

namespace Modules\Reports\Application;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Modules\Contracts\Entities\Contract;
use Modules\Travels\Entities\Travel;

class GetTravelUseCase
{
    public function __invoke(Travel $travel): Dompdf|\Barryvdh\DomPDF\PDF
    {
        $data_travel = $travel->load(['contract', 'contract.products', 'contract.project', 'contract.project.client', 'products']);

        $client_name = $data_travel->contract->project->client->name ?? "";
        $project = $data_travel->contract->project;
        $products = $data_travel->products;
        $travel_date =  (new Carbon($data_travel->travel_date))->format('d/m/y');

        return Pdf::loadView('reports::contracts.travelOnePdf', [
            'contract_id' => $data_travel->contract->id,
            'title' => 'Retiro de productos',
            'client_name' => $client_name,
            'project'=> $project,
            'products' => $products,
            'travel_date' => $travel_date,
            'travel' => $data_travel
        ])->setPaper('a4', 'portrait');
    }
}
