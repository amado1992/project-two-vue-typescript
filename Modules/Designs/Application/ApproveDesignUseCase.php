<?php

namespace Modules\Designs\Application;

use Modules\Designs\Entities\Design;
use Modules\Designs\Events\ApprovingDesign;
use Modules\Designs\Events\DesignApproved;
use Modules\Quotes\Application\ApproveQuoteUseCase;

/**
 * @author Abel David.
 */
class ApproveDesignUseCase
{
    /**
     * @param ApproveQuoteUseCase $approveQuoteUseCase
     */
    public function __construct(
        private readonly ApproveQuoteUseCase $approveQuoteUseCase
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
        if ($design->quote->approved) {

            return false;
        }

        ApprovingDesign::dispatch($design);

        if (($this->approveQuoteUseCase)($design->quote)) {

            DesignApproved::dispatch($design);

            return true;
        }

        return false;
    }
}
