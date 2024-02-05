<?php

namespace Modules\Payments\Application;

use Modules\Payments\Entities\Payment;

/**
 * @author Abel David.
 */
trait HasInvoices
{
    /**
     * @param array $invoices
     * @param Payment $payment
     * @return bool
     */
    protected function syncInvoices(array $invoices, Payment $payment): bool
    {
        $data = [];

        foreach ($invoices as $invoice) {

            $data[$invoice['id']] = [
                'credit' => $invoice['credit']
            ];
        }

        return hasSyncChanges($payment->invoices()->sync($data));
    }
}
