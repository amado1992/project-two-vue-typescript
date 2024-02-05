<?php

namespace Modules\Brands\Application;

use Modules\Brands\Entities\Brand;
use Modules\Brands\Events\BrandDeleted;
use Modules\Brands\Events\DeletingBrand;

/**
 * @author cheynerpb.
 */
class DeleteBrandUseCase
{
    public function __invoke(Brand $brand): bool
    {
        DeletingBrand::dispatch($brand);

        $brand->delete();

        BrandDeleted::dispatch($brand);

        return true;
    }
}
