<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/dashboard', [\Modules\Common\Http\Controllers\DashboardController::class, 'index'])
        ->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/download/{id}', function ($id) {
    $template = "";
    switch ($id) {
        case 1:
            $template = 'Plantilla-CategoriasProductos.xlsx';
            break;
        case 2:
            $template = 'Plantilla-Clientes.xlsx';
            break;
        case 3:
            $template = 'Plantilla-ConfiguracionesGenerales.xlsx';
            break;
        case 4:
            $template = 'Plantilla-DatosEmpresa.xlsx';
            break;
        case 5:
            $template = 'Plantilla-MotivosAjustes.xlsx';
            break;
        case 6:
            $template = 'Plantilla-Productos.xlsx';
            break;
        case 10:
            $template = 'Proveedores.xlsx';
            break;
        case 8:
            $template = 'Plantilla-Proyectos.xlsx';
            break;
        case 9:
            $template = 'Plantilla-Usuarios.xlsx';
            break;
        default:
            $template = 'unknown';
            break;

        };
           /* $file = public_path().'/downloads/'.$template;
            $headers = [
                //'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Type' =>'application/vnd.ms-excel',
                //'Content-Disposition' => 'attachment; filename="'.$template.'"',
            ];
            return  response()->download($file,'filename.xls', $headers);*/

    $headers = [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    return Storage::download($template,$template,$headers);

        });



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
