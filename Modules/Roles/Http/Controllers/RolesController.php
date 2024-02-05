<?php

namespace Modules\Roles\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Common\Helpers\NotificationType;
use Modules\Common\Http\Controllers\Controller;
use Modules\Roles\Application\CreateRoleUseCase;
use Modules\Roles\Application\DeleteRoleUseCase;
use Modules\Roles\Application\PaginateRolesUseCase;
use Modules\Roles\Application\ReadModulesUseCase;
use Modules\Roles\Application\UpdateRoleUseCase;
use Modules\Roles\Entities\Role;
use Modules\Roles\Http\Requests\StoreRoleRequest;
use Modules\Roles\Http\Requests\UpdateRoleRequest;
use Spatie\RouteAttributes\Attributes\Resource;

#[Resource('roles')]
class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param PaginateRolesUseCase $paginateRolesUseCase
     * @return Response
     * @throws AuthorizationException
     */
    public function index(PaginateRolesUseCase $paginateRolesUseCase): Response
    {
        $this->authorize('viewAny', Role::class);

        return Inertia::render('Roles/Index', [
            'roles' => $paginateRolesUseCase()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ReadModulesUseCase $readModulesUseCase
     * @return Response
     * @throws AuthorizationException
     */
    public function create(ReadModulesUseCase $readModulesUseCase): Response
    {
        $this->authorize('create', Role::class);

        return Inertia::render('Roles/Create', [
            'modules' => $readModulesUseCase()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRoleRequest $request
     * @param CreateRoleUseCase $createRoleUseCase
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(StoreRoleRequest $request, CreateRoleUseCase $createRoleUseCase): RedirectResponse
    {
        $this->authorize('create', Role::class);

        $createRoleUseCase($request);

        notification(NotificationType::Success, __('Successfully created.'));

        return redirect()->action([RolesController::class, 'index']);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('roles::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Role $role
     * @param ReadModulesUseCase $readModulesUseCase
     * @return Response
     * @throws AuthorizationException
     */
    public function edit(Role $role, ReadModulesUseCase $readModulesUseCase): Response
    {
        $this->authorize('update', $role);

        return Inertia::render('Roles/Edit', [
            'id' => $role->id,
            'name' => $role->name,
            'modules' => $readModulesUseCase($role)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRoleRequest $request
     * @param UpdateRoleUseCase $updateRoleUseCase
     * @param Role $role
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(UpdateRoleRequest $request, UpdateRoleUseCase $updateRoleUseCase, Role $role): RedirectResponse
    {
        $this->authorize('update', $role);

        if ($updateRoleUseCase($request, $role)) {

            notification(NotificationType::Success, __('Successfully updated.'));
        } else {

            notification(NotificationType::Info, __('No have changes to save.'));
        }

        return redirect()->action([RolesController::class, 'index']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteRoleUseCase $deleteRoleUseCase
     * @param Role $role
     * @return RedirectResponse
     */
    public function destroy(DeleteRoleUseCase $deleteRoleUseCase, Role $role): RedirectResponse
    {
        $deleteRoleUseCase($role);

        notification(NotificationType::Success, __('Successfully deleted.'));

        return redirect()->action([RolesController::class, 'index']);
    }
}
