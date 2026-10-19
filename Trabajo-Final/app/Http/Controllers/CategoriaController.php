<?php

namespace App\Http\Controllers;

use App\Models\Categoria;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CategoriaController extends Controller
{
    public function index(Request $request) {

        $categorias = Categoria::query()
        ->orderBy('id')
        ->get();
        return view('listarCategorias', compact('categorias'));
    }

    public function store(Request $request) {
        $request -> validate ([ 'nombre' => 'required|unique:categorias,nombre', ]);

        $categoria = new Categoria(); 
        $categoria->nombre = $request->nombre; 
        $categoria->activo = true;
        $categoria->usuario_creacion = Auth::id();
        $categoria->usuario_modificacion = Auth::id();
        $categoria->save();

        return redirect() ->back() -> with('success', 'Categoria creada exitosamente');
    }

    public function create() {
        return view('agregarCategoria');
    }


    public function edit(Categoria $categoria) {
        return view('modificarCategoria', ['categoria'=> $categoria]);
    }

    public function update(Request $request, Categoria $categoria) {
        $categoria -> nombre = $request -> nombre; 
        $categoria->usuario_modificacion = Auth::id();

        $categoria->save();
        return redirect() -> route('categorias.index');

    }

    public function show() {

    } 

    public function delete(Categoria $categoria) {
        $categoria->activo = false;
        $categoria->usuario_modificacion = Auth::id();
        $categoria->save();
        return redirect() -> route('categorias.index');
    }

    public function activar(Categoria $categoria)
    {
        $categoria->activo = true;
        $categoria->usuario_modificacion = Auth::id();
        $categoria->save();

        return redirect()->route('categorias.index')->with('success', 'Categoria reactivada correctamente');
    }

}
