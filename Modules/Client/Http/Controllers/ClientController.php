<?php

namespace Modules\Client\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Modules\Client\Application\CreateClientUseCase;
use Modules\Client\Application\DeleteClientUseCase;
use Modules\Client\Application\PaginateClientsUseCase;
use Modules\Client\Application\UpdateClientUseCase;
use Modules\Client\Http\Requests\StoreClientRequest;
use Modules\Client\Http\Requests\ImportClientRequest;
use Modules\Client\Http\Requests\UpdateClientRequest;
use Modules\Common\Helpers\NotificationType;
use Modules\Common\Http\Controllers\Controller;
use Modules\Client\Entities\Client;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Resource;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ClientesImport;
use Spatie\RouteAttributes\Attributes\Post;

/**
 * @author cheynerpb.
 */
#[Resource('clients')]
class ClientController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Client::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @param PaginateClientsUseCase $paginateClientsUseCase
     * @return Response
     */
    public function index(PaginateClientsUseCase $paginateClientsUseCase): Response
    {
        return Inertia::render('Clients/Index', [
            'data' => $paginateClientsUseCase()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return Inertia::render('Clients/Create');
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param StoreClientRequest $request
     * @param CreateClientUseCase $createClientUseCase
     * @return RedirectResponse
     */
    public function store(StoreClientRequest $request, CreateClientUseCase $createClientUseCase): RedirectResponse
    {
        $createClientUseCase($request);

        notification(NotificationType::Success, __('Successfully created.'));

        return redirect()->action([ClientController::class, 'index']);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('client::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Client $client
     * @return Response
     */
    public function edit(Client $client): Response
    {
        return Inertia::render('Clients/Edit', [
            'client' => $client,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateClientUseCase $updateClientUseCase
     * @param UpdateClientRequest $request
     * @param Client $client
     * @return RedirectResponse
     */
    public function update(UpdateClientUseCase $updateClientUseCase, UpdateClientRequest $request, Client $client): RedirectResponse
    {
        if ($updateClientUseCase($request, $client)) {

            notification(NotificationType::Success, __('Successfully updated.'));

            return redirect()->action([ClientController::class, 'index']);
        }

        notification(NotificationType::Info, __('No have changes to save.'));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteClientUseCase $deleteClientUseCase
     * @param Client $client
     * @return RedirectResponse
     */
    public function destroy(DeleteClientUseCase $deleteClientUseCase, Client $client): RedirectResponse
    {
        if ($deleteClientUseCase($client)) {

            notification(NotificationType::Success, __('Successfully removed.'));
        } else {

            notification(NotificationType::Error, __("Can not be delete the selected client."));
        }

        return redirect()->back();
    }



    #[Get('import', 'clients.showImportForm')]
    public function showImportForm(Client $client)
    {
        $this->authorize('import', $client);
        return Inertia::render('Clients/Import');
    }

    #[Post('import', 'clients.import')]
    public function import(ImportClientRequest $request)
    {

        try {

            Excel::import(new ClientesImport, $request->file('file'));
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
             notification(NotificationType::Error, __(strval($result)));
             return response()->json([
                'errors' => $result_array
            ], 400);


        }

        return redirect()->action([ClientController::class, 'index']);

    }



}
