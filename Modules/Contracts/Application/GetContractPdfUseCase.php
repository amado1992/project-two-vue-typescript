<?php

namespace Modules\Contracts\Application;

use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Modules\Client\Entities\Client;
use Modules\Contracts\Entities\Contract;
use Modules\Settings\Entities\ClausulesSettings;

/**
 * @author Abel David.
 */
class GetContractPdfUseCase
{
    /**
     * @param ClausulesSettings $settings
     */
    public function __construct(
        private readonly ClausulesSettings $settings
    )
    {
        //
    }

    /**
     * @param Contract $contract
     * @return Dompdf|\Barryvdh\DomPDF\PDF
     */
    public function __invoke(Contract $contract): Dompdf|\Barryvdh\DomPDF\PDF
    {
        $client = Client::query()->where('id','=',$contract->project->client_id)->first();
        return Pdf::loadView('contracts::templates.contract_pdf', [
            'contract' => $contract,
            'clausules' => $this->getUpdatedClauseles($this->settings->clausules,$client)
        ])->setPaper('legal');
    }

    function nullOrString($value)
    {
        return $value != null ? $value : ' ';
    }

    function getUpdatedClauseles($clausules,$client) {
        $content = array(
        '[ARRENDADOR_NOMBREEMPRESA]' => '<b>'.$this->settings->nombre_empresa_arrendador.'</b>',
        '[ARRENDADOR_FICHAINSCRIPCION]' => '<b>'.$this->settings->ficha_inscripcion_arrendador.'</b>',
        '[ARRENDADOR_DOCUMENTOREDI]' => '<b>'.$this->settings->documento_redi_arrendador.'</b>',
        '[ARRENDADOR_DIRECCIONEMPRESA]' => '<b>'.$this->settings->direccion_empresa_arrendador.'</b>',
        '[ARRENDADOR_NOMBREREPRESENTANTE]' => '<b>'.$this->settings->nombre_representante_arrendador.'</b>',
        '[ARRENDADOR_CEDULAREPRESENTANTE]' => '<b>'.$this->settings->cedula_representante_arrendador.'</b>',
        '[ARRENDADOR_CORREOREPRESENTANTE]' => '<b>'.$this->settings->correo_representante_arrendador.'</b>',
        '[ARRENDADOR_TELEFONOREPRESENTANTE]' => '<b>'.$this->settings->telefono_representante_arrendador.'</b>',

        '[ARRENDATARIO_NOMBREEMPRESA]' => '<b>'.$this->nullOrString($client->name).'</b>',
        '[ARRENDATARIO_FICHAINSCRIPCION]' => '<b>'.$this->nullOrString($client->ficha).'</b>',
        '[ARRENDATARIO_DOCUMENTOREDI]' => '<b>'.$this->nullOrString($client->redi).'</b>',
        '[ARRENDATARIO_DIRECCIONEMPRESA]' => '<b>'.$this->nullOrString($client->address).'</b>',
        '[ARRENDATARIO_NOMBREREPRESENTANTE]' => '<b>'.$this->nullOrString($client->legal_representative).'</b>',
        '[ARRENDATARIO_CEDULAREPRESENTANTE]' => '<b>'.$this->nullOrString($client->cedula).'</b>',
        '[ARRENDATARIO_CORREOREPRESENTANTE]' => '<b>'.$this->nullOrString($client->email).'</b>',
        '[ARRENDATARIO_TELEFONOREPRESENTANTE]' => '<b>'.$client->phone.'ó' .$client->mobile.'</b>');

        return strtr($clausules,$content);
    }


}
