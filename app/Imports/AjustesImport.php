<?php

namespace App\Imports;

use Modules\Inventories\Entities\Reason;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;


class AjustesImport implements ToModel, WithHeadingRow, WithValidation,SkipsEmptyRows
{
    use Importable;


    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $reason = new Reason([
            'name' => strval($row['nombre']),
            'active' => 1,
            'created_by' => auth()->user()->getAuthIdentifier(),
            'updated_by' => auth()->user()->getAuthIdentifier()

        ]);

        return $reason;
    }

    public function rules(): array
    {
        return [
           'nombre' => [
                'required',
                'unique:reasons,name',
                'alpha_dash:ascii',

            ],
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
