<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Modules\Reports\Application\GetReRentsProductsByProvider;


class ReRentProviderProductPerSheet implements WithMultipleSheets
{
    use Exportable;

    private $providers;


    public function __construct($providers)
    {
        $this->providers = $providers;

    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->providers as $provider) {


            $getReRentsProductsByProviderFunction = new GetReRentsProductsByProvider;
            $sheets[] = new ReRentProviderProductsExport($provider,$getReRentsProductsByProviderFunction($provider->id,null));

        }

        return $sheets;
    }
}
