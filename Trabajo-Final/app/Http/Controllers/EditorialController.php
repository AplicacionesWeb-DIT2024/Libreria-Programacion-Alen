<?php

namespace App\Http\Controllers;

use App\Models\Editorial;
use App\Models\Pais;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class EditorialController extends Controller
{
    public function index(Request $request) {
        $paises = collect(config('countries'))->sort()->values();
        

        $editoriales = Editorial::query()
        ->orderBy('id')
        ->get();
       

        return view('listarEditoriales', compact('editoriales','paises'));
    }

    public function store(Request $request) {

        $paises = collect(config('countries'));

         $request->validateWithBag('agregarEditorial', [
        'nombre' =>[
            'required', 'string', 'max:255',
            function ($attribute, $value, $fail) {
                $existe = Editorial::whereRaw('LOWER(nombre) = ?', [mb_strtolower(trim($value))])->exists();
                if ($existe) {
                        $fail('Ya existe una editorial con ese nombre.');
                }
            },
        ],
        'pais'   => ['required', Rule::in($paises->all())],
        ], [
            'nombre.required' => 'El nombre de la editorial es obligatorio.',
            'nombre.max'      => 'El nombre no puede superar los 255 caracteres.',
            'pais.required'   => 'Debe seleccionar un país.',
            'pais.in'         => 'El país seleccionado no es válido.',
        ]);

        $editorial = new Editorial(); 
        $editorial->nombre = trim($request->nombre); 
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

        $paises = collect(config('countries'));

        $request->validateWithBag('editarEditorial', [
        'nombre' => [
            'required', 'string', 'max:255',
            function ($attribute, $value, $fail) use ($editorial) {
                $existe = Editorial::whereRaw('LOWER(nombre) = ?', [mb_strtolower(trim($value))])
                            ->where('id', '!=', $editorial->id)
                            ->exists();
                if ($existe) {
                    $fail('Ya existe otra editorial con ese nombre.');
                }
            },
        ],
        'pais' => ['required', Rule::in($paises->all())],
        ], [
            'nombre.required' => 'El nombre de la editorial es obligatorio.',
            'nombre.max'      => 'El nombre no puede superar los 255 caracteres.',
            'pais.required'   => 'Debe seleccionar un país.',
            'pais.in'         => 'El país seleccionado no es válido.',
        ]);

        $editorial->nombre = trim($request->nombre); 


        $editorial->pais = $request->pais;
        $editorial->usuario_modificacion = Auth::id();

        $editorial->save();
        return redirect() -> back() -> with('success', 'Editorial modificada exitosamente');

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
