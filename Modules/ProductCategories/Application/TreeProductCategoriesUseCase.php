<?php

namespace Modules\ProductCategories\Application;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Modules\ProductCategories\Entities\ProductCategory;

/**
 * @author cheynerpb.
 */
class TreeProductCategoriesUseCase
{

    /**
     * @return Collection<\stdClass>
     */
    public function __invoke(ReadProductCategoriesUseCase $readProductCategoriesUseCase): Collection
    {
        return $this->nodeCategories($readProductCategoriesUseCase);
    }

    /**
     * Create product categories tree.
     *
     * @param ReadProductCategoriesUseCase $readProductCategoriesUseCase
     * @return Collection
     */
    private function nodeCategories(ReadProductCategoriesUseCase $readProductCategoriesUseCase): Collection
    {
        $productCategories = $readProductCategoriesUseCase(false, true);

        $results = collect();

        foreach ($productCategories as $key => $category) {
            $related = ProductCategory::where('product_category_id', $category->id)->get();

            $obj = new \stdClass();
            $obj->label = $category->name;

            $childrens = [];
            foreach ($related as $index => $item) {
                $categorySon = new \stdClass();
                $categorySon->label = $item->name;

                $childrens[] = $categorySon;
            }

            $obj->children = $childrens;

            $results->push($obj);
        }

        return $results;
    }
}


