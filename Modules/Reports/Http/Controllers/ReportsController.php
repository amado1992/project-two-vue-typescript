<?php

namespace Modules\Reports\Http\Controllers;

use App\Exports\ContractsClientPerSheet;
use App\Exports\ProductsPerSheet;
use App\Exports\ReRentProviderProductPerSheet;
use App\Exports\ReRentProviderProductsExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Client\Application\ReadClientsUseCase;
use Modules\Common\Http\Controllers\Controller;
use Modules\Products\Application\ReadProductsUseCase;
use Modules\Products\Entities\Product;
use Modules\Providers\Entities\Provider;
use Modules\Reports\Application\GetReRentsProductsByProvider;
use Modules\Reports\Application\GetReRentsProductsPdfUseCase;
use Modules\Reports\Application\GetReRentsProductsUseCase;
use Modules\Reports\Application\ReadFiltersProductsUseCase;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Modules\Client\Entities\Client;
use Modules\Common\Application\GetClientDataUseCase;
use Modules\Common\Helpers\NotificationType;
use Modules\Products\Application\ContractsUseCase;
use Modules\Projects\Application\ReadProjectsUseCase;
use Modules\Projects\Entities\Project;
use Modules\Providers\Application\ReadProvidersUseCase;
use Modules\Reports\Application\GetClientsContractsDataUseCase;
use Modules\Reports\Application\GetContractsPdfReportUseCase;
use Modules\Reports\Application\GetContractsByClientUseCase;
use Modules\Reports\Application\GetContractsProjectsUseCase;
use Modules\Reports\Application\GetEarningsPdfReportUseCase;
use Modules\Reports\Application\GetProductsPdfReportUseCase;
use Modules\Reports\Application\GetProductToBeDeliveredPdfUseCase;
use Modules\Reports\Application\ReadFiltersClientsUseCase;
use Modules\Reports\Application\ReadGeneralInfoClientsUseCase;
use Modules\Reports\Application\ReadProductsDetailsUseCase;


/**
 * Summary of ReportsController
 */
class ReportsController extends Controller
{
    /**
     * PRODUCTOS.
     *
     * @param ReadProductsUseCase $readProductsUseCase
     * @param ReadClientsUseCase $readClientsUseCase
     * @return void
     *
     * @author YulyLC
     */

    #[Get('reports/products', 'reports.products.filter')]
    public function filters(
        ReadProductsUseCase $readProductsUseCase,
        ReadClientsUseCase $readClientsUseCase
    ) {
        return Inertia::render('Reports/Products/Filters', [
            'products' => $readProductsUseCase(null),
            'clients' => $readClientsUseCase()
        ]);
    }

    /**
     * Preview products info
     */

    #[Post('reports/products/preview', 'reports.products.preview')]
    public function showProductsReportData(
        Request $request,
        ReadFiltersProductsUseCase $readFiltersProductsUseCase,
    ) {

        try {

            $products = $readFiltersProductsUseCase($request->clients, $request->products);

            return $response = ['data' => $products];
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Hubo un error recuperando los datos'], 500);
        }
        return response()->json($response);
    }

    /**
     * Products details info
     *
     * @param Request $request
     * @param ReadProductsDetailsUseCase $readProductsDetailsUseCase
     * @return void
     */

    #[Post('reports/products/details/', 'reports.products.details')]
    public function detailProductInfo(
        Request $request,
        ReadProductsDetailsUseCase $readProductsDetailsUseCase,
    ) {
        try {

            $product = Product::query()->whereId($request->product)->first();
            $clients = $request->clients;
            $values = $readProductsDetailsUseCase($request->product, $clients);

            return $data = ['data' => ['product' => $product, 'clients' => $clients, 'values' => $values]];
            $response = ['data' => $data];
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Hubo un error recuperando los datos'], 500);
        }
        return response()->json($response);
    }

    /**
     * Products reports - export to excel
     *
     * @param Request $request
     * @param ReadProductsDetailsUseCase $readProductsDetailsUseCase
     * @return void
     */
    #[Post('reports/products/excel', 'reports.products.excel')]
    public function exportProductsExcel(
        Request $request,
        ReadProductsDetailsUseCase $readProductsDetailsUseCase,
        ReadFiltersProductsUseCase $readFiltersProductsUseCase
    ) {

        $products = $readFiltersProductsUseCase($request->clients, $request->products);
        return (new ProductsPerSheet($products, $request->clients, $readProductsDetailsUseCase))
            ->download('Products_Info.xlsx');
    }

