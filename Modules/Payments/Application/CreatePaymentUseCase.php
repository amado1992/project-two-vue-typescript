<?php

namespace Modules\Payments\Application;

use Illuminate\Validation\ValidationException;
use Modules\Client\Entities\Client;
use Modules\Payments\Entities\Payment;
use Modules\Payments\Events\CreatingPayment;
use Modules\Payments\Events\PaymentCreated;

/**
 * @author Abel David.
 */
class CreatePaymentUseCase
{
    use HasInvoices;

    /**
     * @param array $data
     * @return Payment
     */
    public function __invoke(array $data): Payment
    {
        CreatingPayment::dispatch();

        $data['created_by'] = auth()->user()->getAuthIdentifier();

        $payment = Payment::create($data);

        $this->syncInvoices($data['invoices'], $payment);

        $client = $payment->client;

        $client->fill([
            'credit' => $client->credit - $payment->credit
        ])->save();

        PaymentCreated::dispatch($payment);

        return $payment;
    }
}
