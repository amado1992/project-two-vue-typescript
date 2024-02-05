<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Modules\Reports\Application\GetContractsByClientUseCase;

class ContractsClientPerSheet implements WithMultipleSheets, ShouldAutoSize
{
    use Exportable;

    private $clients;
    private $products;
    private $projects;

    public function __construct($clients, $products, $projects)
    {
        $this->clients = $clients;
        $this->products = $products;
        $this->projects = $projects;
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new AllContractsClientsExport($this->clients, $this->projects);

        foreach ($this->clients as $client) {

            $getContractsByClientUseCase = new GetContractsByClientUseCase;

            // Muestro la hoja excel del cliente, 
            // solo si contiene datos, o sea, 
            // si tiene contratos. Revisar si contrato->project_id está en los proyectos filtrados.
            
            if ($client->projects->count() != 0) {
               
                $sheets[] = new ContractsExport($client, $this->clients, $this->products, $this->projects, $getContractsByClientUseCase);
            }

        }

        return $sheets;
    }
}
