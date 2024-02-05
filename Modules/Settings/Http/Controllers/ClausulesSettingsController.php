<?php

namespace Modules\Settings\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Common\Helpers\NotificationType;
use Modules\Common\Http\Controllers\Controller;
use Modules\Settings\Applications\StoreClausulesSettingsUseCase;
use Modules\Settings\Entities\ClausulesSettings;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;

/**
 * @author Abel David.
 */
class ClausulesSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ClausulesSettings $settings
     * @return Response
     */
    #[Get('settings/clausules', 'settings.clausules.index')]
    public function index(ClausulesSettings $settings): Response
    {
        return Inertia::render('Settings/Clausules', [
            'clausules' => $settings->clausules,
            'settings' => $settings
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param StoreClausulesSettingsUseCase $storeClausulesSettingsUseCase
     * @return RedirectResponse
     * @throws ValidationException
     */
    #[Post('settings/clausules', 'settings.clausules.store')]
    public function store(
        Request $request,
        StoreClausulesSettingsUseCase $storeClausulesSettingsUseCase
    ): RedirectResponse
    {
        $this->validate($request, [
            'clausules' => ['nullable', 'string']
        ]);

        $storeClausulesSettingsUseCase($request);

        notification(NotificationType::Success, __('Successfully saved.'));

        return back();
    }
}
