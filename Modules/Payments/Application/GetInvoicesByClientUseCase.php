<?php

namespace Modules\Payments\Application;

use Illuminate\Support\Collection;
use Modules\Client\Entities\Client;
use Modules\Contracts\Entities\Invoice;

/**
 * @author Abel David.
 */
class GetInvoicesByClientUseCase
{
    /**
     * @param Client $client
     * @return Collection<Invoice>
     */
    public function __invoke(Client $client): Collection
    {
        return Invoice::query()
            ->join('contracts', 'contracts.id', 'invoices.contract_id')
            ->join('projects', 'projects.id', 'contracts.project_id')
            ->where('projects.client_id', $client->id)
            ->select('invoices.*')
            ->get()
            ->filter(fn (Invoice $invoice) => ! $invoice->was_paid);
    }
}
