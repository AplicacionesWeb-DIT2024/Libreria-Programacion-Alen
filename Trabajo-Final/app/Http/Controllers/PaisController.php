<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pais;


class PaisController extends Controller
{
    public function index() {
        $paises = Pais::paginate(4);
        return view('listarPaises', compact('paises'));
    }

    public function store(Request $request) {
        $request -> validate ([ 'nombre' => 'required|unique:paises,nombre', ]);

        $pais = new Pais(); 
        $pais->nombre = $request->nombre; 
        $pais->save();

        return redirect() -> back() -> with('success', 'Pais creado exitosamente');
    }

    public function create() {
        return view('agregarPais');
    }



    public function edit(Pais $pais) {
        return view('modificarPais', ['pais'=> $pais]);
    }


    public function update(Request $request, Pais $pais) {
        $pais -> nombre = $request -> nombre; 

        $pais->save();
        return redirect() -> route('paises.index');

    }

    public function delete(Pais $pais) {
        $pais->delete();
        return redirect() -> route('paises.index');
    }

    public function show() {

    } 

   
}
