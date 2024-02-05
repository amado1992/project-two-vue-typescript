<?php

namespace Modules\Settings\Entities;

/**
 * @author Abel David.
 */
class ClausulesSettings extends Settings
{
    /**
     * @var string|null
     */
    public ?string $clausules;
public string $nombre_empresa_arrendador;
public string $ficha_inscripcion_arrendador;
public string $documento_redi_arrendador;
public string $direccion_empresa_arrendador;
public string $nombre_representante_arrendador;
public string $cedula_representante_arrendador;
public string $correo_representante_arrendador;
public string $telefono_representante_arrendador;

public string $nombre_empresa_arrendatario;
public string $ficha_inscripcion_arrendatario;
public string $documento_redi_arrendatario;
public string $direccion_empresa_arrendatario;
public string $nombre_representante_arrendatario;
public string $cedula_representante_arrendatario;
public string $correo_representante_arrendatario;
public string $telefono_representante_arrendatario;

    /**
     * @return string
     */
    protected function group(): string
    {
        return 'clausules';
    }
}
