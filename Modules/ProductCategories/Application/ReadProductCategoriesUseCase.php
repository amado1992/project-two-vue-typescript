<?php

namespace Modules\ProductCategories\Application;

use Illuminate\Support\Collection;
use Modules\ProductCategories\Entities\ProductCategory;
use Modules\ProductCategories\Scopes\ActiveScope;

/**
 * @author cheynerpb.
 */
class ReadProductCategoriesUseCase
{

    /**
     * @return Collection<ProductCategory>
     */
    public function __invoke($active, $onlyFathers = null): Collection
    {
        $query = ProductCategory::orderBy('name');

        if($active) {
            $query = $query->active();
        }

        if(isset($onlyFathers)) {
            if($onlyFathers) {
                $query = $query->where('product_category_id', null);
            } else {
                $query = $query->where('product_category_id', '<>', null);
            }
        }


        return $query->with('father')->get();
    }}
