<?php

use App\Http\Controllers\AutorController;
use App\Http\Controllers\BancoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\EditorialController;
use App\Http\Controllers\EntidadController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaisController;
use App\Http\Controllers\SubcategoriaController;
use App\Http\Controllers\UsuarioController;
use App\Models\Libro;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function() {
    return view('home');
}) -> name('home')->middleware('auth');

//Login

Route::controller(LoginController::class) -> group(function() {

    Route::post('/login', 'loguear');
    Route::get('/login', 'show') -> name('login');
    Route::post('/logout', 'desloguear')->name('logout');
    Route::get('/logout', 'desloguear');
});

//Categorias

Route::controller(CategoriaController::class) -> group(function() {
    
    Route::get('categorias', 'index') -> name('categorias.index')->middleware('auth');

    Route::get('categorias/create','create') -> name('categorias.create')->middleware('auth');
    
    Route::post('categorias/store', 'store') -> name('categorias.store')->middleware('auth');
    
    Route::get('categorias/{categoria}/edit', 'edit') -> name('categorias.edit')->middleware('auth');
    
    Route::put('categorias/{categoria}','update') -> name('categorias.update')->middleware('auth');
    
    Route::delete('categorias/{categoria}','delete') -> name('categorias.delete')->middleware('auth');
});



//Subcategorias

Route::controller(SubcategoriaController::class) -> group(function() {

    Route::get('subcategorias','index') -> name('subcategorias.index')->middleware('auth');
    
    Route::get('subcategorias/create', 'create') -> name('subcategorias.create')->middleware('auth');

    Route::post('subcategorias/store','store') -> name('subcategorias.store')->middleware('auth');

    Route::get('subcategorias/{subcategoria}/edit','edit') -> name('subcategorias.edit')->middleware('auth');

    Route::put('subcategorias/{subcategoria}', 'update') -> name('subcategorias.update')->middleware('auth');

    Route::delete('subcategorias/{subcategoria}', 'delete') -> name('subcategorias.delete')->middleware('auth');

});

//Paises

Route::controller(PaisController::class) -> group(function() {

    Route::get('paises', 'index') -> name('paises.index')->middleware('auth');

    Route::get('paises/create', 'create') -> name('paises.create')->middleware('auth');
    
    Route::post('paises/store', 'store') -> name('paises.store')->middleware('auth');
    
    //Editar pais
    
    Route::get('paises/{pais}/edit', 'edit') -> name('paises.edit')->middleware('auth');
    
    Route::put('paises/{pais}',  'update') -> name('paises.update')->middleware('auth');
    
    //Eliminar pais
    
    Route::delete('paises/{pais}', 'delete') -> name('paises.delete')->middleware('auth');
});


//Autores

Route::controller(AutorController::class) -> group(function() {

    Route::get('autores',  'index') -> name('autores.index')->middleware('auth');

    Route::get('autores/create',  'create') -> name('autores.create')->middleware('auth');

    Route::post('autores/store',  'store') -> name('autores.store')->middleware('auth');

    Route::get('autores/{autor}/edit',  'edit') -> name('autores.edit')->middleware('auth');

    Route::put('autores/{autor}',  'update') -> name('autores.update')->middleware('auth');

    Route::delete('autores/{autor}', 'delete') -> name('autores.delete')->middleware('auth');
});



//Bancos
Route::controller(BancoController::class)-> group(function() {

    Route::get('bancos', 'index') -> name('bancos.index')->middleware('auth');

    Route::get('bancos/create', 'create') -> name('bancos.create')->middleware('auth');
    
    Route::post('bancos/store',  'store') -> name('bancos.store')->middleware('auth');
    
    Route::get('bancos/{banco}/edit',  'edit') -> name('bancos.edit')->middleware('auth');
    
    Route::put('bancos/{banco}',  'update') -> name('bancos.update')->middleware('auth');
    
    Route::delete('bancos/{banco}',  'delete') -> name('bancos.delete')->middleware('auth');
});


//Editoriales
Route::controller(EditorialController::class)-> group(function() {

    Route::get('editoriales',  'index') -> name('editoriales.index')->middleware('auth');

    Route::get('editoriales/create', 'create') -> name('editoriales.create')->middleware('auth');
    
    Route::post('editoriales/store', 'store') -> name('editoriales.store')->middleware('auth');
    
    Route::get('editoriales/{editorial}/edit', 'edit') -> name('editoriales.edit')->middleware('auth');
    
    Route::put('editoriales/{editorial}', 'update') -> name('editoriales.update')->middleware('auth');
    
    Route::delete('editoriales/{editorial}',  'delete') -> name('editoriales.delete')->middleware('auth');
});



//Entidades

Route::controller(EntidadController::class)-> group(function() {

    Route::get('entidades',  'index') -> name('entidades.index')->middleware('auth');

    Route::get('entidades/create', 'create') -> name('entidades.create')->middleware('auth');
    
    Route::post('entidades/store', 'store') -> name('entidades.store')->middleware('auth');
    
    Route::get('entidades/{entidad}/edit', 'edit') -> name('entidades.edit')->middleware('auth');
    
    Route::put('entidades/{entidad}', 'update') -> name('entidades.update')->middleware('auth');
    
    Route::delete('entidades/{entidad}', 'delete') -> name('entidades.delete')->middleware('auth');
});



//Libros

Route::controller(LibroController::class)-> group(function() {

    Route::get('libros', 'index') -> name('libros.index')->middleware('auth');

    Route::get('libros/create', 'create') -> name('libros.create')->middleware('auth');
    
    Route::post('libros/store','store') -> name('libros.store')->middleware('auth');
    
    Route::get('libros/{libro}/edit', 'edit') -> name('libros.edit')->middleware('auth');
    
    Route::put('libros/{libro}', 'update') -> name('libros.update')->middleware('auth');
    
    Route::delete('libros/{libro}', 'delete') -> name('libros.delete')->middleware('auth');
});




//Usuarios

Route::controller(UsuarioController::class)-> group(function() {

    Route::get('usuarios', 'index') -> name('usuarios.index')->middleware('auth');

    Route::get('usuarios/create', 'create') -> name('usuarios.create')->middleware('auth');
    
    Route::post('usuarios/store', 'store') -> name('usuarios.store')->middleware('auth');
    
    Route::get('usuarios/{usuario}/edit', 'edit') -> name('usuarios.edit')->middleware('auth');
    
    Route::put('usuarios/{usuario}', 'update') -> name('usuarios.update')->middleware('auth');
    
    Route::delete('usuarios/{usuario}', 'delete') -> name('usuarios.delete')->middleware('auth');
});


//Primera forma
//Route::get('libros', [LibroController::class, 'index']);


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
