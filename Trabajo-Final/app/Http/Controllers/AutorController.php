<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use App\Models\Pais;
use Illuminate\Support\Facades\Auth;



use Illuminate\Http\Request;

class AutorController extends Controller
{
    public function index() {
        $paises = collect(config('countries'))->sort()->values();

        $autores = Autor::query()
        ->orderBy('id')
        ->get();
        return view('listarAutores', compact('autores', 'paises'));
    }

    public function store(Request $request) {

        $autor = new Autor(); 
        $autor->nombre = $request->nombre; 

        $autor->apellido = $request->apellido; 

        $autor->pais_origen = $request->pais;
        $autor->fecha_nacimiento = $request->fechaNac;

        $autor->activo = true;
        $autor->usuario_creacion = Auth::id();
        $autor->usuario_modificacion = Auth::id();
        $autor->save();

        return redirect() ->back() -> with('success', 'Autor creado exitosamente');
    }


    public function create() {
        $paises = config('countries');
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
        $autor->activo = false;
        $autor->usuario_modificacion = Auth::id();
        $autor->save();
        return redirect() -> route('autores.index');
    }

    public function activar(Autor $autor)
    {
        $autor->activo = true;
        $autor->usuario_modificacion = Auth::id();
        $autor->save();

        return redirect()->route('autores.index')->with('success', 'Autor reactivado correctamente');
    }



}
