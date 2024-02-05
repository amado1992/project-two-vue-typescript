<?php

namespace Modules\Products\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Inertia\Inertia;
use Illuminate\Contracts\Support\Renderable;
use Modules\Brands\Application\ReadBrandsUseCase;
use Modules\Common\Helpers\NotificationType;
use Modules\Common\Helpers\ProductType;
use Modules\Common\Http\Controllers\Controller;
use Modules\ProductCategories\Application\ReadProductCategoriesUseCase;
use Modules\Products\Application\CreateProductUseCase;
use Modules\Products\Application\DeleteProductUseCase;
use Modules\Products\Application\GetActiveContractsUseCase;
use Modules\Products\Application\GetReRentsByProviderUseCase;
use Modules\Products\Application\GetReRentsUseCase;
use Modules\Products\Application\PaginateProductsUseCase;
use Modules\Products\Application\UpdateProductUseCase;
use Modules\Products\Entities\Product;
use Modules\Products\Http\Requests\StoreProductRequest;
use Modules\Products\Http\Requests\ImportProductRequest;
use Modules\Products\Http\Requests\UpdateProductRequest;
use Modules\Providers\Entities\Provider;
use Modules\Settings\Applications\ReadSettingUserCase;
use Modules\Users\Policies\ProductPermissions;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Resource;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductosImport;
use Spatie\RouteAttributes\Attributes\Post;


#[Resource('products', except: ['show'])]
class ProductsController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Product::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param PaginateProductsUseCase $paginateProductsUseCase
     * @return Response
     */
    public function index(PaginateProductsUseCase $paginateProductsUseCase): Response
    {
        return Inertia::render('Products/Index', [
            'data' => $paginateProductsUseCase(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ReadProductCategoriesUseCase $readProductCategoriesUseCase
     *
     * @return Response
     */
    public function create(ReadProductCategoriesUseCase $readProductCategoriesUseCase): Response
    {
        return Inertia::render('Products/Create', [
            'productCategories' => $readProductCategoriesUseCase(true, false),
            'types' => pluckProductTypes(ProductType::cases())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProductRequest $request
     * @param CreateProductUseCase $createProductUseCase
     * @return RedirectResponse
     */
    public function store(StoreProductRequest $request, CreateProductUseCase $createProductUseCase): RedirectResponse
    {
        $createProductUseCase($request);

        notification(NotificationType::Success, __('Successfully created.'));

        return redirect()->action([ProductsController::class, 'index']);
    }

    /**
     * @param GetActiveContractsUseCase $useCase
     * @param Product $product
     * @return JsonResponse
     * @throws AuthorizationException
     */
    #[Get('products/{product}/active-contracts', 'products.active-contracts')]
    public function activeContracts(GetActiveContractsUseCase $useCase, Product $product): JsonResponse
    {
        $this->authorize('view', $product);
        return response()->json($useCase($product));
    }

    /**
     * @param GetReRentsUseCase $useCase
     * @param Product $product
     * @return JsonResponse
     * @throws AuthorizationException
     */
    #[Get('products/{product}/re-rents', 'products.re-rents')]
    public function reRents(GetReRentsUseCase $useCase, Product $product): JsonResponse
    {
        $this->authorize('view', $product);
        return response()->json($useCase($product));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('products::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product $product
     * @param ReadProductCategoriesUseCase $readProductCategoriesUseCase
     * @return Response
     */
    public function edit(Product $product, ReadProductCategoriesUseCase $readProductCategoriesUseCase): Response
    {
        return Inertia::render('Products/Edit', [
            'product' => $product,
            'productCategories' => $readProductCategoriesUseCase(true, false),
            'types' => pluckProductTypes(ProductType::cases())
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductUseCase $updateProductUseCase
     * @param UpdateProductRequest $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function update(UpdateProductUseCase $updateProductUseCase, UpdateProductRequest $request, Product $product): RedirectResponse
    {
        if ($updateProductUseCase($request, $product)) {

            notification(NotificationType::Success, __('Successfully updated.'));

            return redirect()->action([ProductsController::class, 'index']);
        }

        notification(NotificationType::Info, __('No have changes to save.'));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteProductUseCase $deleteProductUseCase
     * @param Product $product
     * @return RedirectResponse
     */
    public function destroy(DeleteProductUseCase $deleteProductUseCase, Product $product): RedirectResponse
    {
        if ($deleteProductUseCase($product)) {

            notification(NotificationType::Success, __('Successfully removed.'));
        } else {

            notification(NotificationType::Error, __("Can not be deleted the selected product."));
        }

        return redirect()->back();
    }

    #[Get('importprod', 'products.showImportForm')]
    public function showImportForm(Product $product)
    {
        $this->authorize('import', $product);
        return Inertia::render('Products/Import');
    }

    #[Post('importprod', 'products.import')]
    public function import(ImportProductRequest $request)
    {

        try {
            Excel::import(new ProductosImport, $request->file('file'));

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
        return redirect()->action([ProductsController::class, 'index']);

    }

    #[Get('products/re-rents/{providers}', 'products.rerents.providers')]
    public function reRentsProviders(GetReRentsByProviderUseCase $useCase, int $provider): JsonResponse
    {
        return response()->json($useCase($provider));
    }
}
