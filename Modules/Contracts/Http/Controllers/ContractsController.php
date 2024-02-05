<?php

namespace Modules\Contracts\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use InvalidArgumentException;
use Modules\Client\Application\ReadClientsUseCase;
use Modules\Common\Helpers\NotificationType;
use Modules\Common\Http\Controllers\Controller;
use Modules\Contracts\Application\CancelContractUseCase;
use Modules\Contracts\Application\CreateContractUseCase;
use Modules\Contracts\Application\DeleteContractUseCase;
use Modules\Contracts\Application\FinishContractUseCase;
use Modules\Contracts\Application\GetContractPdfUseCase;
use Modules\Contracts\Application\PaginateContractsUseCase;
use Modules\Contracts\Application\PaginateQuotesUseCase;
use Modules\Contracts\Application\ReadProjectsUseCase;
use Modules\Contracts\Application\StartContractUseCase;
use Modules\Contracts\Application\UpdateContractUseCase;
use Modules\Contracts\Application\UpdateDateContractUseCase;
use Modules\Contracts\Entities\Contract;
use Modules\Contracts\Http\Requests\StartContractRequest;
use Modules\Contracts\Http\Requests\StoreContractRequest;
use Modules\Contracts\Http\Requests\UpdateContractRequest;
use Modules\Products\Application\ReadProductsUseCase;
use Modules\Users\Application\ReadUsersUseCase;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Put;
use Spatie\RouteAttributes\Attributes\Resource;
use Throwable;

/**
 * @author Abel David.
 */
