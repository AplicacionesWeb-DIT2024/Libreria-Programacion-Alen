<?php

namespace App\Http\Controllers;

use App\Models\Banco;
use App\Models\Pais;
use Illuminate\Http\Request;

class BancoController extends Controller
{
    public function index() {
        $bancos = Banco::orderBy('nombre', 'asc') -> paginate(3);
        return view('listarBancos', compact('bancos'));
    }

    public function store(Request $request) {

        $banco = new Banco(); 
        $banco->nombre = $request->nombre; 

        $banco->pais_origen = $request->pais;
        $banco->save();

        return redirect() ->back() -> with('success', 'Banco creado exitosamente');
    }


    public function create() {
        $paises = Pais::all();
        return view('agregarBanco', compact('paises'));
    }


    public function edit(Banco $banco) {
        $paises = Pais::all();
        return view('modificarBanco', ['banco'=> $banco, 'paises'=>$paises]);
    }

    public function update(Request $request, Banco $banco) {
        $banco -> nombre = $request -> nombre; 


        $banco-> pais_origen = $request->pais;

        $banco->save();
        return redirect() -> route('bancos.index');

    }

    public function delete(Banco $banco) {
        $banco->delete();
        return redirect() -> route('bancos.index');
    }

}
