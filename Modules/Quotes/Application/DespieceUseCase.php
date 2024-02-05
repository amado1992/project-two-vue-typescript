<?php

namespace Modules\Quotes\Application;

use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Modules\Quotes\Entities\Quote;

class DespieceUseCase
{
    public function __invoke(Quote $quote): Dompdf|\Barryvdh\DomPDF\PDF
    {
        $quote = Quote::query()->where('id',$quote->id)->first();
        $client_name = $quote->client?->name;
        $project = $quote->project;
        $products = $quote->products;
        return Pdf::loadView('reports::despiece.quotedespiecePdf', [
            'title'=> 'Lista de despiece',
            'client_name' => $client_name,
            'project'=> $project,
            'products' => $products
        ])->setPaper('a4', 'portrait');
    }
}