    /**
     * Products report - export to Pdf
     */
    #[Post('reports/products/download', 'reports.products.pdf')]
    public function downloadProducts(
        Request $request,
        GetProductsPdfReportUseCase $getProductsPdfUseCase,
        ReadProductsDetailsUseCase $readProductsDetailsUseCase,
        ReadFiltersProductsUseCase $readFiltersProductsUseCase
    ): \Illuminate\Http\Response {

        $products = $readFiltersProductsUseCase($request->clients, $request->products);
        return $getProductsPdfUseCase($products, $readProductsDetailsUseCase, $request->clients)
            ->download('ReporteProductos.pdf');
    }

    /**
     * CONTRACTS REPORTS
     */

    #[Get('reports/contracts', 'reports.contracts.filter')]
    public function contractfilters(
        ReadProductsUseCase $readProductsUseCase,
        ReadClientsUseCase $readClientsUseCase,
        ReadProjectsUseCase $readProjectsUseCase
    ) {

        return Inertia::render('Reports/Contracts/Filters', [
            'products' => $readProductsUseCase(null),
            'clients' => $readClientsUseCase(),
            'projects' => $readProjectsUseCase(),
        ]);
    }

    #[Post('reports/contracts/preview', 'reports.contracts.preview')]
    public function getPreview(
        Request $request,
        ReadGeneralInfoClientsUseCase $readGeneralInfoClientsUseCase
    ) {

        try {

            $clients = $readGeneralInfoClientsUseCase($request->clients, $request->products, $request->projects);

            return $response = ['data' => $clients];
        } catch (\Exception $exception) {

            return response()->json(['message' => 'Hubo un error recuperando los datos'], 500);
        }
        return response()->json($response);
    }

    #[Post('reports/contracts/details', 'reports.contracts.details')]
    public function detailContractInfoJson(
        Request $request,
        GetContractsByClientUseCase $getContractsByCLientsUseCase,
        ReadGeneralInfoClientsUseCase $readGeneralInfoClientsUseCase
    ) {

        try {

            $products = $request->products ? $request->products : Product::pluck('id')->toArray();
            $client = Client::whereId($request->client)->first();
            $newclient = $readGeneralInfoClientsUseCase([$client->id], $request->products, $request->projects);

            $allContracts = $getContractsByCLientsUseCase($client, $request->projects);

            return ['data' => ['newclient' => $newclient, 'contracts' => $allContracts, 'products' => $products]];
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Hubo un error recuperando los datos'], 500);
        }
    }

    /* Excel */

    #[Post('reports/contracts/excel', 'reports.contracts.excel')]
    public function exportClientContractsExcel(
        Request $request,

    ) {
        return redirect()->route(
            'reports.contracts.download',
            [
                'clients' => $request->clients,
                'products' => $request->products,
                'projects' => $request->projects
            ]
        );
    }

    #[Get('reports/contracts/download/', 'reports.contracts.download')]
    public function exportClientContractsExcelDownload(
        Request $request,
        ReadGeneralInfoClientsUseCase $readGeneralInfoClientsUseCase,
    ) {

        $products = $request->products ? $request->products : Product::pluck('id')->toArray();
        $projects = $request->projects ? $request->projects : Project::pluck('id')->toArray();
        $clients = $readGeneralInfoClientsUseCase($request->clients, $request->products, $request->projects);
        $new_clients = $clients->where('contracts_quantity', '!=', 0);
        return (new ContractsClientPerSheet($new_clients, $products, $projects))
            ->download('Contracts_Clients_Info.xlsx');
    }

    /**
     * Pdf Contracts
     */

    #[Post('reports/contracts/download', 'reports.contracts.pdf')]
    public function downloadContracts(
        Request $request,
        GetContractsPdfReportUseCase $getContractPdfUseCase,
        ReadGeneralInfoClientsUseCase $readGeneralInfoClientsUseCase
    ): \Illuminate\Http\Response {

        $products = $request->products ? $request->products : Product::pluck('id')->toArray();
        $projects = $request->projects ? $request->projects : Project::pluck('id')->toArray();
        $clients = $readGeneralInfoClientsUseCase($request->clients, $request->products, $request->projects);
        $new_clients = $clients->where('contracts_quantity', '!=', 0);

        return $getContractPdfUseCase($new_clients, $products, $projects)
            ->download('ReporteContratos.pdf');
    }

    /* PROFITS */

    #[Get('reports/earnings', 'reports.earnings.filter')]
    public function earningsfilters(
        ReadClientsUseCase $readClientsUseCase
    ) {
        return Inertia::render('Reports/Earnings/Filters', [

            'clients' => $readClientsUseCase(fields:['id','name'])
        ]);
    }

    /* Preview */

    #[Post('reports/earnings/preview', 'reports.earnings.preview')]
    public function getEarningsReportPreview(
        Request $request,
        GetClientsContractsDataUseCase $dataClients
    ) {

        try {
            $data = $dataClients($request->clients);
            return $response = ['data' => $data];
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Hubo un error recuperando los datos'], 500);
        }

        return response()->json($response);
    }

