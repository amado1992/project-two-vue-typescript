<?php

namespace Modules\Contracts\Application;

use Modules\Contracts\Entities\Invoice;
use Modules\Contracts\Events\DeletingInvoice;
use Modules\Contracts\Events\InvoiceDeleted;

/**
 * @author Abel David.
 */
class DeleteInvoiceUseCase
{
    /**
     * @param Invoice $invoice
     * @return bool
     */
    public function __invoke(Invoice $invoice): bool
    {
        DeletingInvoice::dispatch($invoice);

        $deleted = $invoice->delete() === true;

        if ($deleted) {

            InvoiceDeleted::dispatch($invoice);
        }

        return $deleted;
    }
}
