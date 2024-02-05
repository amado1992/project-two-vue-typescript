<?php

namespace Modules\ReRents\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Common\Helpers\NotificationType;
use Modules\Common\Http\Controllers\Controller;
use Modules\ReRents\Application\CreateReRentReturnUseCase;
use Modules\ReRents\Entities\ReRent;
use Modules\ReRents\Http\Requests\StoreReRentReturnRequest;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;

/**
 * @author Abel David.
 */
class ReRentReturnsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ReRent $reRent
     * @return Response
     * @throws AuthorizationException
     */
    #[Get('re-rents/{re_rent}/returns', 're-rents.returns')]
    public function index(ReRent $reRent): Response
    {
        $this->authorize('returns', $reRent);

        return Inertia::render('ReRents/ReRentReturns', [
           'reRent' => $reRent->load('returns')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreReRentReturnRequest $request
     * @param CreateReRentReturnUseCase $createReRentReturnUseCase
     * @param ReRent $reRent
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    #[Post('re-rents/{re_rent}/returns', 're-rents.returns.store')]
    public function store(
        StoreReRentReturnRequest $request,
        CreateReRentReturnUseCase $createReRentReturnUseCase,
        ReRent $reRent
    ): RedirectResponse
    {
        $this->authorize('addReturns', $reRent);

        if ($createReRentReturnUseCase($request->validated(), $reRent)) {

            notification(NotificationType::Success, __('Successfully created.'));
        } else {

            notification(NotificationType::Error, __('No have changes to save.'));
        }

        return back();
    }
}
