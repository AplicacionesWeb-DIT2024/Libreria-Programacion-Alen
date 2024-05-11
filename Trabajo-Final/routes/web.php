<?php

use App\Http\Controllers\LibroController;
use App\Http\Controllers\LoginController;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function() {
    return view('sidebar');
}) -> name('home');

Route::controller(LoginController::class) -> group(function() {

    Route::post('/login', 'loguear');
    Route::get('/login', 'show');
});


//Primera forma
//Route::get('libros', [LibroController::class, 'index']);



// Agrupa las rutas que seran gestionadas por un mismo controlador

Route::controller(LibroController::class) -> group(function() {
    Route::get('libros','index');
    Route::get('libros/create','create');
    Route::get('libros/{nombre_libro}','show');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
