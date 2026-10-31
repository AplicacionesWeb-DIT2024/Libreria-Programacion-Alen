<?php

namespace App\Http\Controllers;

use App\Models\Categoria;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class CategoriaController extends Controller
{
    public function index(Request $request) {

        $categorias = Categoria::query()
        ->orderBy('id')
        ->get();
        return view('listarCategorias', compact('categorias'));
    }

    public function store(Request $request) {
         $request->validateWithBag('agregarCategoria', 
         ['nombre' => 
            ['required', 'string', 'max:255', 
            function ($attribute, $value, $fail) {
                $existe = Categoria::whereRaw('LOWER(nombre) = ?', [mb_strtolower(trim($value))])->exists();
                if ($existe) {
                    $fail('Ya existe una categoría con ese nombre.');
                }
            },
            ],], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.max'      => 'El nombre no puede superar los 255 caracteres.',
        ]);

        $categoria = new Categoria(); 
        $categoria->nombre = trim($request->nombre); 
        $categoria->activo = true;
        $categoria->usuario_creacion = Auth::id();
        $categoria->usuario_modificacion = Auth::id();
        $categoria->save();

        return redirect() ->back() -> with('success', 'Categoria creada exitosamente');
    }

    public function create() {
        return view('agregarCategoria');
    }


    public function edit(Categoria $categoria) {
        return view('modificarCategoria', ['categoria'=> $categoria]);
    }

    public function update(Request $request, Categoria $categoria) {
        $request->validateWithBag('editarCategoria', [
        'nombre' => [
            'required', 'string', 'max:255',
            function ($attribute, $value, $fail) use ($categoria) {
                $existe = Categoria::whereRaw('LOWER(nombre) = ?', [mb_strtolower(trim($value))])
                    ->where('id', '!=', $categoria->id)
                    ->exists();
                if ($existe) {
                    $fail('Ya existe otra categoría con ese nombre.');
                }
            },
        ],], [
        'nombre.required' => 'El nombre de la categoría es obligatorio.',
        'nombre.max'      => 'El nombre no puede superar los 255 caracteres.',
        ]);
        $categoria -> nombre = trim($request -> nombre); 
        $categoria->usuario_modificacion = Auth::id();

        $categoria->save();
        return redirect()->back() -> with('success', 'Categoria editada exitosamente');

    }

    public function show() {

    } 

    public function delete(Categoria $categoria) {
        $categoria->activo = false;
        $categoria->usuario_modificacion = Auth::id();
        $categoria->save();
        return redirect() -> route('categorias.index');
    }

    public function activar(Categoria $categoria)
    {
        $categoria->activo = true;
        $categoria->usuario_modificacion = Auth::id();
        $categoria->save();

        return redirect()->route('categorias.index')->with('success', 'Categoria reactivada correctamente');
    }

}
