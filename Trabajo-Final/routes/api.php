<?php

use App\Http\Controllers\FacturaController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//TODO: Verificar APIS y ponerle proteccion

Route::get('libros', [LibroController::class, 'index_api']);

Route::get('libros/{id}', [LibroController::class, 'show_api']);


Route::get('/usuarios/{id}', [UsuarioController::class, 'show_api']);

Route::post('/agregarUsuario', [UsuarioController::class, 'store_api']);


Route::post('agregarFactura', [FacturaController::class, 'store']);

Route::get('facturas/', [FacturaController::class, 'index_api']);

Route::get('facturas/{id}', [FacturaController::class, 'show_api']);

Route::get('{cliente}/facturas', [UsuarioController::class, 'show_api_factura_cliente']);
