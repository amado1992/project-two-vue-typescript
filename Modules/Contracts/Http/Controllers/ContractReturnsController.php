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
use Modules\Contracts\Application\CreateContractReturnUseCase;
use Modules\Contracts\Entities\Contract;
use Modules\Contracts\Http\Requests\StoreContractReturnRequest;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;

/**
 * @author Abel David.
 */
class ContractReturnsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Contract $contract
     * @return Response
     * @throws AuthorizationException
     */
    #[Get('contracts/{contract}/returns', 'contracts.returns')]
    public function index(Contract $contract): Response
    {
        $this->authorize('returns', $contract);

        return Inertia::render('Contracts/ContractReturns', [
            'contract' => $contract->load(['returns', 'products'])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreContractReturnRequest $request
     * @param CreateContractReturnUseCase $createContractReturnUseCase
     * @param Contract $contract
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    #[Post('contracts/{contract}/returns', 'contracts.returns.store')]
    public function store(
        StoreContractReturnRequest $request,
        CreateContractReturnUseCase $createContractReturnUseCase,
        Contract $contract
    ): RedirectResponse
    {
        $this->authorize('addReturns', $contract);

        $createContractReturnUseCase($request->validated(), $contract);
        notification(NotificationType::Success, __('Successfully added.'));

        return back();
    }
}
