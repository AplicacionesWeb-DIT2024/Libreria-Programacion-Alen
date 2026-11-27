<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Libro; 

class ListaDeseadosController extends Controller
{
    public function index(Request $request) {
        $cliente = $this->validarCliente($request);  
        $deseados = $this->armarLista($cliente);
        return response()->json($deseados);
    }


     public function agregar(Request $request, Libro $libro){
        $cliente = $this->validarCliente($request);

        if (!$libro->activo) {
            return response()->json(['error' => 'El libro ya no está disponible.'], 422);
        }

        $cliente->deseados()->syncWithoutDetaching([$libro->id]);

        return response()->json($this->armarLista($cliente));
    }

    public function quitar(Request $request, Libro $libro)
    {
        $cliente = $this->validarCliente($request);
        $cliente->deseados()->detach($libro->id);

        return response()->json($this->armarLista($cliente));
    }


    private function validarCliente(Request $request): User{
        $usuario = $request->user();
        abort_unless($usuario->isCliente() && $usuario->activo, 403, 'El usuario no posee permisos para realizar esta acción',);
        return $usuario;
    }

    private function armarLista(User $cliente): array{
        $libros = $cliente->deseados()
            ->with([
                'categoria_perteneciente',
                'subcategoria_perteneciente',
                'editorial_perteneciente',
                'autor_origen',
                'autor_secundario',
                'autor_secundario2',
            ])
            ->orderByPivot('created_at', 'desc')
            ->get()
            ->map(fn (Libro $libro) => [
                'id'                  => $libro->id,
                'nombre'              => $libro->nombre,
                'idioma'              => $libro->idioma,
                'edicion'             => $libro->edicion,
                'sin_stock'           => $libro->stock <= 0,
                'anio_publicacion'    => $libro->anio_publicacion,
                'precio'              => (float) $libro->precio,
                'categoria'           => $libro->categoria_perteneciente?->nombre,
                'subcategoria'        => $libro->subcategoria_perteneciente?->nombre,
                'editorial'           => $libro->editorial_perteneciente?->nombre,
                'autor'               => implode(', ', $libro->getAutores()),
                'imagen_original'     => $libro->imagen_original,
                'imagen_referencia_2' => $libro->imagen_referencia_2,
                'imagen_referencia_3' => $libro->imagen_referencia_3,
                'disponible'          => $this->estaDisponible($libro),
            ]);

        return [
            'items' => $libros,
            'cantidad' => $libros->count(),
        ];
    }

    private function estaDisponible(Libro $libro): bool{
        return $libro->activo && $libro->categoria_perteneciente?->activo && $libro->subcategoria_perteneciente?->activo
        && $libro->editorial_perteneciente?->activo && $libro->autor_origen?->activo && (!$libro->autor2 || $libro->autor_secundario?->activo)
            && (!$libro->autor3 || $libro->autor_secundario2?->activo);
    }
}
