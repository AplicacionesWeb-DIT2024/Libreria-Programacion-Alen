<?php

namespace App\Http\Controllers;
use App\Models\Subcategoria;

use Illuminate\Http\Request;

class SubcategoriaController extends Controller
{
    public function index() {
        $subcategorias = Subcategoria::paginate(4);
        return view('listarSubcategoria', compact('subcategorias'));
    }

    public function store(Request $request) {
        $request -> validate ([ 'nombre' => 'required|unique:subcategorias,nombre', ]);

        $subcategoria = new Subcategoria(); 
        $subcategoria->nombre = $request->nombre; 
        $subcategoria->save();

        return redirect() -> back() -> with('success', 'Subcategoria creada exitosamente');
    }

    public function create() {
        return view('agregarSubcategoria');
    }

    public function edit(Subcategoria $subcategoria) {
        return view('modificarSubcategoria', ['subcategoria'=> $subcategoria]);
    }


    public function update(Request $request, Subcategoria $subcategoria) {
        $subcategoria -> nombre = $request -> nombre; 

        $subcategoria->save();
        return redirect() -> route('subcategorias.index');

    }

    public function delete(Subcategoria $subcategoria) {
        $subcategoria->delete();
        return redirect() -> route('subcategorias.index');
    }


    public function show() {

    } 

   
}
