<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Modules\Reports\Application\ReadProductsDetailsUseCase;

class ProductsPerSheet implements WithMultipleSheets
{
    use Exportable;

    private $products;
    private $clients;
    private ReadProductsDetailsUseCase $readProductsDetailsUseCase;

    public function __construct($products, $clients, $readProductsDetailsUseCase)
    {
        $this->products = $products;
        $this->clients = $clients;
        $this->readProductsDetailsUseCase = $readProductsDetailsUseCase;
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new AllProductsExport($this->products);

        foreach ($this->products as $product) {

            if ($product['quantity'] != 0) {

                $sheets[] = new ProductsExport($product, $this->products, $this->clients, $this->readProductsDetailsUseCase);
            }
        }

        return $sheets;
    }
}
