<?php

namespace Modules\Contracts\Application;

use Modules\Contracts\Entities\Contract;
use Modules\Contracts\Entities\Invoice;
use Modules\Contracts\Events\CreatingInvoice;
use Modules\Contracts\Events\InvoiceCreated;

/**
 * @author Abel David.
 */
class CreateInvoiceUseCase
{
    /**
     * @param Contract $contract
     * @return Invoice|null
     */
    public function __invoke(Contract $contract): ?Invoice
    {
        $status = $contract->status;

        if ($status == Contract::FINISHED_STATUS || $status == Contract::PENDING_STATUS) {

            return null;
        }

        CreatingInvoice::dispatch();

        $products = [];

        $contract->products->each(function ($product) use (&$products) {

            $pivot = $product->pivot;

            if ($pivot->mesu_delivered > $pivot->mesu_return ||
                $pivot->re_rent_delivered > $pivot->re_rent_return) {

                $quantity = $pivot->mesu_delivered + $pivot->re_rent_delivered;
                $subtotal = $quantity * $pivot->price;
                $discountValue = $subtotal * $pivot->discount / 100;
                $subtotal -= $discountValue;

                $products[$product->id] = [
                    'quantity' => $pivot->mesu_delivered + $pivot->re_rent_delivered,
                    'price' => $pivot->price,
                    'discount' => $pivot->discount,
                    'tax' => $pivot->tax,
                    'subtotal' => $subtotal,
                    'total' => $subtotal + $pivot->tax
                ];
            }
        });

        $invoice = Invoice::create([
            'contract_id' => $contract->id,
            'created_by' => auth()->user()->getAuthIdentifier()
        ]);

        $invoice->products()->sync($products);

        InvoiceCreated::dispatch($invoice);

        return $invoice;
    }
}
