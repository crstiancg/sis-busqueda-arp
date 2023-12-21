<?php

use App\Http\Controllers\Anterior2Controller;
use App\Http\Controllers\AnteriorController;
use App\Http\Controllers\ArbolitoController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\EscrituraController;
use App\Http\Controllers\FavorecidoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FolioController;
use App\Http\Controllers\GenerateListController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\NotarioController;
use App\Http\Controllers\Nuevo2Controller;
use App\Http\Controllers\NuevoController;
use App\Http\Controllers\OtorganteController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\PrecioController;
use App\Http\Controllers\RegistroBusquedaController;
use App\Http\Controllers\RegistroVerificacionController;
use App\Http\Controllers\SiaController;
use App\Http\Controllers\Sis20182Controller;
use App\Http\Controllers\Sis2018Controller;
use App\Http\Controllers\SolicitanteController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\SubSerieController;
use App\Http\Controllers\UbigeoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::middleware('auth:api')->get('/user', function (Request $request) {
    $roles = $request->user()->roles;
    $permisos = [];
    foreach ($roles as $rol) {

        # code...
        $permisos = array_merge($permisos, $rol->permissions->pluck('name')->toArray());

        // $permisos=$rol->permissions->pluck('name');
    }
    $permisos = array_values(array_unique($permisos));

    return response()->json([
        'user' => $request->user(),
        // 'rolesSelected' => $request->user()->roles,
        'permisos' => $permisos,
        'roles' => $request->user()->roles->pluck('name'),
    ]);
});

Route::get('ubigeo', [UbigeoController::class, 'getUbigeo']);
Route::get('departamentos', [UbigeoController::class, 'getDepartamentos']);
Route::get('provincias', [UbigeoController::class, 'getProvincias']);
Route::get('distritos', [UbigeoController::class, 'getDistritos']);

Route::apiResource('/folios', FolioController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::apiResource('/usuarios', UserController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::apiResource('/roles', RoleController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::apiResource('/permisos', PermisoController::class)->middleware([HandlePrecognitiveRequests::class]);

Route::apiResource('/anteriores', AnteriorController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::apiResource('/anteriores2', Anterior2Controller::class)->middleware([HandlePrecognitiveRequests::class]);
Route::apiResource('/sis2018', Sis2018Controller::class)->middleware([HandlePrecognitiveRequests::class]);
Route::apiResource('/sis2018_2', Sis20182Controller::class)->middleware([HandlePrecognitiveRequests::class]);
Route::apiResource('/nuevos', NuevoController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::apiResource('/nuevos2', Nuevo2Controller::class)->middleware([HandlePrecognitiveRequests::class]);
Route::apiResource('/arbolitos', ArbolitoController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::apiResource('/sia', SiaController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::get('/generatelist',[GenerateListController::class,'index']);
Route::get('/generatetableall',[GenerateListController::class,'generateTableAll']);

Route::apiResource('/notarios', NotarioController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::apiResource('/subseries', SubSerieController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::apiResource('/areas', AreaController::class)->middleware([HandlePrecognitiveRequests::class]);

Route::apiResource('/libros', LibroController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::apiResource('/escrituras', EscrituraController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::apiResource('/otorgantes', OtorganteController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::apiResource('/favorecidos', FavorecidoController::class)->middleware([HandlePrecognitiveRequests::class]);

Route::apiResource('/solicitantes', SolicitanteController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::get('/solicitantes/dni/{dni}', [SolicitanteController::class, 'getSolicitanteDni']);

Route::apiResource('/precios', PrecioController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::apiResource('/solicitudes', SolicitudController::class)->middleware([HandlePrecognitiveRequests::class]);

Route::apiResource('/registro_busquedas', RegistroBusquedaController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::apiResource('/registro_verificaciones', RegistroVerificacionController::class)->middleware([HandlePrecognitiveRequests::class]);
