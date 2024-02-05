<?php

namespace Modules\Designs\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Client\Application\ReadClientsUseCase;
use Modules\Common\Helpers\NotificationType;
use Modules\Common\Http\Controllers\Controller;
use Modules\Contracts\Application\ReadProjectsUseCase;
use Modules\Designs\Application\ApproveDesignUseCase;
use Modules\Designs\Application\CreateDesignUseCase;
use Modules\Designs\Application\DeleteDesignUseCase;
use Modules\Designs\Application\DespieceUseCaseCreate;
use Modules\Designs\Application\PaginateDesignsUseCase;
use Modules\Designs\Application\UpdateDesignUseCase;
use Modules\Designs\Entities\Design;
use Modules\Designs\Http\Requests\StoreDesignRequest;
use Modules\Designs\Http\Requests\UpdateDesignRequest;
use Modules\Products\Application\ReadProductsUseCase;
use Modules\Reports\Application\DespieceUseCase;
use Modules\Reports\Application\GetClientsContractsDataUseCase;
use Modules\Reports\Application\GetProductToBeDeliveredPdfUseCase;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Resource;

/**
 * @author Abel David.
 */
#[Resource('designs', except: ['show'])]
class DesignsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param PaginateDesignsUseCase $useCase
     * @return Response
     */
    #[Get('/', 'designs.index')]
    public function index(PaginateDesignsUseCase $useCase): Response
    {
        return Inertia::render('Designs/Index', [
            'designs' => $useCase()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ReadClientsUseCase $readClientsUseCase
     * @param ReadProjectsUseCase $readProjectsUseCase
     * @param ReadProductsUseCase $readProductsUseCase
     * @return Response
     */
    public function create(
        ReadClientsUseCase $readClientsUseCase,
        ReadProjectsUseCase $readProjectsUseCase,
        ReadProductsUseCase $readProductsUseCase
    ): Response
    {
        return Inertia::render('Designs/Create', [
            'clients' => $readClientsUseCase(),
            'projects' => $readProjectsUseCase(auth()->user()->client_id),
            'products' => $readProductsUseCase(null)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDesignRequest $request
     * @param CreateDesignUseCase $useCase
     * @return RedirectResponse
     */
    public function store(StoreDesignRequest $request, CreateDesignUseCase $useCase): RedirectResponse
    {
        if ($useCase($request->validated())) {

            notification(NotificationType::Success, __('Successfully created.'));
        }

        return to_route('designs.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ReadClientsUseCase $readClientsUseCase
     * @param ReadProjectsUseCase $readProjectsUseCase
     * @param ReadProductsUseCase $readProductsUseCase
     * @param Design $design
     * @return Response
     */
    public function edit(
        ReadClientsUseCase  $readClientsUseCase,
        ReadProjectsUseCase $readProjectsUseCase,
        ReadProductsUseCase $readProductsUseCase,
        Design $design
    ): Response
    {
        return Inertia::render('Designs/Edit', [
            'clients' => $readClientsUseCase(),
            'projects' => $readProjectsUseCase(auth()->user()->client_id),
            'products' => $readProductsUseCase(null),
            'design' => $design
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateDesignRequest $request
     * @param UpdateDesignUseCase $useCase
     * @param Design $design
     * @return RedirectResponse
     */
    public function update(
        UpdateDesignRequest $request,
        UpdateDesignUseCase $useCase,
        Design $design
    ): RedirectResponse
    {
        if ($useCase($request->validated(), $design)) {

            notification(NotificationType::Success, __('Successfully updated.'));
        } else {

            notification(NotificationType::Warning, __('No have changes to save.'));
        }

        return to_route('designs.index');
    }

    /**
     * @param ApproveDesignUseCase $useCase
     * @param Design $design
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    #[Get('designs/{design}/approve', 'designs.approve')]
    public function approve(ApproveDesignUseCase $useCase, Design $design): RedirectResponse
    {
        $this->authorize('approve', $design);

        if ($useCase($design)) {

            notification(NotificationType::Success, __('Successfully approved.'));
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteDesignUseCase $useCase
     * @param Design $design
     * @return RedirectResponse
     */
    public function destroy(DeleteDesignUseCase $useCase, Design $design)
    {
        if ($useCase($design)) {

            notification(NotificationType::Success, __('Successfully removed.'));
        } else {

            notification(NotificationType::Error, __('Can not be deleted the entity.'));
        }

        return to_route('designs.index');
    }

    /**
     * @param Media $media
     * @return Media
     */
    #[Get('designs/{media}/download', 'design.download-file')]
    public function downloadFile(Media $media): Media
    {
        return $media;
    }

    #[Get('designs/{design}/despiece', 'designs.despiece.pdf')]
    public function downloaddespiecelist(
        DespieceUseCase $useCase, Design $design

    ): \Illuminate\Http\Response {

        return $useCase($design)->download('Lista de Despiece');
    }

    #[Post('designs/despiece', 'designs.store.despiece')]
    public function downloaddespiecelistPost(
        Request $request,
        DespieceUseCaseCreate $useCase
    ): \Illuminate\Http\Response {

        $json = json_decode($request->getContent(), true);
        return $useCase($json)->download('Lista de Despiece');

    }
}
