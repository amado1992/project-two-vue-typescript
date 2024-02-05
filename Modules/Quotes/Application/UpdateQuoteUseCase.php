<?php

namespace Modules\Quotes\Application;

use Illuminate\Support\Carbon;
use Modules\Common\Application\WithContractibleProducts;
use Modules\Quotes\Entities\Quote;
use Modules\Quotes\Events\ApprovingQuote;
use Modules\Quotes\Events\QuoteApproved;
use Modules\Quotes\Events\QuoteUpdated;
use Modules\Quotes\Events\UpdatingQuote;

/**
 * @author Abel David.
 */
class UpdateQuoteUseCase
{
    use WithContractibleProducts;

    /**
     * @param array $data
     * @param Quote $quote
     * @return bool
     */
    public function __invoke(array $data, Quote $quote): bool
    {
        $approved = (bool) $data['approved'];

        $approvingQuote = false;

        if ($quote->approved != $approved && $approved) {

            ApprovingQuote::dispatch($quote);
            $approvingQuote = true;
        }

        $oldQuote = clone $quote;

        $quote->fill($data);

        if ($quote->isDirty()) {

            $quote->fill([
                'updated_by' => auth()->user()->getAuthIdentifier()
            ]);

            UpdatingQuote::dispatch($quote);
        }
        $quote->forceFill([
            'updated_by' => auth()->user()->getAuthIdentifier(),
            'updated_at' => Carbon::now()
        ]);
        $quote->save();

        if ($approvingQuote) {

            QuoteApproved::dispatch($quote);
        }

        $wasChanges = $quote->wasChanged();

        $wasChanges = $this->syncContractibleProducts($quote, $data['products'], $data['tax_exempt']) || $wasChanges;

        if ($wasChanges) {

            QuoteUpdated::dispatch($quote, $oldQuote);
        }

        return $wasChanges;
    }
}
