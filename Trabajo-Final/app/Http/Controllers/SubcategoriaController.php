<?php

namespace App\Http\Controllers;
use App\Models\Subcategoria;
use App\Models\Categoria;


use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Auth;

class SubcategoriaController extends Controller
{
    public function index() {
        $subcategorias = Subcategoria::with('categoria_perteneciente')->paginate(4);
        $categorias = Categoria::where('activo',true)->get();
        return view('listarSubcategoria', compact('subcategorias','categorias'));
    }

    public function store(Request $request) {

        $request->validateWithBag('agregarSubcategoria', [
        'nombre' => [
            'required', 'string', 'max:255',
            function ($attribute, $value, $fail) {
                $existe = Subcategoria::whereRaw('LOWER(nombre) = ?', [mb_strtolower(trim($value))])->exists();
                if ($existe) {
                    $fail('Ya existe una subcategoría con ese nombre en la categoría seleccionada.');
                }
            },
        ],
        'categoria' => ['required', 'exists:categorias,id'],
        ], [
            'nombre.required'    => 'El nombre de la subcategoría es obligatorio.',
            'nombre.max'         => 'El nombre no puede superar los 255 caracteres.',
            'categoria.required' => 'Debe seleccionar una categoría.',
            'categoria.exists'   => 'La categoría seleccionada no es válida.',
        ]);

        $subcategoria = new Subcategoria(); 
        $subcategoria->nombre = trim($request->nombre); 
        $subcategoria->categoria = $request->categoria;
        $subcategoria->activo = true;
        $subcategoria->usuario_creacion = Auth::id();
        $subcategoria->usuario_modificacion = Auth::id();
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

       $request->validateWithBag('editarSubcategoria', [
        'nombre' => [
            'required', 'string', 'max:255',
            function ($attribute, $value, $fail) use ($request, $subcategoria) {
                $existe = Subcategoria::whereRaw('LOWER(nombre) = ?', [mb_strtolower(trim($value))])
                    ->where('categoria', $request->categoria) 
                    ->where('id', '!=', $subcategoria->id)
                    ->exists();
                if ($existe) {
                    $fail('Ya existe otra subcategoría con ese nombre en la categoría seleccionada.');
                }
            },
        ],
         'categoria' => ['required', 'exists:categorias,id'],
        ],[
            'nombre.required'    => 'El nombre de la subcategoría es obligatorio.',
            'nombre.max'         => 'El nombre no puede superar los 255 caracteres.',
            'categoria.required' => 'Debe seleccionar una categoría.',
            'categoria.exists'   => 'La categoría seleccionada no es válida.',
        ]);

        $subcategoria->nombre = trim($request->nombre);
        $subcategoria->categoria = $request->categoria;
        $subcategoria->usuario_modificacion = Auth::id();
        $subcategoria->save();

        return redirect()->back()->with('success', 'Subcategoría modificada exitosamente');;

    }

    public function delete(Subcategoria $subcategoria) {
        $subcategoria->activo = false;
        $subcategoria->usuario_modificacion = Auth::id();
        $subcategoria->save();
        return redirect() -> route('subcategorias.index');
    }

    public function activar(Subcategoria $subcategoria)
    {
        $subcategoria->activo = true;
        $subcategoria->usuario_modificacion = Auth::id();
        $subcategoria->save();

        return redirect()->route('subcategorias.index')->with('success', 'Subcategoria reactivada correctamente');
    }


    public function show() {

    } 

   
}
