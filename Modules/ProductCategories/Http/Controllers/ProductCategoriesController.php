<?php

namespace Modules\ProductCategories\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Modules\ProductCategories\Application\ReadProductCategoriesUseCase;
use Modules\Common\Helpers\NotificationType;
use Modules\Common\Http\Controllers\Controller;
use Modules\ProductCategories\Application\CreateProductCategoryUseCase;
use Modules\ProductCategories\Application\DeleteProductCategoryUseCase;
use Modules\ProductCategories\Application\PaginateProductCategoriesUseCase;
use Modules\ProductCategories\Application\TreeProductCategoriesUseCase;
use Modules\ProductCategories\Application\UpdateProductCategoriesUseCase;
use Modules\ProductCategories\Entities\ProductCategory;
use Modules\ProductCategories\Http\Requests\StoreProductCategoryRequest;
use Modules\ProductCategories\Http\Requests\ImportProductCategoryRequest;
use Modules\ProductCategories\Http\Requests\UpdateProductCategoryRequest;
use Spatie\RouteAttributes\Attributes\Resource;
use Modules\ProductCategories\Policies\ProductCategoryPermissions;
use Spatie\RouteAttributes\Attributes\Get;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CategoriaProductosImport;
use Spatie\RouteAttributes\Attributes\Post;

#[Resource('productCategories')]
class ProductCategoriesController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ProductCategory::class, 'productCategory');
    }
    /**
     * Display a listing of the resource.
     *
     * @param PaginateProductCategoriesUseCase $paginateProductCategoriesUseCase
     * @param TreeProductCategoriesUseCase $treeProductCategoriesUseCase
     * @param ReadProductCategoriesUseCase $readProductCategoriesUseCase
     * @return Response
     */
    public function index(PaginateProductCategoriesUseCase $paginateProductCategoriesUseCase, TreeProductCategoriesUseCase $treeProductCategoriesUseCase, ReadProductCategoriesUseCase $readProductCategoriesUseCase): Response
    {
        return Inertia::render('ProductCategories/Index', [
            'data' => $paginateProductCategoriesUseCase(),
            'productCategories' => $treeProductCategoriesUseCase($readProductCategoriesUseCase)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ReadProductCategoriesUseCase $readProductCategoriesUseCase
     * @return Response
     */
    public function create(ReadProductCategoriesUseCase $readProductCategoriesUseCase): Response
    {
        return Inertia::render('ProductCategories/Create', [
            'productCategories' => $readProductCategoriesUseCase(true, true)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProductCategoryRequest $request
     * @param CreateProductCategoryUseCase $createProductCategoryUseCase
     * @return RedirectResponse
     */
    public function store(StoreProductCategoryRequest $request, CreateProductCategoryUseCase $createProductCategoryUseCase): RedirectResponse
    {
        $createProductCategoryUseCase($request);

        notification(NotificationType::Success, __('Successfully created.'));

        return redirect()->action([ProductCategoriesController::class, 'index']);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('productcategories::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ProductCategory $productCategory
     * @param ReadProductCategoriesUseCase $readProductCategoriesUseCase
     * @return Response
     */
    public function edit(ProductCategory $productCategory, ReadProductCategoriesUseCase $readProductCategoriesUseCase): Response
    {
        return Inertia::render('ProductCategories/Edit', [
            'productCategory' => $productCategory,
            'productCategories' => $readProductCategoriesUseCase(true, true)->where('id', '<>', $productCategory->id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductCategoriesUseCase $updateProductCategoriesUseCase
     * @param UpdateProductCategoryRequest $request
     * @param ProductCategory $productCategory
     * @return RedirectResponse
     */
    public function update(UpdateProductCategoriesUseCase $updateProductCategoriesUseCase, UpdateProductCategoryRequest $request, ProductCategory $productCategory): RedirectResponse
    {
        if ($updateProductCategoriesUseCase($request, $productCategory)) {

            notification(NotificationType::Success, __('Successfully updated.'));

            return redirect()->action([ProductCategoriesController::class, 'index']);
        }

        notification(NotificationType::Info, __('No have changes to save.'));

        return redirect()->back();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteProductCategoryUseCase $deleteProductCategoryUseCase
     * @param ProductCategory $productCategory
     * @return RedirectResponse
     */
    public function destroy(DeleteProductCategoryUseCase $deleteProductCategoryUseCase, ProductCategory $productCategory): RedirectResponse
    {
        if ($deleteProductCategoryUseCase($productCategory)) {

            notification(NotificationType::Success, __('Successfully removed.'));
        } else {

            notification(NotificationType::Error, __("Can not be deleted the selected category because has relations."));
        }

        return back();
    }

    #[Get('importcategory', 'productCategories.showImportForm')]
    public function showImportForm(ProductCategory $productCategory)
    {
        $this->authorize('import', $productCategory);
        return Inertia::render('ProductCategories/Import');
    }

    #[Post('importcategory', 'productCategories.import')]
    public function import(ImportProductCategoryRequest $request)
    {

        try {
            Excel::import(new CategoriaProductosImport, $request->file('file'));
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
        return redirect()->action([ProductCategoriesController::class, 'index']);

    }
}
