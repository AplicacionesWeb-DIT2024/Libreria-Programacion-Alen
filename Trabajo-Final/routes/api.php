<?php

use App\Http\Controllers\FacturaController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    Route::post('crearPedido', [FacturaController::class, 'store']);
    Route::post('logoutApi', [AuthApiController::class, 'desloguearApi']);
});



Route::post('loginApi', [AuthApiController::class, 'loguearApi'])
    ->middleware('throttle:5,1');

Route::get('libros', [LibroController::class, 'index_api']);

Route::get('libros/{id}', [LibroController::class, 'show_api']);


Route::get('/usuarios/{id}', [UsuarioController::class, 'show_api']);

Route::post('/agregarUsuario', [UsuarioController::class, 'store_api']);





Route::get('facturas/', [FacturaController::class, 'index_api']);

Route::get('facturas/{id}', [FacturaController::class, 'show_api']);

Route::get('{cliente}/facturas', [UsuarioController::class, 'show_api_factura_cliente']);




Route::get('cron/cancelar-pedidos', function (Request $request) {
    $secret = config('services.cron.secret');

    if (! $secret || ! hash_equals("Bearer {$secret}", (string) $request->header('Authorization'))) {
        abort(401);
    }

    Artisan::call('pedidos:cancelar-pedidos-vencidos');

    return response()->json(['salida' => trim(Artisan::output())]);
});


Route::get('/cron/prueba', function () {
    return response()->json([
        'mensaje' => 'Laravel funciona en producción',
        'entorno' => app()->environment(),
        'fecha' => now()->toDateTimeString()
    ]);
});