#[Resource('contracts')]
class ContractsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Contract::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param PaginateContractsUseCase $paginateContractsUseCase
     * @return Response
     */



    public function index(PaginateContractsUseCase $paginateContractsUseCase,Request $request): Response
    {

        return Inertia::render('Contracts/Index', [
            'contracts' => $paginateContractsUseCase(request:$request),
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @param ReadUsersUseCase $readUsersUseCase
     * @param ReadClientsUseCase $readClientsUseCase
     * @param ReadProductsUseCase $readProductsUseCase
     * @param ReadProjectsUseCase $readProjectsUseCase
     * @param PaginateQuotesUseCase $paginateQuotesUseCase
     * @return Response
     */
    public function create(
        Request $request,
        ReadUsersUseCase $readUsersUseCase,
        ReadClientsUseCase $readClientsUseCase,
        ReadProductsUseCase $readProductsUseCase,
        ReadProjectsUseCase $readProjectsUseCase,
        PaginateQuotesUseCase $paginateQuotesUseCase
    ): Response
    {
        return Inertia::render('Contracts/Create', [
            'users' => $readUsersUseCase(),
            'clients' => $readClientsUseCase(),
            'products' => $readProductsUseCase(null),
            'projects' => $readProjectsUseCase($request->input('client_id')),
            'quotes' => $paginateQuotesUseCase()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreContractRequest $request
     * @param CreateContractUseCase $createContractUseCase
     * @return RedirectResponse
     */
    public function store(
        StoreContractRequest $request,
        CreateContractUseCase $createContractUseCase
    ): RedirectResponse
    {
        if ($createContractUseCase($request->validated())) {

            notification(NotificationType::Success, __('Successfully created.'));
        }

        return redirect()->action([self::class, 'index']);
    }

    /**
     * Show the specified resource.
     *
     * @param Contract $contract
     * @return Response
     */
    public function show(Contract $contract): Response
    {
        return Inertia::render('Contracts/Show', [
            'contract' => $contract->load('products')
        ]);
    }

    /**
     * Start a contract.
     *
     * @param StartContractRequest $request
     * @param StartContractUseCase $startContractUseCase
     * @param Contract $contract
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    #[Post('contracts/{contract}/start', 'contracts.start')]
    public function start(
        StartContractRequest $request,
        StartContractUseCase $startContractUseCase,
        Contract $contract
    ): RedirectResponse
    {
        $this->authorize('start', $contract);

        try {

            $startContractUseCase($request->input('products'), $contract);
            notification(NotificationType::Success, __('Successfully started.'));

        } catch (InvalidArgumentException $e) {

            notification(NotificationType::Error, $e->getMessage());
        }

        return back();
    }

    /**
     * @param FinishContractUseCase $finishContractUseCase
     * @param Contract $contract
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    #[Get('contracts/{contract}/finish', 'contracts.finish')]
    public function finish(FinishContractUseCase $finishContractUseCase, Contract $contract): RedirectResponse
    {
        $this->authorize('finish', $contract);

        try {

            $finishContractUseCase($contract);

            notification(NotificationType::Success, __('Successfully finished.'));
        } catch (Throwable $e) {

            notification(NotificationType::Error, $e->getMessage());
        }

        return back();
    }

    /**
     * @param Request $request
     * @param CancelContractUseCase $cancelContractUseCase
     * @param Contract $contract
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws ValidationException
     */
    #[Put('contracts/{contract}/cancel', 'contracts.cancel')]
    public function cancel(
        Request $request,
        CancelContractUseCase $cancelContractUseCase,
        Contract $contract
    ): RedirectResponse
    {
        $this->authorize('cancel', $contract);

        $this->validate($request, [
            'reason' => ['nullable', 'string']
        ]);

        try {

            $cancelContractUseCase($contract, $request->input('reason'));

            notification(NotificationType::Success, __('Successfully cancelled.'));
        } catch (Throwable $e) {

            notification(NotificationType::Error, $e->getMessage());
        }

        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param ReadUsersUseCase $readUsersUseCase
     * @param ReadClientsUseCase $readClientsUseCase
     * @param ReadProductsUseCase $readProductsUseCase
     * @param ReadProjectsUseCase $readProjectsUseCase
     * @param PaginateQuotesUseCase $paginateQuotesUseCase
     * @param Contract $contract
     * @return Response
     */
    public function edit(
        Request $request,
        ReadUsersUseCase $readUsersUseCase,
        ReadClientsUseCase $readClientsUseCase,
        ReadProductsUseCase $readProductsUseCase,
        ReadProjectsUseCase $readProjectsUseCase,
        PaginateQuotesUseCase $paginateQuotesUseCase,
        Contract $contract
    ): Response
    {
        return Inertia::render('Contracts/Edit', [
            'contract' => $contract->load('products'),
            'users' => $readUsersUseCase(),
            'clients' => $readClientsUseCase(),
            'products' => $readProductsUseCase(null),
            'projects' => $readProjectsUseCase($request->input('client_id', $contract->quote?->client?->id)),
            'quotes' => $paginateQuotesUseCase()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateContractRequest $request
     * @param UpdateContractUseCase $updateContractUseCase
     * @param Contract $contract
     * @return RedirectResponse
     */
    public function update(
        UpdateContractRequest $request,
        UpdateContractUseCase $updateContractUseCase,
        Contract $contract
    ): RedirectResponse
    {
        if ($updateContractUseCase($request->validated(), $contract)) {

            notification(NotificationType::Success, __('Successfully updated.'));
        } else {

            notification(NotificationType::Warning, __('No have changes to save.'));
        }

        return redirect()->action([self::class, 'index']);
    }

    /**
     * @param Request $request
     * @param UpdateDateContractUseCase $updateDateContractUseCase
     * @param Contract $contract
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws ValidationException
     */
    #[Put('contracts/{contract}/date', 'contracts.update-date')]
    public function updateDate(
        Request $request,
        UpdateDateContractUseCase $updateDateContractUseCase,
        Contract $contract
    ): RedirectResponse
    {
        $this->authorize('updateDate', $contract);

        $this->validate($request, [
            'date' => ['required', 'date_format:Y-m-d']
        ]);

        if ($updateDateContractUseCase($request->input('date'), $contract)) {

            notification(NotificationType::Success, __('Successfully updated.'));
        } else {

            notification(NotificationType::Warning, __('No have changes to save.'));
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param DeleteContractUseCase $deleteContractUseCase
     * @param Contract $contract
     * @return RedirectResponse
     */
    public function destroy(
        Request $request,
        DeleteContractUseCase $deleteContractUseCase,
        Contract $contract
    ): RedirectResponse
    {
        if ($deleteContractUseCase($contract)) {

            notification(NotificationType::Success, __('Successfully removed.'));

            if ($request->has('redirect_to')) {

                return redirect($request->input('redirect_to'));
            }
        } else {

            notification(NotificationType::Error, __('Can not be deleted the contract.'));
        }

        return back();
    }

    /**
     * @param GetContractPdfUseCase $getContractPdfUseCase
     * @param Contract $contract
     * @return \Illuminate\Http\Response
     */
    #[Get('contracts/{contract}/download', 'contracts.download')]
    public function download(GetContractPdfUseCase $getContractPdfUseCase, Contract $contract): \Illuminate\Http\Response
    {
        return $getContractPdfUseCase($contract)
            ->download('Contrato_'.$contract->id.'.pdf');
    }

    #[Get('contracts/{contract}/products', 'contracts.products')]
    public function contracts_products(Contract $contract)
    {
        return response()->json([
            'contract' => $contract->load('products')]);

    }
}
