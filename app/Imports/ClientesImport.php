<?php

namespace App\Imports;

use Modules\Client\Entities\Client;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Validators\Failure;


class ClientesImport implements ToModel, WithHeadingRow, WithValidation
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

        if(!array_filter($row)) {
            $error = ['categoria' => 'La categoría no existe'];
            $failures[] = new Failure($rownumber, 'categoria', $error, $row1);
        throw new \Maatwebsite\Excel\Validators\ValidationException(
            \Illuminate\Validation\ValidationException::withMessages($error),
            $failures );
        }

             return new Client([
            'name' => strval($row['nombre']),
            'active' => 1,
            'ruc' => strval($row['ruc']),
            'dv' => strval($row['dv']),
            'no_taxes' => 0,
            'phone' => $row['telefono'] ? strval($row['telefono']) : null,
            'mobile' => $row['movil'] ? strval($row['movil']) : null,
            'email' => strval($row['correo_electronico']),
            'address' => $row['direccion'] ? strval($row['direccion']) : null,
            'legal_representative' => $row['nombre_representante_legal'] ? strval($row['nombre_representante_legal']) : null,
            'cedula' => $row['cedula_representante_legal'] ? strval($row['cedula_representante_legal']) : null,
            'contacts' => null,
            'credit' => 0.00,
            'created_by' => auth()->user()->getAuthIdentifier(),

        ]);
    }

    public function rules(): array
    {
        return [
           'nombre' => [
                'required',
                'unique:clients,name',
                'string',
            ],
            'ruc' => [
                'required',
            ],
            'dv' => [
                'required','numeric', 'digits:2','min:00', 'max:99'
            ],
            'correo_electronico' => [
                'required',
                'email'
            ],
            'telefono' => ['nullable', 'integer'],
            'movil' => ['nullable', 'integer'],
            'nombre_representante_legal' => ['nullable', 'string'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'correo_electronico.required' => 'El campo correo electrónico es requerido',
            'correo_electronico.email' => 'Debe introducir una cuenta de correo válida',
            'telefono.integer' => 'El campo teléfono debe tener valores númericos',
            'movil.integer' => 'El campo móvil debe tener valores númericos',
            'nombre_representante_legal.string' => 'El campo nombre representante legal debe ser una cadena de caracteres',

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
