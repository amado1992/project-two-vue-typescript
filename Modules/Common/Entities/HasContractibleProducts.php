<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @author Abel David.
 *
 * @property Collection $products
 */
interface HasContractibleProducts
{
    /**
     * @return BelongsToMany
     */
    function products(): BelongsToMany;
}
