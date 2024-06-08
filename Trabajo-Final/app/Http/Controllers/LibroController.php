<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use App\Models\Categoria;
use App\Models\Editorial;
use App\Models\Libro;
use App\Models\Pais;
use App\Models\Subcategoria;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    public function index() {
        $libros = Libro::orderBy('nombre', 'asc') -> paginate(3);
        return view('listarLibros', compact('libros'));
    }

    public function store(Request $request) {


        //$libro = Libro::create($request->all());

        $libro = new Libro(); 
        $libro->nombre = $request->nombre; 
        $libro->stock = $request->stock;
        $libro->autor = $request->autor;

        if ($request->autor2 == $request->autor) {
            $libro->autor2 == null;
        }
        else {
                $request->autor2 == $libro->autor2;
        }

        if ($request->autor3 == $request->autor || $request->autor3 == $request->autor2) {
            $libro->autor3 = null;
        } else {
            $libro->autor3 = $request->autor3;
        }

        $libro->editorial = $request->editorial; 
        $libro->pais_origen = $request->pais_origen; 
        $libro->pais_impresion = $request->pais_impresion; 
        $libro->edicion = $request->edicion;
        $libro->anio_publicacion = $request->anio_publicacion;
        $libro->precio = $request->precio;
        $libro->categoria = $request->categoria; 
        $libro->subcategoria = $request->subcategoria;

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $destinationPath = 'images/libros/';
            $filename = time() . '-' . $file->getClientOriginalName();
            $uploadSuccess = $request->file('imagen')->move($destinationPath,$filename);
            $libro->imagen_referencia = $destinationPath . $filename;
        }
        


        $libro->save();

        return redirect() ->back() -> with('success', 'Libro creado exitosamente');
    }


    public function create() {
        $autores = Autor::all();
        $editoriales = Editorial::all();
        $paises = Pais::all();
        $categorias = Categoria::all();
        $subcategorias = Subcategoria::all();
        return view('agregarLibro', compact('paises','editoriales','autores', 'paises','categorias','subcategorias'));
    }


    public function edit(Libro $libro) {
        $paises = Pais::all();
        $editoriales = Editorial::all();
        $autores = Autor::all();
        $categorias = Categoria::all();
        $subcategorias = Subcategoria::all();
        return view('modificarLibro', ['libro'=> $libro, 'paises'=>$paises, 'editoriales' => $editoriales, 'autores' => $autores
    , 'categorias' => $categorias, 'subcategorias' => $subcategorias]);
    }

    
    public function update(Request $request, Libro $libro) {
        $libro->nombre = $request->nombre; 
        $libro->stock = $request->stock;
        $libro->autor = $request->autor; 

        if ($request->autor2 == $request->autor) {
            $libro->autor2 = null;
        }
        else {
                $libro->autor2 = $request->autor2;
        }

        if ($request->autor3 == $request->autor || $request->autor3 == $request->autor2) {
            $libro->autor3 = null;
        } else {
            $libro->autor3 = $request->autor3;
        }

        $libro->editorial = $request->editorial; 
        $libro->pais_origen = $request->pais_origen; 
        $libro->pais_impresion = $request->pais_impresion; 
        $libro->edicion = $request->edicion;
        $libro->anio_publicacion = $request->anio_publicacion;
        $libro->precio = $request->precio;
        $libro->categoria = $request->categoria; 
        $libro->subcategoria = $request->subcategoria;

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $destinationPath = 'images/libros/';
            $filename = time() . '-' . $file->getClientOriginalName();
            $uploadSuccess = $request->file('imagen')->move($destinationPath,$filename);
            $libro->imagen_referencia = $destinationPath . $filename;
        }
        



        $libro->save();
        return redirect() -> route('libros.index');

    }

    public function delete(Libro $libro) {
        $libro->delete();
        return redirect() -> route('libros.index');
    }

}
