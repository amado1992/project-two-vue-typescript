<?php

namespace App\Imports;

use Illuminate\Support\Str;
use Maatwebsite\Excel\Validators\Failure;
use Modules\Projects\Entities\Project;
use Modules\Client\Entities\Client;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithUpserts;


class ProyectosImport implements ToModel, WithHeadingRow, WithValidation, WithUpserts
{
    use Importable;
    private $numrow = 0;


    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $rownumber =  ++$this->numrow;
        $cliente = Client::where('name', $row['cliente'])->first();
        $idcliente = null;

        if($cliente==null){
            $error = ['cliente' => 'El cliente no existe'];
            $failures[] = new Failure($rownumber, 'cliente', $error, $row);
        throw new \Maatwebsite\Excel\Validators\ValidationException(
            \Illuminate\Validation\ValidationException::withMessages($error),
            $failures );
        }else{
            $idcliente = $cliente->id;
        }
        return new Project([
            'name' => Str::upper(strval($row['nombre'])),
            'address' => strval($row['direccion']),
            'project_manager' => Str::upper(strval($row['nombre_encargado_proyecto'])),
            'project_manager_phone' => strval($row['telefono_encargado_proyecto']),
            'construction_manager' => Str::upper(strval($row['nombre_encargado_obra'])),
            'construction_manager_phone' => strval($row['telefono_encargado_obra']),
            'in_charge_to_pay' => Str::upper(strval($row['nombre_encargado_cuentas_por_pagar'])),
            'in_charge_to_pay_phone' => strval($row['telefono_encargado_cuentas_por_pagar']),
            'client_id' => $idcliente,
            'active' => 1,
            'created_by' => auth()->user()->getAuthIdentifier(),
        ]);
    }

    public function rules(): array
    {
        return [
           'nombre' => [
                'required',
                'unique:projects,name',
                'string',
            ],
            'direccion' => [
                'required',
                'string',
            ],
            'nombre_encargado_proyecto' => [
                'required',
                'string',
            ],
            'telefono_encargado_proyecto' => [
                'required',
                'integer',
            ],
            'nombre_encargado_obra' => [
                'required',
                'string',
            ],
            'telefono_encargado_obra' => [
                'required',
                'integer',
            ],
            'nombre_encargado_cuentas_por_pagar' => [
                'required',
                'string',
            ],
            'telefono_encargado_cuentas_por_pagar' => [
                'required',
                'integer',
            ],
        ];
    }

    public function uniqueBy()
    {
        return 'name';
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function skipRows(): int
    {
        return 1;
    }

    public function customValidationMessages(): array
    {
        return [
            'nombre_encargado_proyecto.required' => 'El campo nombre encargado proyecto es requerido',
            'nombre_encargado_proyecto.string' => 'El campo nombre encargado proyecto debe ser una cadena de caracteres',
            'telefono_encargado_proyecto.required' => 'El campo télefono encargado proyecto es requerido',
            'telefono_encargado_proyecto.integer' => 'El campo télefono encargado proyecto debe ser númerico',
            'nombre_encargado_obra.required' => 'El campo nombre encargado obra es requerido',
            'nombre_encargado_obra.string' => 'El campo nombre encargado obra debe ser una cadena de caracteres',
            'telefono_encargado_obra.required' => 'El campo télefono encargado obra es requerido',
            'telefono_encargado_obra.integer' => 'El campo télefono encargado obra debe ser una cadena de caracteres',
            'nombre_encargado_cuentas_por_pagar.required' => 'El campo nombre encargado cuentas por pagar es requerido',
            'nombre_encargado_cuentas_por_pagar.string' => 'El campo nombre encargado cuentas por pagar debe ser númerico',
            'telefono_encargado_cuentas_por_pagar.required' => 'El campo télefono encargado cuentas por pagar es requerido',
            'telefono_encargado_cuentas_por_pagar.integer' => 'El campo télefono encargado cuentas por pagar debe ser númerico',


        ];
    }
}
