<?php

namespace App\Imports;

use Modules\Client\Entities\Client;
use Modules\Roles\Entities\Role;
use Modules\Users\Entities\User;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Hash;


class UsuariosImport implements ToModel, WithHeadingRow, WithValidation
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

        $role = Role::where('name', $row['rol'])->first();


            if($role==null){
                $error = ['rol' => 'El rol no existe'];
                $failures[] = new Failure($rownumber, 'rol', $error, $row);
            throw new \Maatwebsite\Excel\Validators\ValidationException(
                \Illuminate\Validation\ValidationException::withMessages($error),
                $failures );
            }else{
                $rol = $role->name;
            }

        if($row['pertenece_a_cliente']){
            $cliente = Client::where('name', $row['pertenece_a_cliente'])->first();

            if($cliente==null){
                $error = ['cliente' => 'El cliente no existe'];
                $failures[] = new Failure($rownumber, 'team', $error, $row);
            throw new \Maatwebsite\Excel\Validators\ValidationException(
                \Illuminate\Validation\ValidationException::withMessages($error),
                $failures );
            }else{
                $idcliente = $cliente->id;
            }
        }else{
            $idcliente = null;
        }

        $user = new User([
            'name' => strval($row['nombre']),
            'lastname' => strval($row['apellidos']),
            'password' => Hash::make($row['contrasena']),
            'email' => strval($row['correo_electronico']),
            'client_id' => $idcliente,
            'created_by' => auth()->user()->getAuthIdentifier(),
        ]);
        $user->assignRole($rol);

        return $user;
    }

    public function rules(): array
    {
        return [
           'nombre' => [
                'required',
                'string',
            ],
            'rol' => [
                'required',
                'string',
            ],
            'apellidos' => [
                'required',
                'string',
            ],
            'contrasena' => [
                'required',
            ],

            'correo_electronico' => [
                'required',
                'unique:users,email',
                'string',
            ],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'correo_electronico.required' => 'El campo correo electrónico es requerido',
            'correo_electronico.email' => 'Debe introducir una cuenta de correo válida',
            'correo_electronico.unique' => 'Este correo ya existe en nuestro sistema',
            'contrasena.required' => 'El campo contraseña es requerido'
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
