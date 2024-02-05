<?php

namespace Modules\Brands\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Brands\Application\DeleteBrandUseCase;
use Modules\Brands\Application\PaginateBrandsUseCase;
use Modules\Brands\Application\CreateBrandUseCase;
use Modules\Brands\Application\UpdateBrandUseCase;
use Modules\Brands\Entities\Brand;
use Modules\Common\Helpers\NotificationType;
use Modules\Common\Http\Controllers\Controller;
use Modules\Brands\Http\Requests\StoreBrandRequest;
use Modules\Brands\Http\Requests\UpdateBrandRequest;
use Spatie\RouteAttributes\Attributes\Resource;

#[Resource('brands')]
class BrandsController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Brand::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param PaginateBrandsUseCase $paginateBrandsUseCase
     * @return Response
     */
    public function index(PaginateBrandsUseCase $paginateBrandsUseCase): Response
    {
        return Inertia::render('Brands/Index', [
            'data' => $paginateBrandsUseCase()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return Inertia::render('Brands/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreBrandRequest $request
     * @param CreateBrandUseCase $createBrandUserCase
     * @return RedirectResponse
     */
    public function store(StoreBrandRequest $request, CreateBrandUseCase $createBrandUserCase): RedirectResponse
    {
        $createBrandUserCase($request->validated());

        notification(NotificationType::Success, __('Successfully created.'));

        return redirect()->action([BrandsController::class, 'index']);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('brands::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Brand $brand
     * @return Response
     */
    public function edit(Brand $brand): Response
    {
        return Inertia::render('Brands/Edit', [
            'brand' => $brand,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateBrandUseCase $updateBrandUseCase
     * @param UpdateBrandRequest $request
     * @param Brand $brand
     * @return RedirectResponse
     */
    public function update(UpdateBrandUseCase $updateBrandUseCase, UpdateBrandRequest $request, Brand $brand): RedirectResponse
    {
        if ($updateBrandUseCase($request, $brand)) {

            notification(NotificationType::Success, __('Successfully updated.'));

            return redirect()->action([BrandsController::class, 'index']);
        }

        notification(NotificationType::Info, __('No have changes to save.'));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteBrandUseCase $deleteBrandUseCase
     * @param Brand $brand
     * @return RedirectResponse
     */
    public function destroy(DeleteBrandUseCase $deleteBrandUseCase, Brand $brand): RedirectResponse
    {
        if ($deleteBrandUseCase($brand)) {

            notification(NotificationType::Success, __('Successfully removed.'));
        } else {

            notification(NotificationType::Error, __("Can not be deleted the selected brand."));
        }

        return redirect()->back();
    }
}
