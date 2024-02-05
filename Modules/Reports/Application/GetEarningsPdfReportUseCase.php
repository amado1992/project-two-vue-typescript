<?php

namespace Modules\Reports\Application;

use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;

/**
 * @author ylc.
 */
class GetEarningsPdfReportUseCase
{

    /**
     * @param Contract $contract
     * @return Dompdf|\Barryvdh\DomPDF\PDF
     */
    public function __invoke($data, $doctitle): Dompdf|\Barryvdh\DomPDF\PDF
    {
        return Pdf::loadView('reports::earnings.earningsPdf', [
            'data' => $data,
            'title' => $doctitle
        ])->setPaper('legal');
    }
}
