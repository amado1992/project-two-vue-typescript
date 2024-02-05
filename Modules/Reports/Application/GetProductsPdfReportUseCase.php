<?php

namespace Modules\Reports\Application;

use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;

/**
 * @author ylc.
 */
class GetProductsPdfReportUseCase
{

    /**
     * @param Contract $contract
     * @return Dompdf|\Barryvdh\DomPDF\PDF
     */
     public function __invoke($products, $readProductsDetailsUseCase, $clients = null): Dompdf|\Barryvdh\DomPDF\PDF
    {
          
        return Pdf::loadView('reports::products.productsPdf', [
            'products' => $products,
            'arrs' => $readProductsDetailsUseCase, 
            'clients' => $clients

        ])->setPaper('legal');
    } 
}
