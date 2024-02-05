<?php

namespace Modules\Common\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Common\Application\GetClientDataUseCase;
use Modules\Common\Http\Controllers\Controller;
use Spatie\RouteAttributes\Attributes\Get;

/**
 * @author Abel David.
 */
class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param GetClientDataUseCase $useCase
     * @return Response
     */
    #[Get('/', 'home')]
    public function index(Request $request, GetClientDataUseCase $useCase): Response
    {
        $user = $request->user();

        return Inertia::render('Welcome', [
            'user' => $user->load('client'),
            'data' => $useCase($user->client)
        ]);
    }
}
