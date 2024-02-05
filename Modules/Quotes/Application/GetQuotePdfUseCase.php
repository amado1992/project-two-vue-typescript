<?php

namespace Modules\Quotes\Application;

use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Adapter\CPDF;
use Exception;
use Modules\Common\Services\ViteService;
use Modules\Quotes\Entities\Quote;

/**
 * @author Abel David.
 */
class GetQuotePdfUseCase
{
    /**
     * @param ViteService $viteService
     */
    public function __construct(
        private readonly ViteService $viteService
    )
    {
        //
    }

    /**
     * @param Quote $quote
     * @return \Barryvdh\DomPDF\PDF
     * @throws Exception
     */
    public function __invoke(Quote $quote): \Barryvdh\DomPDF\PDF
    {
        return Pdf::loadView('quotes::templates.quote_pdf', [
            'quote' => $quote,
            'css' => $this->viteService->assetData('Resources/assets/sass/app.scss', 'build-quotes')['file']
        ])->setPaper("legal");
    }
}
