<?php

namespace Modules\Brands\Application;

use Illuminate\Support\Collection;
use Modules\Brands\Entities\Brand;

/**
 * @author cheynerpb.
 */
class ReadBrandsUseCase
{
    /**
     * @return Collection<Brand>
     */
    public function __invoke(): Collection
    {
        return Brand::orderBy('name')->get();
    }
}
