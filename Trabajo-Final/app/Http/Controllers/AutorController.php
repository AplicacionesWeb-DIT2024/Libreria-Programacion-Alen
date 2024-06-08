<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use App\Models\Pais;



use Illuminate\Http\Request;

class AutorController extends Controller
{
    public function index() {
        $autores = Autor::orderBy('apellido', 'asc') -> paginate(3);
        return view('listarAutores', compact('autores'));
    }

    public function store(Request $request) {

        $autor = new Autor(); 
        $autor->nombre = $request->nombre; 

        $autor->apellido = $request->apellido; 

        $autor->pais_origen = $request->pais;
        $autor->fecha_nacimiento = $request->fechaNac;
        $autor->save();

        return redirect() ->back() -> with('success', 'Autor creado exitosamente');
    }


    public function create() {
        $paises = Pais::all();
        return view('agregarAutor', compact('paises'));
    }


    public function edit(Autor $autor) {
        $paises = Pais::all();
        return view('modificarAutor', ['autor'=> $autor, 'paises'=>$paises]);
    }

    public function update(Request $request, Autor $autor) {
        $autor -> nombre = $request -> nombre; 

        $autor-> apellido = $request -> apellido; 

        $autor-> pais_origen = $request->pais;
        $autor->fecha_nacimiento = $request->fechaNac;

        $autor->save();
        return redirect() -> route('autores.index');

    }

    public function show() {

    } 

    public function delete(Autor $autor) {
        $autor->delete();
        return redirect() -> route('autores.index');
    }



}
