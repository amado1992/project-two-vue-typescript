<?php

namespace Modules\Brands\Application;

use Modules\Brands\Entities\Brand;
use Modules\Brands\Events\BrandCreated;
use Modules\Brands\Events\CreatingBrand;
use Modules\Brands\Http\Requests\StoreBrandRequest;

/**
 * Create a Brand.
 *
 * @author cheynerpb.
 */
class CreateBrandUseCase
{
    /**
     * @param array $data
     * @return Brand
     */
    public function __invoke(array $data): Brand
    {
        CreatingBrand::dispatch();

        $brand = Brand::create([
            'name' => $data['name'],
            'active' => (bool) $data['active'],
            'created_by' => auth()->user()->getAuthIdentifier(),
        ]);

        BrandCreated::dispatch($brand);

        return $brand;
    }
}
