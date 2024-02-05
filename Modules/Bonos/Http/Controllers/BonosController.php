<?php

namespace Modules\Bonos\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Bonos\Application\CreateBonoUseCase;
use Modules\Bonos\Application\DeleteBonoUseCase;
use Modules\Bonos\Application\PaginateBonosUseCase;
use Modules\Bonos\Application\UpdateBonoUseCase;
use Modules\Bonos\Entities\Bono;
use Modules\Bonos\Http\Requests\StoreBonoRequest;
use Modules\Bonos\Http\Requests\UpdateBonoRequest;
use Modules\Client\Application\ReadClientsUseCase;
use Modules\Common\Helpers\NotificationType;
use Modules\Common\Http\Controllers\Controller;
use Spatie\RouteAttributes\Attributes\Resource;

/**
 * @author Abel David.
 */
#[Resource('bonos', except: ['show'])]
class BonosController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Bono::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param PaginateBonosUseCase $useCase
     * @return Response
     */
    public function index(PaginateBonosUseCase $useCase): Response
    {
        return Inertia::render('Bonos/Index', [
            'bonos' => $useCase()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ReadClientsUseCase $readClientsUseCase
     * @return Response
     */
    public function create(ReadClientsUseCase $readClientsUseCase): Response
    {
        return Inertia::render('Bonos/Create', [
            'clients' => $readClientsUseCase()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreBonoRequest $request
     * @param CreateBonoUseCase $useCase
     * @return RedirectResponse
     */
    public function store(StoreBonoRequest $request, CreateBonoUseCase $useCase): RedirectResponse
    {
        if ($useCase($request->validated())) {

            notification(NotificationType::Success, __('Successfully created.'));
        }

        return redirect()->action([self::class, 'index']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Bono $bono
     * @return Response
     */
    public function edit(Bono $bono): Response
    {
        return Inertia::render('Bonos/Edit', [
            'bono' => $bono
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateBonoRequest $request
     * @param UpdateBonoUseCase $useCase
     * @param Bono $bono
     * @return RedirectResponse
     */
    public function update(
        UpdateBonoRequest $request,
        UpdateBonoUseCase $useCase,
        Bono              $bono
    ): RedirectResponse
    {
        if ($useCase($request->validated(), $bono)) {

            notification(NotificationType::Success, __('Successfully updated.'));
        }

        return redirect()->action([self::class, 'index']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteBonoUseCase $useCase
     * @param Bono $bono
     * @return RedirectResponse
     */
    public function destroy(DeleteBonoUseCase $useCase, Bono $bono): RedirectResponse
    {
        if ($bono->credit > $bono->client->credit) {

            notification(NotificationType::Error, __('Can not deleted this bono because the client credit is insufficient.'));

        } else if ($useCase($bono)) {

            notification(NotificationType::Success, __('Successfully removed.'));
        }

        return back();
    }
}
