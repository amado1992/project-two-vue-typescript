<?php

namespace Modules\Common\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Bonos\Application\GetBonosCountUseCase;
use Modules\Client\Application\ReadClientsUseCase;
use Modules\Common\Permissions\DashboardPermissions;
use Modules\Contracts\Application\GetContractsCountUseCase;
use Modules\Designs\Application\GetDesignsCountUseCase;
use Modules\Inventories\Application\GetMovementsCountUseCase;
use Modules\Payments\Application\GetPaymentsCountUseCase;
use Modules\Products\Application\ReadProductsUseCase;
use Modules\Projects\Application\GetProjectsCountUseCase;
use Modules\Providers\Application\ReadProvidersUseCase;
use Modules\Quotes\Application\GetQuoteCountUseCase;
use Modules\ReRents\Application\GetReRentsCountUseCase;
use Modules\Users\Application\ReadUsersUseCase;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ReadUsersUseCase $readUsersUseCase
     * @param ReadClientsUseCase $readClientsUseCase
     * @param ReadProvidersUseCase $readProvidersUseCase
     * @param ReadProductsUseCase $readProductsUseCase
     * @param GetQuoteCountUseCase $getQuoteCountUseCase
     * @param GetContractsCountUseCase $getContractsCountUseCase
     * @param GetProjectsCountUseCase $getProjectsCountUseCase
     * @param GetReRentsCountUseCase $getReRentsCountUseCase
     * @param GetDesignsCountUseCase $getDesignsCountUseCase
     * @param GetMovementsCountUseCase $getMovementsCountUseCase
     * @param GetBonosCountUseCase $getBonosCountUseCase
     * @param GetPaymentsCountUseCase $getPaymentsCountUseCase
     * @return Response
     * @throws AuthorizationException
     */
    public function index(
        ReadUsersUseCase $readUsersUseCase,
        ReadClientsUseCase $readClientsUseCase,
        ReadProvidersUseCase $readProvidersUseCase,
        ReadProductsUseCase $readProductsUseCase,
        GetQuoteCountUseCase $getQuoteCountUseCase,
        GetContractsCountUseCase $getContractsCountUseCase,
        GetProjectsCountUseCase $getProjectsCountUseCase,
        GetReRentsCountUseCase $getReRentsCountUseCase,
        GetDesignsCountUseCase $getDesignsCountUseCase,
        GetMovementsCountUseCase $getMovementsCountUseCase,
        GetBonosCountUseCase $getBonosCountUseCase,
        GetPaymentsCountUseCase $getPaymentsCountUseCase
    ): Response
    {
        $this->authorize(DashboardPermissions::READ);

        return Inertia::render('Dashboard', [
            'users' => $readUsersUseCase()->count(),
            'clients' => $readClientsUseCase()->count(),
            'providers' => $readProvidersUseCase()->count(),
            'products' => $readProductsUseCase(null)->count(),
            'quotes' => $getQuoteCountUseCase(),
            'contracts' => $getContractsCountUseCase(),
            'projects' => $getProjectsCountUseCase(),
            're_rents' => $getReRentsCountUseCase(),
            'designs' => $getDesignsCountUseCase(),
            'movements' => $getMovementsCountUseCase(),
            'bonos' => $getBonosCountUseCase(),
            'payments' => $getPaymentsCountUseCase()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('common::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('common::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('common::edit');
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
