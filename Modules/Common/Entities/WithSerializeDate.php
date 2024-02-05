<?php

namespace Modules\Common\Entities;

use Carbon\CarbonImmutable;
use DateTimeImmutable;
use DateTimeInterface;
use Illuminate\Support\Carbon;

trait WithSerializeDate
{
    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date instanceof DateTimeImmutable ?
            CarbonImmutable::instance($date)->toDateTimeString() :
            Carbon::instance($date)->toDateTimeString();
    }
}
