<?php

namespace Modules\Brands\Application;

use Modules\Brands\Entities\Brand;
use Modules\Brands\Events\BrandUpdated;
use Modules\Brands\Events\UpdatingBrand;
use Modules\Brands\Http\Requests\UpdateBrandRequest;

/**
 * Update a brand.
 *
 * @author cheynerpb.
 */
class UpdateBrandUseCase
{
    /**
     * @param UpdateBrandRequest $request
     * @param Brand $brand
     * @return bool
     */
    public function __invoke(UpdateBrandRequest $request, Brand $brand): bool
    {
        $brand->forceFill([
            'name' => $request->input('name'),
            'active' => (bool)$request->input('active'),
            'updated_by' => auth()->user()->getAuthIdentifier(),
        ]);

        if ($brand->isDirty()) {
            UpdatingBrand::dispatch($brand);
        }

        $brand->save();

        $wasChanged = $brand->wasChanged();

        if ($wasChanged) {

            BrandUpdated::dispatch($brand);
        }

        return $wasChanged;
    }
}
