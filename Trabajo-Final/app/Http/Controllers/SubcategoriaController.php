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

        $request->validate([
            'nombre' => [
                'required',
                Rule::unique('subcategorias')->where(function ($query) use ($request) {
                    return $query->where('categoria', $request->categoria);
                }),
            ],
            'categoria' => 'required|exists:categorias,id',
        ]);

        $subcategoria = new Subcategoria(); 
        $subcategoria->nombre = $request->nombre; 
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

        $request->validate([
        'nombre' => [
            'required',
            Rule::unique('subcategorias')
                ->where(fn ($query) => $query->where('categoria', $request->categoria))
                ->ignore($subcategoria->id),
        ],
        'categoria' => 'required|exists:categorias,id',
        ]);

        $subcategoria->nombre = $request->nombre;
        $subcategoria->categoria = $request->categoria;
        $subcategoria->usuario_modificacion = Auth::id();
        $subcategoria->save();

        $subcategoria->save();
        return redirect() -> route('subcategorias.index');

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
