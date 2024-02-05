<?php

namespace Modules\Common\Application;

use Abdavid92\LaravelQuasarPaginator\DataTablePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Modules\Bonos\Entities\Bono;
use Modules\Client\Entities\Client;
use Modules\Contracts\Entities\Contract;
use Modules\Contracts\Entities\Invoice;
use Modules\Payments\Entities\Payment;
use Modules\Quotes\Entities\Quote;

/**
 * @author Abel David.
 */
class GetClientDataUseCase
{

    public function __invoke(?Client $client): array
    {
        if (!$client) {

            return [];
        }

        //$bonos = $this->getBonos($client);

        $bono_total = $this->getBonosSum($client);

        /*$bonos->each(function (Bono $bono) use (&$bono_total) {
            $bono_total += $bono->credit;
        });*/

        //$payments = $this->getPayments($client);

        $payment_total = $this->getPaymentsSum($client);

       /* $payments->each(function (Payment $payment) use (&$payment_total) {
           $payment_total += $payment->credit;
        });*/

        $invoice_total = $this->getInvoicesTotal($client);

        return [
            'invoice_total' => $invoice_total,
            'bono_total' => $bono_total,
            'payment_total' => $payment_total,
            'balance' => $client->credit - ($invoice_total - $payment_total),
            'active_contracts' => $this->getContracts($client, true),
            'contracts' => $this->getContracts($client, false),
            'pending_quotes' => $this->getQuotes($client, true),
            'quotes' => $this->getQuotes($client, false),
            'pending_invoices' => $this->getInvoices($client, true),
            'invoices' => $this->getInvoices($client,false)
        ];
    }

    /**
     * @param Client $client
     * @param bool $onlyPending
     * @return DataTablePaginator
     */
    private function getQuotes(Client $client, bool $onlyPending): DataTablePaginator
    {
        $builder = Quote::query()
            ->join('projects', 'projects.id', 'quotes.project_id')
            ->where('projects.client_id', $client->id);

        if ($onlyPending) {

            $builder->whereNull('quotes.contract_id')
                ->where('quotes.approved', false);
        }

        return new DataTablePaginator(
            $builder,
            null,
            false,
            15,
            $onlyPending ? 'pending_quotes' : 'quotes'
        );
    }

    /**
     * @param Client $client
     * @param bool $onlyActives
     * @return DataTablePaginator
     */
    private function getContracts(Client $client, bool $onlyActives)
    {
        $builder = Contract::query()
            ->join('projects', 'projects.id', 'contracts.project_id')
            ->join('users','contracts.user_id','users.id')
            ->where('projects.client_id', $client->id)
            ->select(['projects.name as project_name',
            'users.name as commercial_name',
            'contracts.*',

        ]);


        return (new DataTablePaginator(
            $builder,
            null,
            false,
            15,
            $onlyActives ? 'active_contracts' : 'contracts'
        ))->filter(function (array $contract) use ($onlyActives) {

                if ($onlyActives) {

                    return str_contains($contract['status'], Contract::ACTIVE_STATUS);
                }

                return true;
            });
    }

    /**
     * @param Client $client
     * @return Collection
     */
    private function getPayments(Client $client): Collection
    {
        return Payment::query()
            ->where('client_id', $client->id)
            ->get();
    }
    private function getPaymentsSum(Client $client)
    {
        return Payment::query()
            ->where('client_id', $client->id)
            ->join('invoice_payment','invoice_payment.payment_id','=','payments.id')->sum('invoice_payment.credit');
    }

    /**
     * @param Client $client
     * @return Collection
     */
    private function getBonos(Client $client): Collection
    {
        return Bono::query()
            ->where('client_id', $client->id)
            ->get();
    }

    private function getBonosSum(Client $client)
    {
        return Bono::query()
            ->where('client_id', $client->id)
            ->sum('credit');
    }

    /**
     * @param Client $client
     * @param bool $onlyPending
     * @return DataTablePaginator
     */
    private function getInvoices(Client $client, bool $onlyPending): DataTablePaginator
    {
        return (new DataTablePaginator($this->getInvoicesBuilder($client)))
            ->filter(function (array $invoice) use ($onlyPending) {

                if ($onlyPending) {

                    return !$invoice['was_paid'];
                }

                return true;
            });
    }

    /**
     * @param Client $client
     * @return float
     */
    private function getInvoicesTotal(Client $client): float
    {
        $invoice_total = 0;

        $invoice_total = $this->getInvoicesBuilder($client)
        ->join('invoice_product','invoice_product.invoice_id','=','invoices.id')->sum('invoice_product.total');/*->get()
            ->each(function (Invoice $invoice) use (&$invoice_total) {
                $invoice_total += $invoice->total;
            });*/

        return $invoice_total;
    }

    /**
     * @param Client $client
     * @return Builder
     */
    private function getInvoicesBuilder(Client $client): Builder
    {
        return Invoice::query()
            ->join('contracts', 'contracts.id', 'invoices.contract_id')
            ->join('projects', 'projects.id', 'contracts.project_id')
            ->where('projects.client_id', $client->id);
    }
}
