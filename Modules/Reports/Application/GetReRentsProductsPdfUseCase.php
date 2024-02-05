<?php

namespace Modules\Reports\Application;

use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;

/**
 * @author andito
 */
class GetReRentsProductsPdfUseCase
{

    /**
     * @param Collection $providers
     * @return Dompdf|\Barryvdh\DomPDF\PDF
     */
    public function __invoke($data, $doctitle, $mapFunction): Dompdf|\Barryvdh\DomPDF\PDF
    {
        $arrayProvidersWithProducts = [];

        /*$width = 31.0/2.54*72;
        $height = 46.0/2.54*72;

        $customPaper = array(0,0, $height, $width);*/

        foreach ($data as $provider ) {
             array_push($arrayProvidersWithProducts, array("name"=>$provider->name,"items" => $mapFunction($provider->id,null))) ;
        }

        return Pdf::loadView('reports::rerents.productproviderspdf', [
            'data' => $arrayProvidersWithProducts,
            'title' => $doctitle,
        ])->setPaper('a4', 'portrait'); // old ->setPaper($customPaper, 'landscape')
    }
}
