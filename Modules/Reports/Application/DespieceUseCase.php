<?php

namespace Modules\Reports\Application;

use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Modules\Designs\Entities\Design;
use Modules\Quotes\Entities\Quote;

class DespieceUseCase
{
    public function __invoke(Design $design): Dompdf|\Barryvdh\DomPDF\PDF
    {
        $quote = Quote::query()->where('id',$design->quote_id)->first();
        $desing_next = $design->id;
        $client_name = $quote->client?->name ?? "";
        $project = $quote->project;
        $products = $quote->products;
        return Pdf::loadView('reports::despiece.desingdespiecePdf', [
            'title'=> 'Lista de despiece',
            'desing_next' => $desing_next,
            'client_name' => $client_name,
            'project'=> $project,
            'products' => $products
        ])->setPaper('a4', 'portrait');
    }
}
