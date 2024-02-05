<?php

namespace Modules\Quotes\Application;

use Modules\Quotes\Entities\Quote;

/**
 * @author Abel David.
 */
class GetQuoteCountUseCase
{
    /**
     * @return int
     */
    public function __invoke(): int
    {
        return Quote::query()
            ->count();
    }
}
