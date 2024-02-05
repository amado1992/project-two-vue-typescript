<?php

namespace Modules\Designs\Application;

use Illuminate\Support\Facades\Log;
use Modules\Designs\Entities\Design;
use Modules\Designs\Events\CreatingDesign;
use Modules\Designs\Events\DesignCreated;
use Modules\Quotes\Application\CreateQuoteUseCase;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

/**
 * @author Abel David.
 */
class CreateDesignUseCase
{
    /**
     * @param CreateQuoteUseCase $createQuoteUseCase
     */
    public function __construct(
        private readonly CreateQuoteUseCase $createQuoteUseCase
    )
    {
        //
    }

    /**
     * @param array $data
     * @return Design
     */
    public function __invoke(array $data): Design
    {
        $quote = ($this->createQuoteUseCase)($data);

        CreatingDesign::dispatch();

        $design = Design::create([
            'quote_id' => $quote->id,
            'created_by' => auth()->user()->getAuthIdentifier()
        ]);

        foreach ($data['files'] as $file) {

            try {

                $design->addMedia($file)
                    ->toMediaCollection();

            } catch (\Throwable $e) {

                Log::error($e->getMessage());
            }
        }

        DesignCreated::dispatch($design);

        return $design;
    }
}
