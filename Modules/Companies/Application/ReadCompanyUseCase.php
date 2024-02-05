<?php

namespace Modules\Companies\Application;

use Illuminate\Support\Collection;
use Modules\Companies\Entities\Company;

/**
 * @author cheynerpb.
 */
class ReadCompanyUseCase
{
    /**
     * @return Company
     */
    public function __invoke(): Company|null
    {
        return Company::all()->last();
    }
}
