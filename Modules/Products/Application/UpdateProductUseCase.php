<?php

namespace Modules\Products\Application;

use Modules\Products\Entities\Product;
use Modules\Products\Events\ProductUpdated;
use Modules\Products\Events\UpdatingProduct;
use Modules\Products\Http\Requests\UpdateProductRequest;

/**
 * Update a product.
 *
 * @author cheynerpb.
 */
class UpdateProductUseCase
{
    /**
     * @param UpdateProductRequest $request
     * @param Product $product
     * @return bool
     */
    public function __invoke(UpdateProductRequest $request, Product $product): bool
    {
        $oldProduct = clone $product;

        $product->forceFill([
            'name' => $request->input('name'),
            'active' => (bool)$request->input('active'),
            'product_category_id' => $request->input('product_category_id'),
            'type' => $request->input('type'),
            'cost_price' => $request->input('cost_price'),
            'daily_price' => $request->input('daily_price'),
            'weekly_price' => $request->input('weekly_price'),
            'biweekly_price' => $request->input('biweekly_price'),
            'monthly_price' => $request->input('monthly_price'),
            'replacement_price' => $request->input('replacement_price'),
            'tax' => $request->input('tax')
        ]);

        if ($product->isDirty()) {

            $product->fill([
                'updated_by' => auth()->user()->getAuthIdentifier()
            ]);

            UpdatingProduct::dispatch($product);
        }

        $product->save();

        if ($product->wasChanged()) {

            ProductUpdated::dispatch($product, $oldProduct);

            return true;
        }

        return false;
    }
}
