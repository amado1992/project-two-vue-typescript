<?php

namespace Modules\Quotes\Application;

use Modules\Common\Application\WithContractibleProducts;
use Modules\Quotes\Entities\Quote;
use Modules\Quotes\Events\CreatingQuote;
use Modules\Quotes\Events\QuoteCreated;

/**
 * @author Abel David.
 */
class CreateQuoteUseCase
{
    use WithContractibleProducts;

    /**
     * @param array $data
     * @return Quote
     */
    public function __invoke(array $data): Quote
    {
        CreatingQuote::dispatch();

        $user_id = auth()->user()->getAuthIdentifier();
        $data['user_id'] = $user_id;
        $data['created_by'] = $user_id;

        $quote = Quote::create($data);

        $this->syncContractibleProducts($quote, $data['products'], $data['tax_exempt']);

        QuoteCreated::dispatch($quote);

        return $quote;
    }
}
