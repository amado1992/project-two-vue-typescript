<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Modules\Companies\Entities\Company;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;


class EmpresasImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts,SkipsEmptyRows
{
    use Importable;


    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        return new Company([
            'name' => strval($row['nombre']),
            'active' => 1,
            'ruc' => strval($row['ruc']),
            'dv' => strval($row['dv']),
            'email' => strval($row['correo_electronico']),
            'address' => strval($row['direccion']),
            'social_reason' => strval($row['razon_social']),
            'created_by' => auth()->user()->getAuthIdentifier(),

        ]);
    }

    public function rules(): array
    {
        return [
           'nombre' => [
                'required',
                //'unique:companies,name',
                'string',
            ],
            'ruc' => [
                'numeric',
            ],
            'dv' => [
                'numeric', 'digits:2','min:00', 'max:99'
            ],
            'correo_electronico' => [
                'email',
                'required',
            ],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'correo_electronico.required' => 'El campo correo electrónico es requerido',
            'correo_electronico.email' => 'Debe introducir una cuenta de correo válida',
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

    public function batchSize(): int
    {
        return 1;
    }
}
