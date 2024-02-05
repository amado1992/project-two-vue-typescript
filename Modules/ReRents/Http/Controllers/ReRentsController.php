<?php

namespace Modules\ReRents\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Common\Helpers\NotificationType;
use Modules\Common\Http\Controllers\Controller;
use Modules\Contracts\Application\StartContractUseCase;
use Modules\Contracts\Entities\Contract;
use Modules\Contracts\Http\Requests\StartContractRequest;
use Modules\Products\Application\ReadProductsUseCase;
use Modules\Providers\Application\ReadProvidersUseCase;
use Modules\ReRents\Application\CancelReRentUseCase;
use Modules\ReRents\Application\CreateReRentUseCase;
use Modules\ReRents\Application\DeleteReRentUseCase;
use Modules\ReRents\Application\FinishReRentUseCase;
use Modules\ReRents\Application\PaginateReRentsUseCase;
use Modules\ReRents\Application\StartRerentUseCase;
use Modules\ReRents\Application\UpdateReRentUseCase;
use Modules\ReRents\Entities\ReRent;
use Modules\ReRents\Http\Requests\StoreReRentRequest;
use Modules\ReRents\Http\Requests\UpdateReRentRequest;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Put;
use Spatie\RouteAttributes\Attributes\Resource;
use Throwable;

/**
 * @author Abel David.
 */
#[Resource('re-rents')]
class ReRentsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ReRent::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param PaginateReRentsUseCase $paginateReRentsUseCase
     * @return Response
     */
    public function index(PaginateReRentsUseCase $paginateReRentsUseCase): Response
    {
        return Inertia::render('ReRents/Index', [
            'reRents' => $paginateReRentsUseCase()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ReadProvidersUseCase $readProvidersUseCase
     * @param ReadProductsUseCase $readProductsUseCase
     * @return Response
     */
    public function create(
        ReadProvidersUseCase $readProvidersUseCase,
        ReadProductsUseCase $readProductsUseCase
    ): Response
    {
        return Inertia::render('ReRents/Create', [
            'providers' => $readProvidersUseCase(),
            'products' => $readProductsUseCase(null)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreReRentRequest $request
     * @param CreateReRentUseCase $createReRentUseCase
     * @return RedirectResponse
     */
    public function store(StoreReRentRequest $request, CreateReRentUseCase $createReRentUseCase): RedirectResponse
    {
        $createReRentUseCase($request->validated());

        notification(NotificationType::Success, __('Successfully created.'));

        return redirect()->action([self::class, 'index']);
    }

    /**
     * Show the specified resource.
     *
     * @param ReRent $reRent
     * @return Response
     */
    public function show(ReRent $reRent): Response
    {
        return Inertia::render('ReRents/Show', [
            'reRent' => $reRent->load('products')
        ]);
    }

    /**
     * @param FinishReRentUseCase $finishReRentUseCase
     * @param ReRent $reRent
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    #[Get('re-rents/{re_rent}/finish', 're-rents.finish')]
    public function finish(FinishReRentUseCase $finishReRentUseCase, ReRent $reRent): RedirectResponse
    {
        $this->authorize('finish', $reRent);

        try {

            $finishReRentUseCase($reRent);
            notification(NotificationType::Success, __('Successfully finished.'));

        } catch (Throwable $e) {

            notification(NotificationType::Error, $e->getMessage());
        }

        return back();
    }

    /**
     * @param Request $request
     * @param CancelReRentUseCase $cancelReRentUseCase
     * @param ReRent $reRent
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws ValidationException
     */
    #[Put('re-rents/{re_rent}/cancel', 're-rents.cancel')]
    public function cancel(
        Request $request,
        CancelReRentUseCase $cancelReRentUseCase,
        ReRent $reRent
    ): RedirectResponse
    {
        $this->authorize('cancel', $reRent);

        $this->validate($request, [
            'reason' => ['nullable', 'string']
        ]);

        try {

            $cancelReRentUseCase($reRent, $request->input('reason'));

            notification(NotificationType::Success, __('Successfully cancelled.'));
        } catch (Throwable $e) {

            notification(NotificationType::Error, $e->getMessage());
        }

        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ReadProvidersUseCase $readProvidersUseCase
     * @param ReadProductsUseCase $readProductsUseCase
     * @param ReRent $reRent
     * @return Response
     */
    public function edit(
        ReadProvidersUseCase $readProvidersUseCase,
        ReadProductsUseCase $readProductsUseCase,
        ReRent $reRent
    ): Response
    {
        return Inertia::render('ReRents/Edit', [
            'reRent' => $reRent->load('products'),
            'providers' => $readProvidersUseCase(),
            'products' => $readProductsUseCase(null)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateReRentRequest $request
     * @param UpdateReRentUseCase $updateReRentUseCase
     * @param ReRent $reRent
     * @return RedirectResponse
     */
    public function update(
        UpdateReRentRequest $request,
        UpdateReRentUseCase $updateReRentUseCase,
        ReRent $reRent
    ): RedirectResponse
    {
        if ($updateReRentUseCase($request->validated(), $reRent)) {

            notification(NotificationType::Success, __('Successfully updated.'));
        }

        return redirect()->action([self::class, 'index']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param DeleteReRentUseCase $deleteReRentUseCase
     * @param ReRent $reRent
     * @return RedirectResponse
     */
    public function destroy(
        Request $request,
        DeleteReRentUseCase $deleteReRentUseCase,
        ReRent $reRent
    ): RedirectResponse
    {
        if ($deleteReRentUseCase($reRent)) {

            notification(NotificationType::Success, __('Successfully removed.'));

            if ($request->has('redirect_to')) {

                return redirect($request->input('redirect_to'));
            }
        }

        return back();
    }

    #[Put('re-rents/{re_rent}/start', 're-rents.start')]
    public function start(
        StartRerentUseCase $startRerentUseCase,
        ReRent $reRent){

        try {
            $startRerentUseCase($reRent);
            notification(NotificationType::Success, __('Successfully started.'));
        } catch (Throwable $e) {

            notification(NotificationType::Error, $e->getMessage());
        }

    }
}
