<?php

namespace Modules\Settings\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Modules\Settings\Entities\GeneralSettings;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Common\Helpers\NotificationType;
use Modules\Common\Http\Controllers\Controller;
use Modules\Settings\Applications\ReadBillersUserCase;
use Modules\Settings\Applications\ReadSettingUserCase;
use Modules\Settings\Applications\StoreSettingUserCase;
use Modules\Settings\Http\Requests\ImportSettingsRequest;
use Modules\Users\Application\ReadUsersUseCase;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Throwable;
use Spatie\RouteAttributes\Attributes\Resource;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\GeneralesImport;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param ReadSettingUserCase $readSettingUserCase
     * @param ReadUsersUseCase $readUsersUseCase
     * @param ReadBillersUserCase $readBillersUserCase
     * @return Response
     */
    #[Get('settings', name: "settings.index")]
    public function index(ReadSettingUserCase $readSettingUserCase, ReadUsersUseCase $readUsersUseCase, ReadBillersUserCase $readBillersUserCase): Response
    {
        return Inertia::render('Settings/Edit', [
            'settings' => $readSettingUserCase(),
            'users' => $readUsersUseCase(true),
            'billers' => $readBillersUserCase()
        ]);
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param StoreSettingUserCase $storeSettingUserCase
     * @return RedirectResponse
     * @throws Throwable
     */
    #[Post('settings', name: "settings.store")]
    public function store(Request $request, StoreSettingUserCase $storeSettingUserCase): RedirectResponse
    {
        $this->validate($request, [
            'billers' => ['nullable', 'array'],
            'tax_itbms' => ['required', 'numeric'],
            'expire_contract_notification' => ['required', 'numeric']
        ]);

        try {
            if ($storeSettingUserCase($request)) {

                notification(NotificationType::Success, __('Successfully updated.'));

                return redirect()->action([SettingsController::class, 'index']);
            }

            notification(NotificationType::Info, __('No have changes to save.'));

            return redirect()->back();
        } catch (Throwable $th) {
            notification(NotificationType::Error, $th->getMessage());
        }

        return back();
    }

    #[Get('showimportsettings', 'settings.showImportForm')]
    public function showImportForm(GeneralSettings $settings)
    {

        return Inertia::render('Settings/Import');
    }

    #[Post('importsettings', 'settings.import')]
    public function import(ImportSettingsRequest $request,GeneralSettings $settings)
    {

        try {
            Excel::import(new GeneralesImport($settings), $request->file('file'));
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
        return redirect()->action([SettingsController::class, 'index']);

    }
}
