<?php

namespace Modules\Quotes\Application;

use Modules\Quotes\Entities\Quote;
use Modules\Quotes\Events\ApprovingQuote;
use Modules\Quotes\Events\QuoteApproved;

/**
 * @author Abel David.
 */
class ApproveQuoteUseCase
{
    /**
     * @param Quote $quote
     * @return bool
     */
    public function __invoke(Quote $quote): bool
    {
        ApprovingQuote::dispatch($quote);

        if ($quote->approved) {
            return false;
        }

        $quote->fill([
            'approved' => true
        ])->save();

        QuoteApproved::dispatch($quote);

        return true;
    }
}
