<?php

namespace Modules\Designs\Application;

use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Modules\Client\Entities\Client;
use Modules\Designs\Entities\Design;
use Modules\Projects\Entities\Project;
use Modules\Quotes\Entities\Quote;

class DespieceUseCaseCreate
{
    public function __invoke($data): Dompdf|\Barryvdh\DomPDF\PDF
    {
        $client = Client::query()->where('id', '=', $data['client_id'])->first();
        $project = Project::query()->where('id', '=', $data['project_id'])->first();
        $client_name = "";
        $project_name = "";
        $project_address = "";
        if($client != null){
            $client_name = $client->name;
        }
        if($project != null){
            $project_name = $project->name;
            $project_address = $project->address;
        }


        return Pdf::loadView('reports::despiece.desingdespiececreatePdf', [
            'title'=> 'Lista de despiece',
            'client_name' => $client_name,
            'project_name'=> $project_name,
            'project_address'=> $project_address,
            'products' => $data['products'],
        ])->setPaper('a4', 'portrait');
    }
}
