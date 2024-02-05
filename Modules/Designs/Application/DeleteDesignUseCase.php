<?php

namespace Modules\Designs\Application;

use Modules\Designs\Entities\Design;
use Modules\Designs\Events\DeletingDesign;
use Modules\Designs\Events\DesignDeleted;
use Modules\Quotes\Application\DeleteQuoteUseCase;

/**
 * @author Abel David.
 */
class DeleteDesignUseCase
{
    /**
     * @param DeleteQuoteUseCase $deleteQuoteUseCase
     */
    public function __construct(
        private readonly DeleteQuoteUseCase $deleteQuoteUseCase
    )
    {
        //
    }

    /**
     * @param Design $design
     * @return bool
     */
    public function __invoke(Design $design): bool
    {
        DeletingDesign::dispatch($design);

        $quote = $design->quote;

        $design->media()->delete();

        $deleted = $design->delete();

        ($this->deleteQuoteUseCase)($quote);

        if ($deleted) {

            DesignDeleted::dispatch($design);
        }

        return $deleted;
    }
}
