<?php

namespace App\Http\Controllers;

use App\Models\Categoria;

use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index() {
        $categorias = Categoria::orderBy('nombre', 'asc') -> paginate(3);
        return view('listarCategorias', compact('categorias'));
    }

    public function store(Request $request) {
        $request -> validate ([ 'nombre' => 'required|unique:categorias,nombre', ]);

        $categoria = new Categoria(); 
        $categoria->nombre = $request->nombre; 
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

        $categoria->save();
        return redirect() -> route('categorias.index');

    }

    public function show() {

    } 

    public function delete(Categoria $categoria) {
        try {
            $categoria->delete();
        } catch (\Exception $error) {
            return redirect() -> back() -> withErrors(['msg' => 'No se puede eliminar la categoria']);
        }
        return redirect() -> route('categorias.index');
    }

}
