<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Client\Application\ReadClientsUseCase;
use Modules\Common\Helpers\NotificationType;
use Modules\Common\Http\Controllers\Controller;
use Modules\Roles\Application\ReadRolesUseCase;
use Modules\Users\Application\CreateUserUseCase;
use Modules\Users\Application\DeleteUserUseCase;
use Modules\Users\Application\PaginateUsersUseCase;
use Modules\Users\Application\UpdateUserUseCase;
use Modules\Users\Entities\User;
use Modules\Users\Http\Requests\StoreUserRequest;
use Modules\Users\Http\Requests\UpdateUserRequest;
use Modules\Users\Http\Requests\ImportUserRequest;
use Spatie\RouteAttributes\Attributes\Resource;
use Throwable;
use Modules\Users\Policies\UserPermissions;
use Spatie\RouteAttributes\Attributes\Get;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsuariosImport;
use Spatie\RouteAttributes\Attributes\Post;

/**
 * @author Abel David.
 */
#[Resource('users')]
class UsersController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param PaginateUsersUseCase $paginateUsersUseCase
     * @return Response
     */
    public function index(PaginateUsersUseCase $paginateUsersUseCase): Response
    {
        return Inertia::render('Users/Index', [
            'data' => $paginateUsersUseCase()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ReadRolesUseCase $readRolesUseCase
     * @param ReadClientsUseCase $readClientsUseCase
     * @return Response
     */
    public function create(ReadRolesUseCase $readRolesUseCase, ReadClientsUseCase $readClientsUseCase): Response
    {
        return Inertia::render('Users/Create', [
            'roles' => $readRolesUseCase(),
            'clients' => $readClientsUseCase()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     * @param CreateUserUseCase $createUserUseCase
     * @return RedirectResponse
     */
    public function store(StoreUserRequest $request, CreateUserUseCase $createUserUseCase): RedirectResponse
    {
        $createUserUseCase($request);

        notification(NotificationType::Success, __('Successfully created.'));

        return redirect()->action([UsersController::class, 'index']);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('users::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ReadRolesUseCase $readRolesUseCase
     * @param ReadClientsUseCase $readClientsUseCase
     * @param User $user
     * @return Response
     */
    public function edit(ReadRolesUseCase $readRolesUseCase, ReadClientsUseCase $readClientsUseCase, User $user): Response
    {
        return Inertia::render('Users/Edit', [
            'user' => $user,
            'roles' => $readRolesUseCase(),
            'clients' => $readClientsUseCase()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserUseCase $updateUserUseCase
     * @param UpdateUserRequest $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(UpdateUserUseCase $updateUserUseCase, UpdateUserRequest $request, User $user): RedirectResponse
    {
        try {

            if ($updateUserUseCase($request, $user)) {

                notification(NotificationType::Success, __('Successfully updated.'));

            } else {

                notification(NotificationType::Info, __('No have changes to save.'));
            }

            return redirect()->action([UsersController::class, 'index']);

        } catch (Throwable $e) {

            notification(NotificationType::Error, $e->getMessage());
        }


        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteUserUseCase $deleteUserUseCase
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(DeleteUserUseCase $deleteUserUseCase, User $user): RedirectResponse
    {
        if ($deleteUserUseCase($user)) {

            notification(NotificationType::Success, __('Successfully removed.'));
        } else {

            notification(NotificationType::Error, __("Can not be delete the authenticated user."));
        }

        return redirect()->back();
    }


    #[Get('importuser', 'users.showImportForm')]
    public function showImportForm(User $user)
    {
        $this->authorize('import', $user);
        return Inertia::render('Users/Import');
    }

    #[Post('importuser', 'users.import')]
    public function import(ImportUserRequest $request)
    {

        try {
            Excel::import(new UsuariosImport, $request->file('file'));
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
        return redirect()->action([UsersController::class, 'index']);

    }
}
