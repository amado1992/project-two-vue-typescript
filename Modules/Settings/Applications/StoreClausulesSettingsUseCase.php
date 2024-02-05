<?php

namespace Modules\Settings\Applications;

use Illuminate\Http\Request;
use Modules\Settings\Entities\ClausulesSettings;

/**
 * @author Abel David.
 */
class StoreClausulesSettingsUseCase
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
     * @param Request $request
     * @return void
     */
    public function __invoke(Request $request): void
    {
        $this->settings->clausules = $request->input('clausules');
        $this->settings->nombre_empresa_arrendador = $request->input('nombre_empresa_arrendador') ?? " ";
        $this->settings->ficha_inscripcion_arrendador = $request->input('ficha_inscripcion_arrendador') ?? " ";
        $this->settings->documento_redi_arrendador = $request->input('documento_redi_arrendador') ?? " ";
        $this->settings->direccion_empresa_arrendador = $request->input('direccion_empresa_arrendador') ?? " ";
        $this->settings->nombre_representante_arrendador = $request->input('nombre_representante_arrendador') ?? " ";
        $this->settings->cedula_representante_arrendador = $request->input('cedula_representante_arrendador') ?? " ";
        $this->settings->correo_representante_arrendador = $request->input('correo_representante_arrendador') ?? " ";
        $this->settings->telefono_representante_arrendador = $request->input('telefono_representante_arrendador') ?? " ";

        /*$this->settings->nombre_empresa_arrendatario = $request->input('nombre_empresa_arrendatario') ?? " ";
        $this->settings->ficha_inscripcion_arrendatario = $request->input('ficha_inscripcion_arrendatario') ?? " ";
        $this->settings->documento_redi_arrendatario = $request->input('documento_redi_arrendatario') ?? " ";
        $this->settings->direccion_empresa_arrendatario = $request->input('direccion_empresa_arrendatario') ?? " ";
        $this->settings->nombre_representante_arrendatario = $request->input('nombre_representante_arrendatario') ?? " ";
        $this->settings->cedula_representante_arrendatario = $request->input('cedula_representante_arrendatario') ?? " ";
        $this->settings->correo_representante_arrendatario = $request->input('correo_representante_arrendatario') ?? " ";
        $this->settings->telefono_representante_arrendatario = $request->input('telefono_representante_arrendatario') ?? " ";*/
        $this->settings->save();
    }
}
