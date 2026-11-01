<?php

namespace App\Http\Controllers;

use App\Models\Banco;
use App\Models\Entidad;
use Illuminate\Http\Request;

class EntidadController extends Controller
{
    public function index() {
        $entidades = Entidad::orderBy('nombre', 'asc') -> paginate(3);
        return view('listarEntidades', compact('entidades'));
    }

    public function store(Request $request) {

        $entidad = new Entidad(); 
        $entidad->nombre = $request->nombre; 
        $entidad->tipo = $request->tipo;
        $entidad->codigo_banco = $request->banco;
        $entidad->save();

        return redirect() ->back() -> with('success', 'Entidad creada exitosamente');
    }


    public function create() {
        $bancos = Banco::all();
        return view('agregarEntidad', compact('bancos'));
    }


    public function edit(Entidad $entidad) {
        $bancos = Banco::all();
        return view('modificarEntidad', ['entidad'=> $entidad, 'bancos'=>$bancos]);
    }

    public function update(Request $request, Entidad $entidad) {
        $entidad->nombre = $request->nombre; 


        $entidad->codigo_banco = $request->banco;

        $entidad->tipo = $request->tipo;


        $entidad->save();
        return redirect() -> route('entidades.index');

    }

  
    public function delete(Entidad $entidad) {
        $entidad->delete();
        return redirect() -> route('entidades.index');
    }
}
