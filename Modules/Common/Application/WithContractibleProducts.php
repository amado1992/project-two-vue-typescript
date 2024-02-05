<?php

namespace Modules\Common\Application;

use Closure;
use Modules\Common\Entities\HasContractibleProducts;
use Modules\Products\Entities\Product;

/**
 * @author Abel David.
 */
trait WithContractibleProducts
{
    /**
     * @param HasContractibleProducts $model
     * @param array $products
     * @param bool $tax_exempt
     * @param Closure|null $productCallback
     * @return bool
     */
    protected function syncContractibleProducts(
        HasContractibleProducts $model,
        array $products,
        bool $tax_exempt = false,
        ?Closure $productCallback = null
    ): bool
    {
        $data = [];

        foreach ($products as $product) {

            $productModel = Product::findOrFail($product['id']);

            if ($productCallback) {

                $productCallback($productModel, $product);
            }

            $tax = $tax_exempt ? 0 : $productModel->tax;

            if ($tax > 0) {
                $tax = $product['price'] * $tax / 100;
                $tax = $tax * $product['quantity'];
            }

            $subtotal = $product['price'] * $product['quantity'];

            $discountValue = $subtotal * $product['discount'] / 100;
            $subtotal -= $discountValue;

            $data[$product['id']] = [
                'price' => $product['price'],
                'quantity' => $product['quantity'],
                'percent_discount' => $product['discount'],
                'discount' => $discountValue,
                'tax' => $tax,
                'subtotal' => $subtotal,
                'total' => $subtotal + $tax
            ];
        }

        $result = $model->products()->sync($data);

        return hasSyncChanges($result);
    }
}
