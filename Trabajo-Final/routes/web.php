<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaisController;
use App\Http\Controllers\SubcategoriaController;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function() {
    return view('home');
}) -> name('home');

//Login

Route::controller(LoginController::class) -> group(function() {

    Route::post('/login', 'loguear');
    Route::get('/login', 'show');
});

//Categorias

Route::get('categorias', [CategoriaController::class, 'index']) -> name('categorias.index');

Route::get('categorias/create', [CategoriaController::class, 'create']) -> name('categorias.create');

Route::post('categorias/store', [CategoriaController::class, 'store']) -> name('categorias.store');

Route::get('categorias/{categoria}/edit', [CategoriaController::class, 'edit']) -> name('categorias.edit');

Route::put('categorias/{categoria}', [CategoriaController::class, 'update']) -> name('categorias.update');

Route::delete('categorias/{categoria}', [CategoriaController::class, 'delete']) -> name('categorias.delete');

//Subcategorias

//Listar

Route::get('subcategorias', [SubcategoriaController::class, 'index']) -> name('subcategorias.index');

//Mostrar vista

Route::get('subcategorias/create', [SubcategoriaController::class, 'create']) -> name('subcategorias.create');

//Agregar subcategoria

Route::post('subcategorias/store', [SubcategoriaController::class, 'store']) -> name('subcategorias.store');

//Editar subcategoria

Route::get('subcategorias/{subcategoria}/edit', [SubcategoriaController::class, 'edit']) -> name('subcategorias.edit');

Route::put('subcategorias/{subcategoria}', [SubcategoriaController::class, 'update']) -> name('subcategorias.update');

//Eliminar subcategoria

Route::delete('subcategorias/{subcategoria}', [SubcategoriaController::class, 'delete']) -> name('subcategorias.delete');


//Paises

Route::get('paises', [PaisController::class, 'index']) -> name('paises.index');

Route::get('paises/create', [PaisController::class, 'create']) -> name('paises.create');

Route::post('paises/store', [PaisController::class, 'store']) -> name('paises.store');

//Editar pais

Route::get('paises/{pais}/edit', [PaisController::class, 'edit']) -> name('paises.edit');

Route::put('paises/{pais}', [PaisController::class, 'update']) -> name('paises.update');

//Eliminar pais

Route::delete('paises/{pais}', [PaisController::class, 'delete']) -> name('paises.delete');



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
