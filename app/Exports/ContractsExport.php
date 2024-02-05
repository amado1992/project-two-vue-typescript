<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithTitle;
use Modules\Reports\Application\GetContractsByClientUseCase;

class ContractsExport implements FromView, WithTitle, ShouldAutoSize, SkipsEmptyRows
{
    private $client;
    private $clients;
    private $products;
    private $projects;
    protected $getContractsByClientUseCase;

    public function __construct($client, $clients, $products, $projects, GetContractsByClientUseCase $getContractsByClientUseCase)
    {
        $this->client = $client;
        $this->clients = $clients;
        $this->products = $products;
        $this->projects = $projects;
        $this->getContractsByClientUseCase = $getContractsByClientUseCase($this->client);
    }

    public function view(): View
    {
    
        return view('reports::contracts.contracts', [
            'client' => $this->client,
            'clients' => $this->clients,
            'products' => $this->products,
            'projects' => $this->projects,
            'contracts' => $this->getContractsByClientUseCase

        ]);
    }

    public function title(): string
    {
        return ucfirst($this->client->name);
    }
}
