<?php

namespace Modules\Travels\Http\Controllers;

use Modules\Common\Http\Controllers\Controller;
use Modules\Contracts\Entities\Contract;
use Modules\Reports\Application\GetTravelsAllUseCase;
use Modules\Reports\Application\GetTravelUseCase;
use Modules\Reports\Application\TemplateTravelsUseCase;
use Modules\Travels\Entities\Travel;
use Spatie\RouteAttributes\Attributes\Get;

/**
 * @author Amado Rafael.
 */

class TravelsExportTemplatesController extends Controller
{
    #[Get('travels/{contract}/template', 'travels.template.pdf')]
    public function download_template_travels(
        TemplateTravelsUseCase $useCase, Contract $contract

    ): \Illuminate\Http\Response {

        return $useCase($contract)->download('Plantilla de viaje');
    }

    #[Get('travels/{contract}/export_travels_all', 'travels.export_travels_all.pdf')]
    public function download_travels_all(
        GetTravelsAllUseCase $useCase, Contract $contract

    ): \Illuminate\Http\Response {

        return $useCase($contract)->download('Viajes');
    }

    #[Get('travels/{travel}/export_travel_one', 'travels.export_travel_one.pdf')]
    public function download_travel_one(
        GetTravelUseCase $useCase, Travel $travel

    ): \Illuminate\Http\Response {

        return $useCase($travel)->download('Viaje');
    }
}
