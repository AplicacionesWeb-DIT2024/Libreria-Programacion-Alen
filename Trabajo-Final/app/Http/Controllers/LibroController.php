<?php

namespace App\Http\Controllers;


use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Autor;
use App\Models\Categoria;
use App\Models\Editorial;
use App\Models\Libro;
use App\Models\Pais;
use App\Models\Subcategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class LibroController extends Controller
{
    public function index() {
        $libros = Libro::with([
            'categoria_perteneciente',
            'subcategoria_perteneciente',
            'editorial_perteneciente',
            'autor_origen',
            'autor_secundario',
            'autor_secundario2',
        ])->orderBy('id')
        ->get();
         $autores = Autor::all();
         $editoriales = Editorial::all();
         $categorias = Categoria::all();
         $subcategorias = Subcategoria::all();
         $paises = config('countries');
         $idiomas = config('languages');
        return view('listarLibros', compact('libros','autores','editoriales',
        'paises',
        'categorias',
        'subcategorias','idiomas'));
    }


    public function index_api() {
        $libros = Libro::orderBy('nombre', 'asc')->get();
        return response()->json($libros, 200);

    }


    public function show_api(Request $request) {
        $libro = Libro::where ('id', $request->id) -> first();
        if ($libro) {
            return response()->json($libro, 200);
        }
        else {
            return response()->json(['message' => 'libro no encontrado'], 404);

        }

    }

    public function store(Request $request) {

        $imagen = ['file', 'image', 'mimes:jpg,jpeg,png,webp', 'mimetypes:image/jpeg,image/png,image/webp', 'max:5120'];
        $paises  = collect(config('countries'))->values()->all();
        $idiomas = array_keys(config('languages'));

        $request->validateWithBag('agregarLibro', [
            'nombre'           => [
                'required', 'string', 'max:255', "regex:/^[\p{L}\p{N}¡¿][\p{L}\p{N}\s'’.,:;!?¡¿-]*$/u",
                function ($attribute, $value, $fail) use ($request) {
                    $existe = Libro::whereRaw('LOWER(nombre) = ?', [mb_strtolower(trim($value))])
                        ->where('idioma', $request->idioma)
                        ->where('categoria', $request->categoria)
                        ->where('subcategoria', $request->subcategoria)
                        ->where('editorial', $request->editorial)
                        ->where('autor', $request->autor)
                        ->where('autor2', $request->autor2 ?: null)
                        ->where('autor3', $request->autor3 ?: null)
                        ->where('pais_origen', $request->pais_origen)
                        ->where('pais_impresion', $request->pais_impresion)
                        ->where('edicion', $request->edicion)
                        ->where('anio_publicacion', $request->anio_publicacion)
                        ->exists();
                    if ($existe) {
                        $fail('Ya existe un libro con esos mismos datos.');
                    }
                },
            ],
            'categoria'        => ['required', 'exists:categorias,id'],
            'subcategoria'     => ['required', 'exists:subcategorias,id'],
            'editorial'        => ['required', 'exists:editoriales,id'],
            'autor'            => ['required', 'exists:autores,id', 'different:autor2', 'different:autor3'],
            'autor2'           => ['nullable', 'exists:autores,id', 'different:autor'],
            'autor3'           => ['nullable', 'exists:autores,id', 'different:autor', 'different:autor2'],
            'edicion'          => ['required', 'integer', 'min:1'],
            'anio_publicacion' => ['required', 'integer', 'min:1000', 'max:' . date('Y')],
            'stock'            => ['required', 'integer', 'min:0'],
            'precio'           => ['required', 'numeric', 'min:0'],
            'idioma'         => ['required', Rule::in($idiomas)],
            'pais_origen'    => ['required', Rule::in($paises)],
            'pais_impresion' => ['required', Rule::in($paises)],
            'imagen_original'      => array_merge(['required'], $imagen),
            'imagen_referencia_2'  => array_merge(['nullable'], $imagen),
            'imagen_referencia_3'  => array_merge(['nullable'], $imagen),
        ], [
            'required'  => 'Este campo es obligatorio.',
            'exists'    => 'La opción seleccionada no es válida.',
            'integer'   => 'Debe ser un número entero.',
            'numeric'   => 'Debe ser un número.',
            'min'       => 'El valor mínimo permitido es :min.',
            'anio_publicacion.max' => 'El año no puede ser mayor al actual.',
            'autor.different'  => 'Los autores no pueden repetirse.',
            'autor2.different' => 'Los autores no pueden repetirse.',
            'autor3.different' => 'Los autores no pueden repetirse.',
            'imagen_original.required' => 'Debe seleccionar una imagen principal.',
            'idioma.in'         => 'El idioma seleccionado no es válido.',
            'pais_origen.in'    => 'El país de origen seleccionado no es válido.',
            'pais_impresion.in' => 'El país de impresión seleccionado no es válido.',
            '*.image'     => 'El archivo debe ser una imagen.',
            '*.mimes'     => 'Solo se permiten imágenes JPG, PNG o WEBP.',
            '*.mimetypes' => 'Solo se permiten imágenes JPG, PNG o WEBP.',
            '*.max'       => 'La imagen no puede superar los 5 MB.',
            '*.uploaded'  => 'No se pudo subir la imagen (puede ser demasiado pesada).',
        ]);

        $libro = new Libro(); 
        $libro->nombre = $request->nombre; 
        $libro->stock = $request->stock;
        $libro->autor = $request->autor;
        $libro->autor2 = $request->autor2 ?: null;
        $libro->autor3 = $request->autor3 ?: null;
        
        $libro->idioma = $request->idioma;
        $libro->editorial = $request->editorial; 
        $libro->pais_origen = $request->pais_origen; 
        $libro->pais_impresion = $request->pais_impresion; 
        $libro->edicion = $request->edicion;
        $libro->anio_publicacion = $request->anio_publicacion;
        $libro->precio = $request->precio;
        $libro->categoria = $request->categoria; 
        $libro->subcategoria = $request->subcategoria;
        $libro->usuario_creacion = Auth::id();
        $libro->usuario_modificacion = Auth::id();
        $libro->activo = true;

        $cloudinaryImagen = $request->file('imagen_original')->storeOnCloudinary('libros');
        $libro->imagen_original = $cloudinaryImagen->getSecurePath();
        $libro->imagen_original_public_id = $cloudinaryImagen->getPublicId();

        if ($request->hasFile('imagen_referencia_2')) {
            $cloudinaryRef2 = $request->file('imagen_referencia_2')->storeOnCloudinary('libros');
            $libro->imagen_referencia_2 = $cloudinaryRef2->getSecurePath();
            $libro->imagen_referencia_2_public_id = $cloudinaryRef2->getPublicId();

        }

        if ($request->hasFile('imagen_referencia_3')) {
            $cloudinaryRef3 = $request->file('imagen_referencia_3')->storeOnCloudinary('libros');
            $libro->imagen_referencia_3 = $cloudinaryRef3->getSecurePath();
            $libro->imagen_referencia_3_public_id = $cloudinaryRef3->getPublicId();

        }
        


        $libro->save();

        return redirect() ->back() -> with('success', 'Libro creado exitosamente');
    }


    public function create() {
        $autores = Autor::all();
        $editoriales = Editorial::all();
        $paises = config('countries');
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
        $imagen = ['file', 'image', 'mimes:jpg,jpeg,png,webp', 'mimetypes:image/jpeg,image/png,image/webp', 'max:5120'];
        $paises  = collect(config('countries'))->values()->all();
        $idiomas = array_keys(config('languages'));

        $request->validateWithBag('editarLibro', [
            'nombre'           => [
                'required', 'string', 'max:255', "regex:/^[\p{L}\p{N}¡¿][\p{L}\p{N}\s'’.,:;!?¡¿-]*$/u",
                function ($attribute, $value, $fail) use ($request, $libro) {
                    $existe = Libro::whereRaw('LOWER(nombre) = ?', [mb_strtolower(trim($value))])
                        ->where('idioma', $request->idioma)
                        ->where('categoria', $request->categoria)
                        ->where('subcategoria', $request->subcategoria)
                        ->where('editorial', $request->editorial)
                        ->where('autor', $request->autor)
                        ->where('autor2', $request->autor2 ?: null)
                        ->where('autor3', $request->autor3 ?: null)
                        ->where('pais_origen', $request->pais_origen)
                        ->where('pais_impresion', $request->pais_impresion)
                        ->where('edicion', $request->edicion)
                        ->where('anio_publicacion', $request->anio_publicacion)
                        ->where('id', '!=', $libro->id)
                        ->exists();
                    if ($existe) {
                        $fail('Ya existe un libro con esos mismos datos.');
                    }
                },
            ],
            'categoria'        => ['required', 'exists:categorias,id'],
            'subcategoria'     => ['required', 'exists:subcategorias,id'],
            'editorial'        => ['required', 'exists:editoriales,id'],
            'autor'            => ['required', 'exists:autores,id', 'different:autor2', 'different:autor3'],
            'autor2'           => ['nullable', 'exists:autores,id', 'different:autor'],
            'autor3'           => ['nullable', 'exists:autores,id', 'different:autor', 'different:autor2'],
            'edicion'          => ['required', 'integer', 'min:1'],
            'anio_publicacion' => ['required', 'integer', 'min:1000', 'max:' . date('Y')],
            'stock'            => ['required', 'integer', 'min:0'],
            'precio'           => ['required', 'numeric', 'min:0'],
            'idioma'         => ['required', Rule::in($idiomas)],
            'pais_origen'    => ['required', Rule::in($paises)],
            'pais_impresion' => ['required', Rule::in($paises)],
            'imagen_original'      => array_merge(['nullable'], $imagen),
            'imagen_referencia_2'  => array_merge(['nullable'], $imagen),
            'imagen_referencia_3'  => array_merge(['nullable'], $imagen),
        ], [
            'required'  => 'Este campo es obligatorio.',
            'exists'    => 'La opción seleccionada no es válida.',
            'integer'   => 'Debe ser un número entero.',
            'numeric'   => 'Debe ser un número.',
            'min'       => 'El valor mínimo permitido es :min.',
            'anio_publicacion.max' => 'El año no puede ser mayor al actual.',
            'autor.different'  => 'Los autores no pueden repetirse.',
            'autor2.different' => 'Los autores no pueden repetirse.',
            'autor3.different' => 'Los autores no pueden repetirse.',
            'idioma.in'         => 'El idioma seleccionado no es válido.',
            'pais_origen.in'    => 'El país de origen seleccionado no es válido.',
            'pais_impresion.in' => 'El país de impresión seleccionado no es válido.',
            '*.image'     => 'El archivo debe ser una imagen.',
            '*.mimes'     => 'Solo se permiten imágenes JPG, PNG o WEBP.',
            '*.mimetypes' => 'Solo se permiten imágenes JPG, PNG o WEBP.',
            '*.max'       => 'La imagen no puede superar los 5 MB.',
            '*.uploaded'  => 'No se pudo subir la imagen (puede ser demasiado pesada).',
        ]);

        $libro->nombre = $request->nombre; 
        $libro->stock = $request->stock;
        $libro->autor = $request->autor;
        $libro->autor2 = $request->autor2 ?: null;
        $libro->autor3 = $request->autor3 ?: null;
        
        $libro->idioma = $request->idioma;
        $libro->editorial = $request->editorial; 
        $libro->pais_origen = $request->pais_origen; 
        $libro->pais_impresion = $request->pais_impresion; 
        $libro->edicion = $request->edicion;
        $libro->anio_publicacion = $request->anio_publicacion;
        $libro->precio = $request->precio;
        $libro->categoria = $request->categoria; 
        $libro->subcategoria = $request->subcategoria;
        $libro->usuario_modificacion = Auth::id();

        if ($request->hasFile('imagen_original')) {
            $viejo = $libro->imagen_original_public_id;
            $subida = $request->file('imagen_original')->storeOnCloudinary('libros');
            $libro->imagen_original = $subida->getSecurePath();
            $libro->imagen_original_public_id = $subida->getPublicId();
            if ($viejo) {
                Cloudinary::destroy($viejo);
            }
        }

        foreach ([2, 3] as $n) {
            $campo    = "imagen_referencia_$n";
            $publicId = "{$campo}_public_id";

            if ($request->hasFile($campo)) {
                if ($libro->$publicId) {
                    Cloudinary::destroy($libro->$publicId);
                }
                $subida = $request->file($campo)->storeOnCloudinary('libros');
                $libro->$campo    = $subida->getSecurePath();
                $libro->$publicId = $subida->getPublicId();

            } elseif ($request->boolean("eliminar_$campo")) {
                if ($libro->$publicId) {
                    Cloudinary::destroy($libro->$publicId);
                }
                $libro->$campo    = null;
                $libro->$publicId = null;
            }
        }
        
        $libro->save();
        return redirect()->back()->with('success', 'Libro modificado exitosamente');


    }

    public function delete(Libro $libro) {
        $libro->activo = false;
        $libro->usuario_modificacion = Auth::id();
        $libro->save();
        return redirect() -> route('libros.index');
    }

    public function activar(Libro $libro)
    {
        $libro->activo = true;
        $libro->usuario_modificacion = Auth::id();
        $libro->save();

        return redirect()->route('libros.index')->with('success', 'Categoria reactivada correctamente');
    }


}
