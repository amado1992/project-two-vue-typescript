<?php

namespace Modules\Payments\Application;

use Modules\Payments\Entities\Payment;
use Modules\Payments\Events\PaymentUpdated;
use Modules\Payments\Events\UpdatingPayment;

/**
 * @author Abel David.
 */
class UpdatePaymentUseCase
{
    use HasInvoices;

    /**
     * @param array $data
     * @param Payment $payment
     * @return bool
     */
    public function __invoke(array $data, Payment $payment): bool
    {
        $oldPayment = clone $payment;

        $payment->fill($data);

        if ($payment->isDirty()) {
            UpdatingPayment::dispatch($payment);
        }

        $payment->save();

        $wasChanged = $payment->wasChanged();

        $wasChanged = $this->syncInvoices($data['invoices'], $payment) || $wasChanged;

        if ($wasChanged) {
            PaymentUpdated::dispatch($payment, $oldPayment);
        }

        return $wasChanged;
    }
}
