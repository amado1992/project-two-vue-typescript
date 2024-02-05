<?php

namespace Modules\Contracts\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Common\Helpers\NotificationType;
use Modules\Common\Http\Controllers\Controller;
use Modules\Contracts\Application\CreateRenovationUseCase;
use Modules\Contracts\Entities\Contract;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;

/**
 * @author Abel David.
 */
class RenovationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Contract $contract
     * @return Response
     * @throws AuthorizationException
     */
    #[Get('contracts/{contract}/renovations', 'contracts.renovations')]
    public function index(Contract $contract): Response
    {
        $this->authorize('renovations', $contract);

        return Inertia::render('Contracts/Renovations', [
            'contract' => $contract->load(['renovations', 'products'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('contracts::create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateRenovationUseCase $createRenovationUseCase
     * @param Contract $contract
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    #[Post('contracts/{contract}/renovations', 'contracts.renovations.store')]
    public function store(CreateRenovationUseCase $createRenovationUseCase, Contract $contract): RedirectResponse
    {
        $this->authorize('addRenovations', $contract);

        if ($createRenovationUseCase($contract)) {

            notification(NotificationType::Success, __('Successfully created.'));
        }

        return back();
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('contracts::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('contracts::edit');
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
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
