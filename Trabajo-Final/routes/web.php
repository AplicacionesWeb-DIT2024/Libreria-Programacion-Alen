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
use App\Http\Controllers\ClienteController;

use App\Models\Libro;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

//Login

Route::controller(LoginController::class) -> group(function() {

    Route::post('admin/login', 'loguear');
    Route::get('admin/login', 'show') -> name('login');
    Route::post('admin/logout', 'desloguear')->name('logout');
    Route::get('admin/logout', 'desloguear');
});


Route::middleware(['auth', 'role:admin,superadmin'])->group(function () {

    Route::get('admin/home', function() {
        return view('home');
    }) -> name('home')->middleware('auth');

    //Categorias

    Route::controller(CategoriaController::class) -> group(function() {
        
        Route::get('admin/categorias', 'index') -> name('categorias.index')->middleware('auth');

        Route::get('admin/categorias/create','create') -> name('categorias.create')->middleware('auth');
        
        Route::post('admin/categorias/store', 'store') -> name('categorias.store')->middleware('auth');
        
        Route::get('admin/categorias/{categoria}/edit', 'edit') -> name('categorias.edit')->middleware('auth');
        
        Route::put('admin/categorias/{categoria}','update') -> name('categorias.update')->middleware('auth');
        
        Route::delete('admin/categorias/{categoria}','delete') -> name('categorias.delete')->middleware('auth');

        Route::patch('admin/categorias/{categoria}', 'activar')->name('categorias.activar')->middleware('auth');

    });



    //Subcategorias

    Route::controller(SubcategoriaController::class) -> group(function() {

        Route::get('admin/subcategorias','index') -> name('subcategorias.index')->middleware('auth');
        
        Route::get('admin/subcategorias/create', 'create') -> name('subcategorias.create')->middleware('auth');

        Route::post('admin/subcategorias/store','store') -> name('subcategorias.store')->middleware('auth');

        Route::get('admin/subcategorias/{subcategoria}/edit','edit') -> name('subcategorias.edit')->middleware('auth');

        Route::put('admin/subcategorias/{subcategoria}', 'update') -> name('subcategorias.update')->middleware('auth');

        Route::delete('admin/subcategorias/{subcategoria}', 'delete') -> name('subcategorias.delete')->middleware('auth');

        Route::patch('admin/subcategorias/{subcategoria}', 'activar')->name('subcategorias.activar')->middleware('auth');

    });


    //Autores

    Route::controller(AutorController::class) -> group(function() {

        Route::get('admin/autores',  'index') -> name('autores.index')->middleware('auth');

        Route::get('admin/autores/create',  'create') -> name('autores.create')->middleware('auth');

        Route::post('admin/autores/store',  'store') -> name('autores.store')->middleware('auth');

        Route::get('admin/autores/{autor}/edit',  'edit') -> name('autores.edit')->middleware('auth');

        Route::put('admin/autores/{autor}',  'update') -> name('autores.update')->middleware('auth');

        Route::delete('admin/autores/{autor}', 'delete') -> name('autores.delete')->middleware('auth');
        
        Route::patch('admin/autores/{autor}', 'activar')->name('autores.activar')->middleware('auth');


    });



    //Editoriales
    Route::controller(EditorialController::class)-> group(function() {

        Route::get('admin/editoriales',  'index') -> name('editoriales.index')->middleware('auth');

        Route::get('admin/editoriales/create', 'create') -> name('editoriales.create')->middleware('auth');
        
        Route::post('admin/editoriales/store', 'store') -> name('editoriales.store')->middleware('auth');
            
        Route::put('admin/editoriales/{editorial}', 'update') -> name('editoriales.update')->middleware('auth');
        
        Route::delete('admin/editoriales/{editorial}',  'delete') -> name('editoriales.delete')->middleware('auth');

        Route::patch('admin/editoriales/{editorial}', 'activar')->name('editoriales.activar')->middleware('auth');
    });

    //Libros

    Route::controller(LibroController::class)-> group(function() {

        Route::get('admin/libros', 'index') -> name('libros.index')->middleware('auth');

        Route::get('admin/libros/create', 'create') -> name('libros.create')->middleware('auth');
        
        Route::post('admin/libros/store','store') -> name('libros.store')->middleware('auth');
        
        Route::get('admin/libros/{libro}/edit', 'edit') -> name('libros.edit')->middleware('auth');
        
        Route::put('admin/libros/{libro}', 'update') -> name('libros.update')->middleware('auth');
        
        Route::delete('admin/libros/{libro}', 'delete') -> name('libros.delete')->middleware('auth');

        Route::patch('admin/libros/{libro}', 'activar')->name('libros.activar')->middleware('auth');

    });

    Route::controller(ClienteController::class)->group(function () {
        Route::get('admin/clientes', 'index')->name('clientes.index');
    });

    Route::controller(UsuarioController::class)->group(function () {
        Route::get('admin/perfil', 'edit')->name('usuario.perfil');
        Route::put('admin/perfil', 'update')->name('usuario.perfil.update');
        Route::get('admin/administradores', 'indexAdmins')->name('administradores.index');
        Route::patch('admin/usuarios/{usuario}/desactivar', 'delete')->name('usuarios.desactivar');
        Route::patch('admin/usuarios/{usuario}/activar', 'activar')->name('usuarios.activar');

    });
});


//Superadmins
Route::middleware(['auth', 'role:superadmin'])->controller(UsuarioController::class)->group(function () {
    Route::get('admin/usuarios/create', 'create')->name('usuarios.create');
    Route::post('admin/usuarios', 'store')->name('usuarios.store');
    Route::patch('admin/usuarios/{usuario}/transferir-superadmin', 'transferirSuperAdmin')->name('usuarios.transferir');
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
