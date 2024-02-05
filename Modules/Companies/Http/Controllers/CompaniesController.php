<?php

namespace Modules\Companies\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Common\Helpers\NotificationType;
use Modules\Common\Http\Controllers\Controller;
use Modules\Companies\Application\ReadCompanyUseCase;
use Modules\Companies\Application\UpdateCompanyUseCase;
use Modules\Companies\Entities\Company;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Companies\Http\Requests\UpdateCompanyRequest;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Resource;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmpresasImport;
use Modules\Companies\Http\Requests\ImportCompanyRequest;

class CompaniesController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Company::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param ReadCompanyUseCase $readCompanyUseCase
     * @return Response
     */
    #[Get('company', name: "companies.index")]
    public function index(ReadCompanyUseCase $readCompanyUseCase): Response
    {
        return Inertia::render('Company/Edit', [
            'company' => $readCompanyUseCase()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UpdateCompanyRequest $request
     * @param UpdateCompanyUseCase $createCompanyUserCase
     * @param ReadCompanyUseCase $readCompanyUseCase
     * @return RedirectResponse
     */
    #[Post('company', name: "companies.store")]
    public function store(UpdateCompanyRequest $request, UpdateCompanyUseCase $createCompanyUserCase, ReadCompanyUseCase $readCompanyUseCase): RedirectResponse
    {
        $company = $readCompanyUseCase();

        if ($createCompanyUserCase($request, $company)) {

            notification(NotificationType::Success, __('Successfully updated.'));

            return redirect()->action([CompaniesController::class, 'index']);
        }

        notification(NotificationType::Info, __('No have changes to save.'));

        return redirect()->back();
    }

    /**
     * Download the company logo.
     *
     * @param ReadCompanyUseCase $readCompanyUseCase
     * @return Media
     */
    #[Get('downloadLogo', name: "companies.logo")]
    public function downloadLogo(ReadCompanyUseCase $readCompanyUseCase): Media
    {
        $company = $readCompanyUseCase();
        return $company?->downloadImage();

    }

    #[Get('importcompany', 'companies.showImportForm')]
    public function showImportForm(Company $company)
    {
        $this->authorize('import', $company );

        return Inertia::render('Company/Import');
    }

    #[Post('importcompanies', 'companies.import')]
    public function import(ImportCompanyRequest $request)
    {

        try {
            $companies = Excel::toArray(new EmpresasImport, $request->file('file'));
            if(count($companies)>0){
                $companie = $companies[0][0];

                $company = new Company([
                    'name' => strval($companie['nombre']),
                    'active' => 1,
                    'ruc' => strval($companie['ruc']),
                    'dv' => strval($companie['dv']),
                    'email' => strval($companie['correo_electronico']),
                    'created_by' => auth()->user()->getAuthIdentifier(),
                    'address' => strval($companie['direccion']),
                    'social_reason' => strval($companie['razon_social']),
                ]);
                $company->save();
            }
            notification(NotificationType::Success, __('Successfully imported.'));
        } catch (Exception $e) {
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
        return redirect()->action([CompaniesController::class, 'index']);

    }
}
