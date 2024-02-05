<?php

namespace Modules\Inventories\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Common\Helpers\NotificationType;
use Modules\Common\Http\Controllers\Controller;
use Modules\Inventories\Application\CreateReasonUseCase;
use Modules\Inventories\Application\DeleteReasonUseCase;
use Modules\Inventories\Application\ReadReasonsUseCase;
use Modules\Inventories\Application\UpdateReasonUseCase;
use Modules\Inventories\Entities\Reason;
use Spatie\RouteAttributes\Attributes\Resource;
use Spatie\RouteAttributes\Attributes\Get;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AjustesImport;
use Modules\Inventories\Http\Requests\ImportReasonRequest;
use Spatie\RouteAttributes\Attributes\Post;

/**
 * @author Abel David.
 */
#[Resource('reasons', except: ['show'])]
class ReasonsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param ReadReasonsUseCase $readReasonsUseCase
     * @return Response
     */
    public function index(Request $request, ReadReasonsUseCase $readReasonsUseCase): Response
    {
        return Inertia::render('Reasons/Index', [
            'reasons' => $readReasonsUseCase($request->input('filter', 'actives'))
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Reasons/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param CreateReasonUseCase $createReasonUseCase
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request, CreateReasonUseCase $createReasonUseCase): RedirectResponse
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255', Rule::unique('reasons', 'name')],
            'active' => ['nullable', 'boolean']
        ]);

        if ($createReasonUseCase($request->only(['name', 'active']))) {

            notification(NotificationType::Success, __('Successfully created.'));
        }

        if ($redirect_to = $request->input('redirect_to')) {

            return redirect($redirect_to);
        }

        return redirect()->action([self::class, 'index']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Reason $reason
     * @return Response
     */
    public function edit(Reason $reason): Response
    {
        return Inertia::render('Reasons/Edit', [
            'reason' => $reason
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param UpdateReasonUseCase $updateReasonUseCase
     * @param Reason $reason
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(
        Request $request,
        UpdateReasonUseCase $updateReasonUseCase,
        Reason $reason
    ): RedirectResponse
    {
        $this->validate($request, [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('reasons', 'name')->ignore($reason->id)
            ],
            'active' => ['nullable', 'boolean']
        ]);

        if ($updateReasonUseCase($request->only(['name', 'active']), $reason)) {

            notification(NotificationType::Success, __('Successfully updated.'));
        }

        return redirect()->action([self::class, 'index']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteReasonUseCase $deleteReasonUseCase
     * @param Reason $reason
     * @return RedirectResponse
     */
    public function destroy(DeleteReasonUseCase $deleteReasonUseCase, Reason $reason): RedirectResponse
    {
        if ($deleteReasonUseCase($reason)) {

            notification(NotificationType::Success, __('Successfully removed.'));
        }

        return back();
    }

    #[Get('importreason', 'reasons.showImportForm')]
    public function showImportForm(Reason $reason)
    {
        $this->authorize('import', $reason );

        return Inertia::render('Reasons/Import');
    }

    #[Post('importreason', 'reasons.import')]
    public function import(ImportReasonRequest $request)
    {

        try {
            Excel::import(new AjustesImport, $request->file('file'));
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
        return redirect()->action([ReasonsController::class, 'index']);

    }
}
