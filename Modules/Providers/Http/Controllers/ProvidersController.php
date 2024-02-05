<?php

namespace Modules\Providers\Http\Controllers;

use App\Imports\ProveedoresImport;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Modules\Common\Helpers\NotificationType;
use Modules\Common\Http\Controllers\Controller;
use Modules\Providers\Application\CreateProviderUserCase;
use Modules\Providers\Application\DeleteProviderUseCase;
use Modules\Providers\Application\PaginateProvidersUseCase;
use Modules\Providers\Application\ReadProviderContact;
use Modules\Providers\Application\ReadProviderContactUseCase;
use Modules\Providers\Application\UpdateProviderUseCase;
use Modules\Providers\Entities\Provider;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Providers\Http\Requests\StoreProviderRequest;
use Modules\Providers\Http\Requests\ImportProviderRequest;
use Modules\Providers\Http\Requests\UpdateProviderRequest;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Resource;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProveedorImport;
use Modules\Providers\Http\Requests\ImportProviderRequest as RequestsImportProviderRequest;
use Spatie\RouteAttributes\Attributes\Post;

#[Resource('providers', except: ['show'])]
class ProvidersController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Provider::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param PaginateProvidersUseCase $paginateProvidersUseCase
     * @return Response
     */
    public function index(PaginateProvidersUseCase $paginateProvidersUseCase): Response
    {
        return Inertia::render('Providers/Index', [
            'data' => $paginateProvidersUseCase()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return Inertia::render('Providers/Create');
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProviderRequest $request
     * @param CreateProviderUserCase $createProviderUserCase
     * @return RedirectResponse
     */
    public function store(StoreProviderRequest $request, CreateProviderUserCase $createProviderUserCase): RedirectResponse
    {
        $createProviderUserCase($request);

        notification(NotificationType::Success, __('Successfully created.'));

        return redirect()->action([ProvidersController::class, 'index']);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('providers::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Provider $provider
     * @return Response
     */
    public function edit(Provider $provider): Response
    {

        return Inertia::render('Providers/Edit', [
            'provider' => $provider,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProviderUseCase $updateProviderUseCase
     * @param UpdateProviderRequest $request
     * @param Provider $provider
     * @return RedirectResponse
     */
    public function update(UpdateProviderUseCase $updateProviderUseCase, UpdateProviderRequest $request, Provider $provider): RedirectResponse
    {
        if ($updateProviderUseCase($request, $provider)) {

            notification(NotificationType::Success, __('Successfully updated.'));

            return redirect()->action([ProvidersController::class, 'index']);
        }

        notification(NotificationType::Info, __('No have changes to save.'));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteProviderUseCase $deleteProviderUserCase
     * @param Provider $provider
     * @return RedirectResponse
     */
    public function destroy(DeleteProviderUseCase $deleteProviderUserCase, Provider $provider): RedirectResponse
    {
        if ($deleteProviderUserCase($provider)) {

            notification(NotificationType::Success, __('Successfully removed.'));
        } else {

            notification(NotificationType::Error, __("Can not be deleted the selected provider."));
        }

        return redirect()->back();
    }

    #[Get('importprov', 'providers.showImportForm')]
    public function showImportForm(Provider $provider)
    {
        $this->authorize('import', $provider );

        return Inertia::render('Providers/Import');
    }

    #[Post('importprov', 'providers.import')]
    public function import(ImportProviderRequest $request)
    {

        try {
            Excel::import(new ProveedoresImport, $request->file('file'));
            notification(NotificationType::Success, __('Successfully imported.'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();

            $result ="";
            $result_array = [];
             foreach ($failures as $failure) {
              $fail = $failure->row();
            $attribute = $failure->attribute();
            $error = $failure->errors();
            $result .= "Fila: " . $fail ." --". "Error: " . implode(", ", $error);
            array_push($result_array,$result);
            $result = "";
             }

             return response()->json([
                'errors' => $result_array
            ], 400);

        }
        return redirect()->action([ProvidersController::class, 'index']);

    }


}
