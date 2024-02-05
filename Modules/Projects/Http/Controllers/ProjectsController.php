<?php

namespace Modules\Projects\Http\Controllers;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Client\Application\ReadClientsUseCase;
use Modules\Common\Helpers\NotificationType;
use Modules\Common\Http\Controllers\Controller;
use Modules\Projects\Application\CreateProjectUseCase;
use Modules\Projects\Application\DeleteProjectUseCase;
use Modules\Projects\Application\PaginateProjectsUseCase;
use Modules\Projects\Application\UpdateProjectUseCase;
use Modules\Projects\Entities\Project;
use Modules\Projects\Http\Requests\ImportProjectRequest;
use Modules\Projects\Http\Requests\StoreProjectRequest;
use Modules\Projects\Http\Requests\UpdateProjectRequest;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Resource;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProyectosImport;
use Spatie\RouteAttributes\Attributes\Post;




/**
 * @author Abel David.
 */
#[Resource('projects')]
class ProjectsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Project::class);
    }


    /**
     * Display a listing of the resource.
     *
     * @param PaginateProjectsUseCase $paginateProjectsUseCase
     * @return Response
     */
    public function index(PaginateProjectsUseCase $paginateProjectsUseCase): Response
    {
        return Inertia::render('Projects/Index', [
            'projects' => $paginateProjectsUseCase()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ReadClientsUseCase $readClientsUseCase
     * @return Response
     */
    public function create(ReadClientsUseCase $readClientsUseCase): Response
    {
        return Inertia::render('Projects/Create', [
            'clients' => $readClientsUseCase()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProjectRequest $request
     * @param CreateProjectUseCase $createProjectUseCase
     * @return RedirectResponse
     */
    public function store(StoreProjectRequest $request, CreateProjectUseCase $createProjectUseCase): RedirectResponse
    {
        if ($project = $createProjectUseCase($request->validated())) {

            notification(NotificationType::Success, __('Successfully created.'));

            session(['redirect_data' => $project]);
        }

        if ($redirect_to = $request->input('redirect_to')) {

            return redirect($redirect_to);
        }

        return redirect()->action([self::class, 'index']);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('projects::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ReadClientsUseCase $readClientsUseCase
     * @param Project $project
     * @return Response
     */
    public function edit(ReadClientsUseCase $readClientsUseCase, Project $project): Response
    {
        return Inertia::render('Projects/Edit', [
            'project' => $project,
            'clients' => $readClientsUseCase()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProjectRequest $request
     * @param UpdateProjectUseCase $updateProjectUseCase
     * @param Project $project
     * @return RedirectResponse
     */
    public function update(
        UpdateProjectRequest $request,
        UpdateProjectUseCase $updateProjectUseCase,
        Project $project
    ): RedirectResponse
    {
        if ($updateProjectUseCase($request->validated(), $project)) {

            notification(NotificationType::Success, __('Successfully updated.'));
        }

        return redirect()->action([self::class, 'index']);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteProjectUseCase $deleteProjectUseCase
     * @param Project $project
     * @return RedirectResponse
     */
    public function destroy(DeleteProjectUseCase $deleteProjectUseCase, Project $project): RedirectResponse
    {
        if ($deleteProjectUseCase($project)) {

            notification(NotificationType::Success, __('Successfully removed.'));
        }

        return back();
    }

    #[Get('importproject', 'projects.showImportForm')]
    public function showImportForm(Project $project)
    {
        $this->authorize('import', $project);
        return Inertia::render('Projects/Import');
    }

    #[Post('importproject', 'projects.import')]
    public function import(ImportProjectRequest $request)
    {

        try {
            Excel::import(new ProyectosImport, $request->file('file'));
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
        return redirect()->action([self::class, 'index']);
    }

}
