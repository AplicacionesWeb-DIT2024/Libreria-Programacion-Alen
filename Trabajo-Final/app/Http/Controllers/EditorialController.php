<?php

namespace App\Http\Controllers;

use App\Models\Editorial;
use App\Models\Pais;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class EditorialController extends Controller
{
    public function index(Request $request) {
        $paises = collect(config('countries'))->sort()->values();
        

        $editoriales = Editorial::query()
        ->orderBy('id')
        ->get();
;
       

        return view('listarEditoriales', compact('editoriales','paises'));
    }

    public function store(Request $request) {

        $editorial = new Editorial(); 
        $editorial->nombre = $request->nombre; 
        $editorial->pais = $request->pais;
        $editorial->activo = true; 
        $editorial->usuario_creacion = Auth::id();
        $editorial->usuario_modificacion = Auth::id();
        $editorial->save();

        return redirect() ->back() -> with('success', 'Editorial creada exitosamente');
    }


    public function create() {
        $paises = config('countries');
        return view('agregarEditorial', compact('paises'));
    }


    public function edit(Editorial $editorial) {
        $paises = Pais::all();
        return view('modificarEditorial', ['editorial'=> $editorial, 'paises'=>$paises]);
    }

    public function update(Request $request, Editorial $editorial) {
        $editorial->nombre = $request->nombre; 


        $editorial->pais = $request->pais;
        $editorial->usuario_modificacion = Auth::id();

        $editorial->save();
        return redirect() -> route('editoriales.index');

    }

  
    public function delete(Editorial $editorial) {

        $editorial->activo = false;
        $editorial->usuario_modificacion = Auth::id();
        $editorial->save();
        return redirect() -> route('editoriales.index');
    }

    public function activar(Editorial $editorial)
    {
        $editorial->activo = true;
        $editorial->usuario_modificacion = Auth::id();
        $editorial->save();

        return redirect()->route('editoriales.index')->with('success', 'Editorial reactivada correctamente');
    }

}
