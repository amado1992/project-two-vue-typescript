<?php

namespace Modules\Quotes\Application;

use Modules\Quotes\Entities\Quote;
use Modules\Quotes\Events\DeletingQuote;
use Modules\Quotes\Events\QuoteDeleted;

/**
 * @author Abel David.
 */
class DeleteQuoteUseCase
{
    /**
     * @param Quote $quote
     * @return bool
     */
    public function __invoke(Quote $quote): bool
    {
        DeletingQuote::dispatch($quote);

        $deleted = $quote->delete() == true;

        if ($deleted) {

            QuoteDeleted::dispatch($quote);
        }

        return $deleted;
    }
}
