<?php

namespace App\Imports;

use Illuminate\Support\Str;
use Modules\ProductCategories\Entities\ProductCategory;
use Modules\ProductCategories\Events\CreatingProductCategory;
use Modules\ProductCategories\Events\ProductCategoryCreated;
use Modules\Products\Entities\Product;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductosImport implements ToCollection, SkipsEmptyRows, WithHeadingRow, WithValidation
{
    use Importable;

    private $numrow = 0;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function collection(Collection $rows)
    {
        $rownumber =  ++$this->numrow;

        foreach ($rows as $row)
        {

            $tipo='';
        if ($row['tipo_servicio_o_producto'] &&  strtolower($row['tipo_servicio_o_producto'])=='producto'){
            $tipo='product';
        }elseif($row['tipo_servicio_o_producto'] &&  strtolower($row['tipo_servicio_o_producto'])=='servicio'){
            $tipo='service';
        }else{
            $tipo='product';
        }
        $row1 = [];
        $categoria = ProductCategory::where('name', $row['categoria'])->first();
        $cat = $categoria->id;

        /*if($categoria==null){
            $error = ['categoria' => 'La categoría no existe'];

            $failures[] = new Failure($rownumber, 'categoria', $error, $row1);
        throw new \Maatwebsite\Excel\Validators\ValidationException(
            \Illuminate\Validation\ValidationException::withMessages($error),
            $failures );
        }else{
            $cat = $categoria->id;
        }*/
            if($categoria == null){
                CreatingProductCategory::dispatch();

                $productCategory = ProductCategory::create([
                    'name' => $row['categoria'],
                    'active' => true,
                    'product_category_id' => null,
                    'created_by' => auth()->user()->getAuthIdentifier(),
                ]);

                ProductCategoryCreated::dispatch($productCategory);
                $cat = $productCategory->id;
            }

            $product = Product::query()->where('name','=',Str::upper(strval($row['nombre'])))->first();

            if($product == null) {
                $product_id = DB::table('products')->insertGetId([
                    'name' => strval($row['nombre']),
                    'active' =>true,
                    'product_category_id' => $cat,
                    'cost_price' => $row['precio_de_costo'] ? strval($row['precio_de_costo']) : 0,
                    'type' => $tipo,
                    'daily_price' => $row['precio_diario'] ? strval($row['precio_diario']) : 0,
                    'weekly_price' => $row['precio_semanal'] ? strval($row['precio_semanal']) : 0,
                    'biweekly_price' => $row['precio_quincenal'] ? strval($row['precio_quincenal']) : 0,
                    'monthly_price' => $row['precio_mensual'] ? strval($row['precio_mensual']) : 0,
                    'replacement_price' => $row['precio_de_reposicion'] ? strval($row['precio_de_reposicion']) : 0,
                    'tax' => $row['impuesto'] ? strval($row['impuesto']) : 0,
                    'created_at'=>date('Y-m-d h:i:s'),
                    'updated_at'=>date('Y-m-d h:i:s'),
                    'created_by' => auth()->user()->getAuthIdentifier()
                ]);

                DB::table('inventories')->insert([
                    'quantity' => 0,
                    'stock' => 0,
                    'rented' => 0,
                    're_quantity' => 0,
                    're_stock' => 0,
                    're_rented' => 0,
                    'product_id' => $product_id,
                    'created_at'=>date('Y-m-d h:i:s'),
                    'updated_at'=>date('Y-m-d h:i:s')
                ]);
            }else{
                $product_id=$this->updateProduct($product,$row,$cat,$tipo);
            }

        }
    }

    public function rules(): array
    {
        return [
           'nombre' => [
                'required',
                'string',
            ],
            'categoria' => [
                'required',
            ],
            'tipo_servicio_o_producto' => [
                'required',
                'string',
            ],
            'precio_diario' => [
                'numeric',

            ],
            'precio_semanal' => [
                'numeric',

            ],
            'precio_mensual' => [
                'numeric',

            ],
            'precio_quincenal' => [
                'numeric',

            ],
            'impuesto' => [
                'numeric',

            ],

        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'categoria.required' => 'El campo categoría es requerido',
            'tipo_servicio_o_producto.required' => 'El campo tipo es requerido',
            'tipo_servicio_o_producto.string' => 'El campo tipo debe ser una cadena de caracteres',
            'precio_diario.numeric' => 'El campo precio diario debe tener valores númericos',
            'precio_semanal.numeric' => 'El campo precio semanal debe tener valores númericos',
            'precio_mensual.numeric' => 'El campo precio mensual debe tener valores númericos',
            'precio_quincenal.numeric' => 'El campo precio quincenal debe tener valores númericos',

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

    private function updateProduct($product,$row,$cat,$tipo){

        DB::table('products')->where('id','=',$product->id)->update([
            'name' => strval($row['nombre']),
            'active' =>true,
            'product_category_id' => $cat,
            'cost_price' => $row['precio_de_costo'] ? strval($row['precio_de_costo']) : 0,
            'type' => $tipo,
            'daily_price' => $row['precio_diario'] ? strval($row['precio_diario']) : 0,
            'weekly_price' => $row['precio_semanal'] ? strval($row['precio_semanal']) : 0,
            'biweekly_price' => $row['precio_quincenal'] ? strval($row['precio_quincenal']) :  0,
            'monthly_price' => $row['precio_mensual'] ? strval($row['precio_mensual']) :  0,
            'replacement_price' => $row['precio_de_reposicion'] ? strval($row['precio_de_reposicion']) :  0,
            'tax' => $row['impuesto'] ? strval($row['impuesto']) :  0,
            'updated_at'=>date('Y-m-d h:i:s'),
            'updated_by' => auth()->user()->getAuthIdentifier()
        ]);

        return $product->id;
    }
}
