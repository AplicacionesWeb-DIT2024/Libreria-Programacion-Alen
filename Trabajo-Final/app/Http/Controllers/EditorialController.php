<?php

namespace App\Http\Controllers;

use App\Models\Editorial;
use App\Models\Pais;
use Illuminate\Http\Request;

class EditorialController extends Controller
{
    public function index() {
        $editoriales = Editorial::orderBy('nombre', 'asc') -> paginate(3);
        return view('listarEditoriales', compact('editoriales'));
    }

    public function store(Request $request) {

        $editorial = new Editorial(); 
        $editorial->nombre = $request->nombre; 
        $editorial->pais = $request->pais;
        $editorial->save();

        return redirect() ->back() -> with('success', 'Editorial creada exitosamente');
    }


    public function create() {
        $paises = Pais::all();
        return view('agregarEditorial', compact('paises'));
    }


    public function edit(Editorial $editorial) {
        $paises = Pais::all();
        return view('modificarEditorial', ['editorial'=> $editorial, 'paises'=>$paises]);
    }

    public function update(Request $request, Editorial $editorial) {
        $editorial->nombre = $request->nombre; 


        $editorial->pais = $request->pais;

        $editorial->save();
        return redirect() -> route('editoriales.index');

    }

  
    public function delete(Editorial $editorial) {
        $editorial->delete();
        return redirect() -> route('editoriales.index');
    }

}
