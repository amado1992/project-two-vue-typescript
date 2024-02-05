<?php

namespace App\Imports;

use Modules\Providers\Entities\Provider;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProveedoresImport implements ToModel, SkipsEmptyRows, WithHeadingRow, WithValidation
{

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        return new Provider([
            'name' => strval($row['nombre']),
            'active' => 1,
            'ruc' => strval($row['ruc']),
            'dv' => strval($row['dv']),
            'no_taxes' => 0,
            'phone' => $row['telefono'] ? strval($row['telefono']) : null,
            'address' => $row['direccion'] ? strval($row['direccion']) : null,
            'contacts' => null,
            'created_by' => auth()->user()->getAuthIdentifier(),

        ]);
    }

    public function rules(): array
    {
        return [
           'nombre' => [
                'required',
                'unique:providers,name',
                'string',
            ],
            'telefono' => [
                'required',
                'integer',
            ],
            'ruc' => [
                'required',
            ],
            'dv' => [
                'required','numeric', 'digits:2','min:00', 'max:99'
            ],
            'direccion' => [
                'required',
                'string',
            ],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'direccion.required' => 'El campo dirección es requerido',
            'telefono.required' => 'El campo teléfono es requerido',
            'telefono.integer' => 'El campo teléfono debe tener valores númericos',
            'direccion.string' => 'El campo dirección debe ser una cadena',
        ];
    }



    public function headingRow(): int
    {
        return 1;
    }

    public function skipRows(): int
    {
        return 1;
    }
}
