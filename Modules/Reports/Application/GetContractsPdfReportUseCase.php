<?php

namespace Modules\Reports\Application;

use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;

/**
 * @author ylc.
 */
class GetContractsPdfReportUseCase
{

    /**
     * @param Contract $contract
     * @return Dompdf|\Barryvdh\DomPDF\PDF
     */
    public function __invoke($clients, $products, $projects): Dompdf|\Barryvdh\DomPDF\PDF
    {
        return Pdf::loadView('reports::contracts.contractsPdf', [
            'clients' => $clients,
            'products' => $products,
            'projects' => $projects
        ])->setPaper('legal');
    }
}
