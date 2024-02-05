<?php

namespace Modules\Quotes\Http\Controllers;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Client\Application\ReadClientsUseCase;
use Modules\Common\Helpers\NotificationType;
use Modules\Common\Http\Controllers\Controller;
use Modules\Designs\Application\DespieceUseCaseCreate;
use Modules\Products\Application\ReadProductsUseCase;
use Modules\Contracts\Application\ReadProjectsUseCase;
use Modules\Quotes\Application\ApproveQuoteUseCase;
use Modules\Quotes\Application\CreateQuoteUseCase;
use Modules\Quotes\Application\DeleteQuoteUseCase;
use Modules\Quotes\Application\GetQuotePdfUseCase;
use Modules\Quotes\Application\PaginateQuotesUseCase;
use Modules\Quotes\Application\UpdateQuoteUseCase;
use Modules\Quotes\Entities\Quote;
use Modules\Quotes\Http\Requests\StoreQuoteRequest;
use Modules\Quotes\Http\Requests\UpdateQuoteRequest;
use Modules\Quotes\Application\DespieceUseCase;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Resource;

/**
 * @author Abel David.
 */
#[Resource('quotes', except: ['show'])]
class QuotesController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Quote::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param PaginateQuotesUseCase $paginateQuotesUseCase
     * @return Response
     */
    public function index(
        PaginateQuotesUseCase $paginateQuotesUseCase
    ): Response
    {
        return Inertia::render('Quotes/Index', [
            'quotes' => $paginateQuotesUseCase()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ReadProjectsUseCase $readProjectsUseCase
     * @param ReadProductsUseCase $readProductsUseCase
     * @param ReadClientsUseCase $readClientsUseCase
     * @return Response
     */
    public function create(
        ReadProjectsUseCase $readProjectsUseCase,
        ReadProductsUseCase $readProductsUseCase,
        ReadClientsUseCase $readClientsUseCase
    ): Response
    {
        return Inertia::render('Quotes/Create', [
            'projects' => $readProjectsUseCase(auth()->user()->client_id),
            'products' => $readProductsUseCase(null),
            'clients' => $readClientsUseCase()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreQuoteRequest $request
     * @param CreateQuoteUseCase $createQuoteUseCase
     * @return RedirectResponse
     */
    public function store(StoreQuoteRequest $request, CreateQuoteUseCase $createQuoteUseCase): RedirectResponse
    {
        if ($createQuoteUseCase($request->validated())) {

            notification(NotificationType::Success, __('Successfully created.'));
        }

        return redirect()->action([self::class, 'index']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ReadProjectsUseCase $readProjectsUseCase
     * @param ReadProductsUseCase $readProductsUseCase
     * @param ReadClientsUseCase $readClientsUseCase
     * @param Quote $quote
     * @return Response
     */
    public function edit(
        ReadProjectsUseCase $readProjectsUseCase,
        ReadProductsUseCase $readProductsUseCase,
        ReadClientsUseCase $readClientsUseCase,
        Quote $quote
    ): Response
    {
        return Inertia::render('Quotes/Edit', [
            'quote' => $quote->load('products'),
            'projects' => $readProjectsUseCase(),
            'products' => $readProductsUseCase(null),
            'clients' => $readClientsUseCase()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateQuoteRequest $request
     * @param UpdateQuoteUseCase $updateQuoteUseCase
     * @param Quote $quote
     * @return RedirectResponse
     */
    public function update(
        UpdateQuoteRequest $request,
        UpdateQuoteUseCase $updateQuoteUseCase,
        Quote              $quote
    ): RedirectResponse
    {
        if ($updateQuoteUseCase($request->validated(), $quote)) {

            notification(NotificationType::Success, __('Successfully updated.'));
        } else {

            notification(NotificationType::Warning, __("No have changes to save." ));
        }

        return redirect()->action([self::class, 'index']);
    }

    /**
     * @param ApproveQuoteUseCase $useCase
     * @param Quote $quote
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    #[Get('quotes/{quote}/approve', 'quotes.approve')]
    public function approve(ApproveQuoteUseCase $useCase, Quote $quote): RedirectResponse
    {
        $this->authorize('approve', $quote);

        if ($useCase($quote)) {

            notification(NotificationType::Success, __("Successfully approved."));
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteQuoteUseCase $deleteQuoteUseCase
     * @param Quote $quote
     * @return RedirectResponse
     */
    public function destroy(
        DeleteQuoteUseCase $deleteQuoteUseCase,
        Quote $quote
    ): RedirectResponse
    {
        if ($deleteQuoteUseCase($quote)) {

            notification(NotificationType::Success, __('Successfully removed.'));
        } else {

            notification(NotificationType::Error, __('Can not be deleted the quote.'));
        }

        return back();
    }

    /**
     * @param GetQuotePdfUseCase $getQuotePdfUseCase
     * @param Quote $quote
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    #[Get('quotes/{quote}/download', 'quotes.download')]
    public function download(GetQuotePdfUseCase $getQuotePdfUseCase, Quote $quote): \Illuminate\Http\Response
    {
        return $getQuotePdfUseCase($quote)->download('Cotización '.$quote->id.'.pdf');
    }

    #[Get('quotes/{quote}/despiece', 'quote.despiece.pdf')]
    public function downloaddespiecelist(
        DespieceUseCase $useCase, Quote $quote

    ): \Illuminate\Http\Response {

        return $useCase($quote)->download('Lista de Despiece');
    }

    #[Post('quotes/despiece', 'quote.store.despiece')]
    public function downloaddespiecelistPost(
        Request $request,
        DespieceUseCaseCreate $useCase
    ): \Illuminate\Http\Response {

        $json = json_decode($request->getContent(), true);
        return $useCase($json)->download('Lista de Despiece');

    }
}
