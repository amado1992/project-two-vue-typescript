<?php

namespace Modules\Products\Application;

use Modules\Products\Entities\Product;
use Modules\Products\Events\CreatingProduct;
use Modules\Products\Events\ProductCreated;
use Modules\Products\Http\Requests\StoreProductRequest;

/**
 * Create a Product.
 *
 * @author cheynerpb.
 */
class CreateProductUseCase
{
    /**
     * @param StoreProductRequest $request
     * @return Product
     */
    public function __invoke(StoreProductRequest $request): Product
    {
        CreatingProduct::dispatch();

        $product = Product::create([
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
            'tax' => $request->input('tax'),
            'created_by' => auth()->user()->getAuthIdentifier(),
        ]);

        ProductCreated::dispatch($product);

        return $product;
    }
}
