<?php

namespace Modules\ReRents\Application;

use Modules\ReRents\Entities\ReRent;

class StartRerentUseCase
{
    public function __invoke(ReRent $reRent): void
    {
        $reRent->fill([
            'active_at' => now(),
            'active_by' => auth()->user()->getAuthIdentifier()
        ])->save();
    }
}
