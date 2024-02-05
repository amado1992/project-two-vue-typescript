<?php

namespace Modules\Travels\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Common\Http\Controllers\Controller;
use Modules\Contracts\Entities\Contract;
use Modules\Travels\Application\CreateTravelUseCase;
use Modules\Travels\Entities\Travel;
use Modules\Travels\Http\Requests\StoreTravelRequest;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Illuminate\Http\RedirectResponse;
use Modules\Common\Helpers\NotificationType;

class TravelsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Contract $contract
     * @return Response
     */
    #[Get('travels/{contract}/travels', 'contracts.travels')]
    public function index(Contract $contract): Response
    {
        return Inertia::render('Contracts/ContractTravels', [
            'contract' => $contract->load(['returns', 'products', 'travels', 'travels.products'])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreTravelRequest $request
     * @param CreateTravelUseCase $createTravelUseCase
     * @param Contract $contract
     * @return RedirectResponse
     */
    #[Post('travels/{contract}/travel', 'contracts.travel.store')]
    public function store(StoreTravelRequest $request,
        CreateTravelUseCase $createTravelUseCase,
        Contract $contract
    ): RedirectResponse
    {
        $createTravelUseCase($request->validated(), $contract);
        notification(NotificationType::Success, __('Successfully added.'));

        return back();
    }
}
