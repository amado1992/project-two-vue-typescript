<?php

namespace App\Imports;

use Maatwebsite\Excel\Validators\Failure;
use Modules\ProductCategories\Entities\ProductCategory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;


class CategoriaProductosImport implements ToModel, WithHeadingRow, WithValidation
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

        if(empty($row['categoria_padre'])){
            $category = NULL;
        }else{
            $categoria = ProductCategory::where('name', $row['categoria_padre'])->first();

            if($categoria==null){
                $error = ['categoria_padre' => 'La categoría padre no existe'];
                $failures[] = new Failure($rownumber, 'team', $error, $row);
                throw new \Maatwebsite\Excel\Validators\ValidationException(
                    \Illuminate\Validation\ValidationException::withMessages($error),
                    $failures );
            }

            $category = $categoria->id;
        }
         return new ProductCategory([
            'name' => strval($row['nombre']),
            'active' => 1,
            'product_category_id' => $category,
            'created_by' => auth()->user()->getAuthIdentifier(),

        ]);
    }

    public function rules(): array
    {
        return [
           'nombre' => [
                'required',
                'string',
                'unique:product_categories,name'
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
