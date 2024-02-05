<?php

namespace Modules\Contracts\Http\Controllers;

use Barryvdh\DomPDF\PDF;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Common\Helpers\NotificationType;
use Modules\Common\Http\Controllers\Controller;
use Modules\Companies\Application\ReadCompanyUseCase;
use Modules\Contracts\Application\CreateInvoiceUseCase;
use Modules\Contracts\Application\DeleteInvoiceUseCase;
use Modules\Contracts\Application\GetInvoicePdfUseCase;
use Modules\Contracts\Application\PaginateInvoicesUseCase;
use Modules\Contracts\Entities\Contract;
use Modules\Contracts\Entities\Invoice;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Resource;

/**
 * @author Abel David.
 */
class InvoicesController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Invoice::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param PaginateInvoicesUseCase $useCase
     * @param Contract $contract
     * @return Response
     */
    #[Get('invoices/{contract}', 'invoices.index')]
    public function index(PaginateInvoicesUseCase $useCase, Contract $contract): Response
    {
        return Inertia::render('Invoices/Index', [
            'contract_id' => $contract->id,
            'invoices' => $useCase($contract)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param CreateInvoiceUseCase $useCase
     * @return RedirectResponse
     * @throws ValidationException
     */
    #[Post('invoices', 'invoices.store')]
    public function store(Request $request, CreateInvoiceUseCase $useCase): RedirectResponse
    {
        $this->validate($request, [
            'contract_id' => ['required', Rule::exists('contracts', 'id')]
        ]);

        if ($invoice = $useCase(Contract::query()->findOrFail($request->input('contract_id')))) {

            notification(NotificationType::Success, __('Successfully created.'));

            return redirect()->action([self::class, 'show'], ['invoice' => $invoice]);
        }

        notification(NotificationType::Error, __("Can't create the invoice."));

        return back();
    }

    /**
     * Show the specified resource.
     *
     * @param Invoice $invoice
     * @return Response
     */
    #[Get('invoices/{invoice}/details', 'invoices.show')]
    public function show(Invoice $invoice): Response
    {
        return Inertia::render('Invoices/Show', [
            'invoice' => $invoice->load(['products', 'contract']),
            'templates' => invoicesTemplates()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteInvoiceUseCase $useCase
     * @param Invoice $invoice
     * @return RedirectResponse
     */
    #[Delete('invoices/{invoice}', 'invoices.destroy')]
    public function destroy(DeleteInvoiceUseCase $useCase, Invoice $invoice): RedirectResponse
    {
        if ($useCase($invoice)) {

            notification(NotificationType::Success, __("Successfully removed."));
        }

        return back();
    }

    /**
     * @param GetInvoicePdfUseCase $getInvoicePdfUseCase
     * @param Invoice $invoice
     * @param string $template
     * @return \Illuminate\Http\Response
     */
    #[Get('invoice/{invoice}/download/{template}', 'invoices.download')]
    public function download(GetInvoicePdfUseCase $getInvoicePdfUseCase, Invoice $invoice, string $template): \Illuminate\Http\Response
    {
        return $getInvoicePdfUseCase($invoice, $template)
            ->download('Invoice_'.$invoice->id.'_'.$template.'.pdf');
    }
}
