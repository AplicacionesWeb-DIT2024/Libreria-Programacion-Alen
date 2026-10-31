<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use App\Models\Pais;
use Illuminate\Support\Facades\Auth;

use Illuminate\Validation\Rule;


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

        $paises = collect(config('countries'));

        $request->validateWithBag('agregarAutor', [
            'nombre'   => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'pais'     => ['required', Rule::in($paises->all())],
            'fechaNac' => [
                'required', 'date',
                'before_or_equal:' . now()->subYears(18)->toDateString(),
                'after_or_equal:1900-01-01',
                function ($attribute, $value, $fail) use ($request) {
                    $existe = Autor::whereRaw('LOWER(nombre) = ?', [mb_strtolower(trim($request->nombre))])
                        ->whereRaw('LOWER(apellido) = ?', [mb_strtolower(trim($request->apellido))])
                        ->where('pais_origen', $request->pais)
                        ->whereDate('fecha_nacimiento', $request->fechaNac)
                        ->exists();

                    if ($existe) {
                        $fail('Ya existe un autor con el mismo nombre, apellido, país y fecha de nacimiento.');
                    }
                },
            ],
        ], [
            'nombre.required'   => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'pais.required'     => 'Debe seleccionar un país.',
            'pais.in'           => 'El país seleccionado no es válido.',
            'fechaNac.required' => 'La fecha de nacimiento es obligatoria.',
            'fechaNac.date'     => 'La fecha de nacimiento no es válida.',
            'fechaNac.before_or_equal' => 'El autor debe tener al menos 18 años.',
            'fechaNac.after_or_equal'  => 'La fecha de nacimiento no es válida.',
        ]);

        $autor = new Autor(); 
        $autor->nombre = trim($request->nombre); 

        $autor->apellido = trim($request->apellido); 

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
        $paises = collect(config('countries'));

        $request->validateWithBag('editarAutor', [
            'nombre'   => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'pais'     => ['required', Rule::in($paises->all())],
            'fechaNac' => [
                'required', 'date',
                'before_or_equal:' . now()->subYears(18)->toDateString(),
                'after_or_equal:1900-01-01',
                function ($attribute, $value, $fail) use ($request, $autor) {
                    $existe = Autor::whereRaw('LOWER(nombre) = ?', [mb_strtolower(trim($request->nombre))])
                        ->whereRaw('LOWER(apellido) = ?', [mb_strtolower(trim($request->apellido))])
                        ->where('pais_origen', $request->pais)
                        ->whereDate('fecha_nacimiento', $value)
                        ->where('id', '!=', $autor->id)
                        ->exists();
                    if ($existe) {
                        $fail('Ya existe otro autor con el mismo nombre, apellido, país y fecha de nacimiento.');
                    }
                },
            ],
        ], 
        [
            'nombre.required'   => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'pais.required'     => 'Debe seleccionar un país.',
            'pais.in'           => 'El país seleccionado no es válido.',
            'fechaNac.required' => 'La fecha de nacimiento es obligatoria.',
            'fechaNac.date'     => 'La fecha de nacimiento no es válida.',
            'fechaNac.before_or_equal' => 'El autor debe tener al menos 18 años.',
            'fechaNac.after_or_equal'  => 'La fecha de nacimiento no es válida.',
        ]);

        $autor->nombre = trim($request->nombre);
        $autor->apellido = trim($request->apellido);

        $autor-> pais_origen = $request->pais;
        $autor->fecha_nacimiento = $request->fechaNac;
        $autor->usuario_modificacion = Auth::id();


        $autor->save();
        return redirect()-> back()->with('success', 'Autor modificado exitosamente');

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
