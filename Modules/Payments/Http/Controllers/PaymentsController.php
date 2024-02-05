<?php

namespace Modules\Payments\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Client\Application\ReadClientsUseCase;
use Modules\Client\Entities\Client;
use Modules\Common\Helpers\NotificationType;
use Modules\Common\Http\Controllers\Controller;
use Modules\Payments\Application\CreatePaymentUseCase;
use Modules\Payments\Application\DeletePaymentUseCase;
use Modules\Payments\Application\GetInvoicesByClientUseCase;
use Modules\Payments\Application\PaginatePaymentsUseCase;
use Modules\Payments\Application\UpdatePaymentUseCase;
use Modules\Payments\Entities\Payment;
use Modules\Payments\Http\Requests\StorePaymentRequest;
use Modules\Payments\Http\Requests\UpdatePaymentRequest;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Resource;

/**
 * @author Abel David.
 */
#[Resource('payments')]
class PaymentsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Payment::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param PaginatePaymentsUseCase $useCase
     * @return Response
     */
    public function index(PaginatePaymentsUseCase $useCase): Response
    {
        return Inertia::render('Payments/Index', [
            'payments' => $useCase()
        ]);
    }

    /**
     * @param GetInvoicesByClientUseCase $useCase
     * @param Client $client
     * @return JsonResponse
     * @throws AuthorizationException
     */
    #[Get('payments/{client}/invoices', 'payments.invoices')]
    public function invoices(GetInvoicesByClientUseCase $useCase, Client $client): JsonResponse
    {
        $this->authorize('view', $client);

        return response()->json($useCase($client));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ReadClientsUseCase $readClientsUseCase
     * @return Response
     */
    public function create(ReadClientsUseCase $readClientsUseCase): Response
    {
        return Inertia::render('Payments/Create', [
            'clients' => $readClientsUseCase()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePaymentRequest $request
     * @param CreatePaymentUseCase $useCase
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(StorePaymentRequest $request, CreatePaymentUseCase $useCase): RedirectResponse
    {
        $client = Client::find($request->input('client_id'));

        $this->ensureThatCreditIsValid($request->input('invoices'), $client->credit);

        if ($useCase($request->validated())) {

            notification(NotificationType::Success, __('Successfully created.'));
        }

        return redirect()->action([self::class, 'index']);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    /*public function show($id)
    {
        return view('payments::show');
    }*/

    public function show(Payment $payment): Response
    {
        return Inertia::render('Payments/Show', [
            'payment' => $payment->load(['client', 'invoices'])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ReadClientsUseCase $readClientsUseCase
     * @param Payment $payment
     * @return Response
     */
    public function edit(
        ReadClientsUseCase $readClientsUseCase,
        Payment $payment
    ): Response
    {
        return Inertia::render('Payments/Edit', [
            'payment' => $payment->load('invoices'),
            'clients' => $readClientsUseCase()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePaymentRequest $request
     * @param UpdatePaymentUseCase $updatePaymentUseCase
     * @param Payment $payment
     * @return RedirectResponse
     */
    public function update(
        UpdatePaymentRequest $request,
        UpdatePaymentUseCase $updatePaymentUseCase,
        Payment $payment
    ): RedirectResponse
    {
        if ($updatePaymentUseCase($request->validated(), $payment)) {

            notification(NotificationType::Success, __('Successfully updated.'));
        }

        return redirect()->action([self::class, 'index']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeletePaymentUseCase $useCase
     * @param Payment $payment
     * @return RedirectResponse
     */
    public function destroy(DeletePaymentUseCase $useCase, Payment $payment): RedirectResponse
    {
        if ($useCase($payment)) {

            notification(NotificationType::Success, __('Successfully removed.'));
        }

        return back();
    }

    /**
     * @param mixed $invoices
     * @param float $credit
     * @return void
     * @throws ValidationException
     */
    private function ensureThatCreditIsValid(mixed $invoices, float $credit): void
    {
        $invoicesCredit = 0;

        foreach ($invoices as $invoice) {

            $invoicesCredit += $invoice['credit'];
        }

        if ($invoicesCredit > $credit) {

            throw ValidationException::withMessages([
                'invoices' => __('The distributed credit is not valid.')
            ]);
        }
    }
}
