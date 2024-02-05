<?php

namespace Modules\Payments\Application;

use Modules\Payments\Entities\Payment;
use Modules\Payments\Events\DeletingPayment;
use Modules\Payments\Events\PaymentDeleted;

/**
 * @author Abel David.
 */
class DeletePaymentUseCase
{
    /**
     * @param Payment $payment
     * @return bool
     */
    public function __invoke(Payment $payment): bool
    {
        DeletingPayment::dispatch($payment);

        $client = $payment->client;

        $client->fill([
            'credit' => $client->credit + $payment->credit
        ])->save();

        $deleted = $payment->delete() === true;

        if ($deleted) {
            PaymentDeleted::dispatch($payment);
        }

        return $deleted;
    }
}
