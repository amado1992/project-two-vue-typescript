<?php

namespace Modules\Common\Helpers;

/**
 * @author cheynerpb.
 */
enum ProductType
{
    case Service;
    case Product;
    public function toString(): string
    {
        return match ($this) {

            ProductType::Service => 'service',
            ProductType::Product => 'product'
        };
    }
}
