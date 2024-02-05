<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Validators\Failure;
use Modules\Client\Entities\Client;
use Modules\Settings\Entities\GeneralSettings;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Modules\Users\Entities\User;


class GeneralesImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;
    private $numrow = 0;

    public function __construct(
        private readonly GeneralSettings $settings
    )
    {
        //
    }


    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $rownumber =  ++$this->numrow;

        $array_invoicing = [];
        $user = null;
        $slices = explode(",", $row['facturadores']);

        for ($i = 0; $i < count($slices); $i++) {
            $user = User::where('name', Str::upper($slices[$i]))->first();
            if ($user == null) {
              break;
            }
            $array_invoicing[] = $user->id;
        }

        if($user == null){
            $error = ['facturadores' => 'El facturador no existe'];
            $failures[] = new Failure($rownumber, 'facturadores', $error, $row);
            throw new \Maatwebsite\Excel\Validators\ValidationException(
                \Illuminate\Validation\ValidationException::withMessages($error),
                $failures );
        }

        if($row['impuesto_itbms']){

            $this->settings->tax_itbms = $row['impuesto_itbms'];
        }

        if($row['vencimiento_de_contrato']){

            $this->settings->expire_contract_notification = $row['vencimiento_de_contrato'];
        }

        if($row['facturadores']){

            $this->settings->billers = $array_invoicing;
        }

        $this->settings->save();

        return null;
    }

   public function rules(): array
    {
        return [
           'impuesto_itbms' => [
                'required',
                'numeric',
            ],
            'vencimiento_de_contrato' => [
                'required',
                'numeric',
            ],
        ];
    }

public function customValidationMessages(): array
{
    return [
        'impuesto_itbms.numeric' => 'El campo Impuesto ITBMS debe ser un número.',
        'impuesto_itbms.required' => 'El campo Impuesto ITBMS es obligatorio.',
        'vencimiento_de_contrato.required' => 'El campo Vencimiento de contrato es obligatorio.',
        'vencimiento_de_contrato.numeric' => 'El campo Vencimiento de contrato debe ser un número.'

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
