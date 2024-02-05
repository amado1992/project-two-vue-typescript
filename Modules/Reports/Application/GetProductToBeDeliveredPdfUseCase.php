<?php

namespace Modules\Reports\Application;

use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;

/**
 * @author ylc.
 */
class GetProductToBeDeliveredPdfUseCase
{

    /**
     * @param Contract $contract
     * @return Dompdf|\Barryvdh\DomPDF\PDF
     */
    public function __invoke($data, $doctitle): Dompdf|\Barryvdh\DomPDF\PDF
    {
        return Pdf::loadView('reports::to-be-delivered.productsToBeDeliveredPdf', [
            'data' => $data,
            'title' => $doctitle,
        ])->setPaper('A4', 'portrait');
    }
}
