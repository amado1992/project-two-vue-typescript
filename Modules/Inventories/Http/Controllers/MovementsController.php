<?php

namespace Modules\Inventories\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Common\Helpers\NotificationType;
use Modules\Common\Http\Controllers\Controller;
use Modules\Inventories\Application\CreateMovementUseCase;
use Modules\Inventories\Application\DeleteMovementUseCase;
use Modules\Inventories\Application\PaginateMovementsUseCase;
use Modules\Inventories\Application\ReadReasonsUseCase;
use Modules\Inventories\Entities\Movement;
use Modules\Inventories\Http\Requests\StoreMovementRequest;
use Modules\Products\Application\ReadProductsUseCase;
use Spatie\RouteAttributes\Attributes\Resource;

/**
 * @author Abel David.
 */
#[Resource('movements', except: ['edit', 'update', 'destroy'])]
class MovementsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param PaginateMovementsUseCase $paginateMovementsUseCase
     * @return Response
     */
    public function index(PaginateMovementsUseCase $paginateMovementsUseCase): Response
    {
        return Inertia::render('Movements/Index', [
            'movements' => $paginateMovementsUseCase()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ReadProductsUseCase $readProductsUseCase
     * @param ReadReasonsUseCase $readReasonsUseCase
     * @return Response
     */
    public function create(
        ReadProductsUseCase $readProductsUseCase,
        ReadReasonsUseCase $readReasonsUseCase
    ): Response
    {
        return Inertia::render('Movements/Create', [
            'products' => $readProductsUseCase(null),
            'reasons' => $readReasonsUseCase('actives')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreMovementRequest $request
     * @param CreateMovementUseCase $createMovementUseCase
     * @return RedirectResponse
     */
    public function store(StoreMovementRequest $request, CreateMovementUseCase $createMovementUseCase): RedirectResponse
    {
        if ($createMovementUseCase($request->validated())) {

            notification(NotificationType::Success, __('Successfully created.'));
        }

        return redirect()->action([self::class, 'index']);
    }

    /**
     * Show the specified resource.
     *
     * @param Movement $movement
     * @return Response
     */
    public function show(Movement $movement): Response
    {
        return Inertia::render('Movements/Show', [
            'movement' => $movement->load('products')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('inventories::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Movement $movement
     * @param DeleteMovementUseCase $deleteMovementUseCase
     * @return RedirectResponse
     */
    public function destroy(Movement $movement, DeleteMovementUseCase $deleteMovementUseCase): RedirectResponse
    {
        if ($deleteMovementUseCase($movement)) {

            notification(NotificationType::Success, __('Successfully removed.'));
        }

        return back();
    }
}