    /* Pdf report */

    #[Post('reports/earnings/download', 'reports.earnings.pdf')]
    public function downloadEarnings(
        Request $request,
        GetEarningsPdfReportUseCase $getEarningsPdfUseCase,
        GetClientsContractsDataUseCase $dataClients,

    ): \Illuminate\Http\Response {

        $data = $dataClients($request->clients);

        return $getEarningsPdfUseCase($data, $request->doctitle)
            ->download('ReporteContratos.pdf');
    }


    /* PRODUCTS TO BE DELIVERED */

    #[Get('reports/to-be-delivered', 'reports.productsToBeDelivered.filter')]
    public function productsToBeDeliveredFilters(
        ReadClientsUseCase $readClientsUseCase
    ) {
        return Inertia::render('Reports/ProductsToBeDelivered/Filters', [
            'clients' => $readClientsUseCase()
        ]);
    }

    /* Preview */

    #[Post('reports/to-be-delivered/preview', 'reports.productsToBeDelivered.preview')]
    public function getProductsToBeDeliveredReportPreview(
        Request $request,
        GetClientsContractsDataUseCase $dataClients,
    ) {

        try {
            $data = $dataClients($request->clients);

            return $response = ['data' => $data];
        } catch (\Exception $exception) {

            return response()->json(['message' => 'Hubo un error recuperando los datos'], 500);
        }

        return response()->json($response);
    }

    #[Post('reports/to-be-delivered/download', 'reports.productsToBeDelivered.pdf')]
    public function downloadproductsToBeDelivered(
        Request $request,
        GetProductToBeDeliveredPdfUseCase $getProductToBeDeliveredPdfUseCase,
        GetClientsContractsDataUseCase $dataClients,

    ): \Illuminate\Http\Response {

        $data = $dataClients($request->clients);

        return $getProductToBeDeliveredPdfUseCase($data, $request->doctitle)
            ->download('ReporteContratos.pdf');
    }

    #[Get('reports/reRents', 'reports.reRents.filter')]
    public function reRentsFilters(
        ReadProvidersUseCase $readProvidersUseCase,
        ReadProductsUseCase $readProductsUseCase,
    ) {
        return Inertia::render('Reports/ReRents/Filters', [
            'providers' => $readProvidersUseCase(),
            'products' => $readProductsUseCase(['id','name'])
        ]);
    }

    #[Post('reports/reRents/all', 'reports.reRents.all')]
    public function reRentsAll(
        Request $request,
        GetReRentsProductsUseCase $getReRentsProductsUseCase
    ) {
        return response()->json($getReRentsProductsUseCase($request->provider,$request->products));
    }

    #[Get('reports/reRents/product/provider', 'reports.reRents.product.provider')]
    public function reRentsProductsProvider(
        Request $request,
        GetReRentsProductsByProvider $getReRentsProductsByProvider
    ) {

        return response()->json($getReRentsProductsByProvider($request->input('provider_id'),$request->input('products')));
    }

    #[Post('reports/reRents/download', 'reports.reRents.pdf')]
    /**
     * Summary of downloadreRentsProductsProvider
     * @param Request $request
     * @param GetReRentsProductsUseCase $getReRentsProductsUseCase
     * @param GetReRentsProductsByProvider $getReRentsProductsByProvider
     * @param GetProductToBeDeliveredPdfUseCase $getProductToBeDeliveredPdfUseCase,
     * @return \Illuminate\Http\Response
     */
    public function downloadreRentsProductsProvider(
        Request $request,
        GetReRentsProductsUseCase $getReRentsProductsUseCase,
        GetReRentsProductsByProvider $getReRentsProductsByProvider,
        GetReRentsProductsPdfUseCase $getReRentsProductsPdfUseCase,


    ): \Illuminate\Http\Response {

        $data = $getReRentsProductsUseCase($request->provider,$request->products);

        return $getReRentsProductsPdfUseCase($data, $request->doctitle,$getReRentsProductsByProvider)
            ->download('ReporteRe-alquilados.pdf');
    }

    #[Post('reports/reRents/download/excel', 'reports.reRents.excel')]

    public function downloadExcelreRentsProductsProvider(
        Request $request,
        GetReRentsProductsUseCase $getReRentsProductsUseCase,
        GetReRentsProductsByProvider $getReRentsProductsByProvider,



    ){

        $data = $getReRentsProductsUseCase($request->provider,$request->products);
        $date = Carbon::now()->format('Y-m-d');
        $filename = "Realquilado $date .xlsx";
        return (new ReRentProviderProductPerSheet(
            $data,
        ))->download($filename);
    }

}
