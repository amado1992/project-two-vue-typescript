<?php

namespace Modules\Traces\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Bonos\Entities\Bono;
use Modules\Brands\Entities\Brand;
use Modules\Client\Entities\Client;
use Modules\Common\Helpers\NotificationType;
use Modules\Common\Http\Controllers\Controller;
use Modules\Common\Permissions\TracePermissions;
use Modules\Contracts\Entities\Contract;
use Modules\Inventories\Entities\Movement;
use Modules\Inventories\Entities\Reason;
use Modules\Payments\Entities\Payment;
use Modules\ProductCategories\Entities\ProductCategory;
use Modules\Products\Entities\Product;
use Modules\Projects\Entities\Project;
use Modules\Providers\Entities\Provider;
use Modules\Quotes\Entities\Quote;
use Modules\ReRents\Entities\ReRent;
use Modules\Roles\Entities\Role;
use Modules\Traces\Application\ClearTracesUseCase;
use Modules\Traces\Application\GetModelUrlUseCase;
use Modules\Traces\Application\PaginateTracesUseCase;
use Modules\Traces\Entities\Trace;
use Modules\Designs\Entities\Design;
use Modules\Users\Entities\User;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;

/**
 * @author Abel David.
 */
class TracesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param PaginateTracesUseCase $paginateTracesUseCase
     * @param string|null $module
     * @return Response
     * @throws ValidationException
     * @throws AuthorizationException
     */
    #[Get('traces/{module?}', 'traces.index')]
    public function index(
        PaginateTracesUseCase $paginateTracesUseCase,
        ?string $module = 'contracts'
    ): Response
    {
        $this->authorize(TracePermissions::READ);
        $this->validateModule($module);

        return Inertia::render('Traces/Index', [
            'traces' => $paginateTracesUseCase(Trace::MODELS_MAP[$module]),
            'selectedModule' => $module
        ]);
    }

    /**
     * Show the specified resource.
     *
     * @param Request $request
     * @param Trace $trace
     * @return Response
     * @throws AuthorizationException
     */
    #[Get('traces/{trace}/details', 'traces.show')]
    public function show(Request $request, Trace $trace): Response
    {
        $this->authorize(TracePermissions::READ);

        return Inertia::render('Traces/Show', [
            'trace' => $trace,
            'module' => $request->input('module')
        ]);
    }

    /**
     * @param GetModelUrlUseCase $useCase
     * @param Trace $trace
     * @return RedirectResponse
     */
    #[Get('traces/{trace}/model', 'traces.model')]
    public function showModel(GetModelUrlUseCase $useCase, Trace $trace): RedirectResponse
    {
        if ($url = $useCase($trace)) {

            return redirect($url);
        }

        notification(NotificationType::Error, __('traces::messages.resource_not_found'));

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ClearTracesUseCase $clearTracesUseCase
     * @param string $module
     * @return RedirectResponse
     * @throws ValidationException
     * @throws AuthorizationException
     */
    #[Delete('traces/{module}', 'traces.clear')]
    public function destroy(ClearTracesUseCase $clearTracesUseCase, string $module): RedirectResponse
    {
        $this->authorize(TracePermissions::CLEAR);
        $this->validateModule($module);

        $clearTracesUseCase(Trace::MODELS_MAP[$module]);

        notification(NotificationType::Success, __('Successfully cleared.'));

        return back();
    }

    /**
     * @param string $module
     * @return void
     * @throws ValidationException
     */
    private function validateModule(string $module): void
    {
        Validator::make([
            'module' => $module
        ], [
            'module' => ['required', Rule::in(array_keys(Trace::MODELS_MAP))]
        ])->validate();
    }
}
