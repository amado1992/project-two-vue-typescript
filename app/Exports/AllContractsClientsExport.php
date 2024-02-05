<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class AllContractsClientsExport implements FromView, WithTitle, ShouldAutoSize
{
    private $clients;
    private $projects;
    public function __construct($clients, $projects)
    
    {
        $this->clients = $clients;
        $this->projects = $projects;
    }

    public function view(): View
    {
        return view('reports::contracts.allcontracts', [
            'clients' => $this->clients,
            'projects' => $this->projects
        ]);
    }

    public function title(): string
    {
        return ucfirst("Contratos - Clientes");
    }

}
