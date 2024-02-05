<?php

namespace Modules\Contracts\Application;

use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Modules\Companies\Application\ReadCompanyUseCase;
use Modules\Contracts\Entities\Invoice;
use Modules\Settings\Entities\GeneralSettings;
use Modules\Settings\Entities\Settings;

/**
 * @author Abel David.
 */
class GetInvoicePdfUseCase
{
    /**
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * @param Invoice $invoice
     * @param string $template
     * @param ReadCompanyUseCase $readCompanyUseCase
     * @return Dompdf|\Barryvdh\DomPDF\PDF
     */
    public function __invoke(Invoice $invoice, string $template): Dompdf|\Barryvdh\DomPDF\PDF
    {
        $readCompanyUseCase = app(ReadCompanyUseCase::class);

        return Pdf::loadView('contracts::templates.invoices.'.$template.'.pdf', [
            'invoice' => $invoice,
            'template' => $template,
            'company' => $readCompanyUseCase()
        ])->setPaper('legal');
    }
}
