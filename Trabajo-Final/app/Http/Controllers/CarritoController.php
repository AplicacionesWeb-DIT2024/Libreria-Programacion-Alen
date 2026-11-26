<?php

namespace App\Http\Controllers;
use App\Models\Carrito;
use App\Models\Libro;
use App\Models\Factura;
use App\Enums\EstadoFactura;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CarritoController extends Controller
{

    private const CANTIDAD_MAXIMA = 99;


    public function index(Request $request) {
        $cliente = $this->validarCliente($request);  
        $carrito = $this->armarCarrito($cliente);
        return response()->json($carrito);
    }



     public function actualizar(Request $request, Libro $libro){
        $cliente = $this->validarCliente($request);

        $datos = $request->validate([
            'cantidad' => ['required', 'integer', 'min:0', 'max:' . self::CANTIDAD_MAXIMA],
        ], [
            'cantidad.required' => 'Indique la cantidad.',
            'cantidad.integer' => 'La cantidad tiene que ser un número entero.',
            'cantidad.min' => 'La cantidad no puede ser negativa',
            'cantidad.max' => 'No se pueden agregar más de ' . self::CANTIDAD_MAXIMA . ' unidades de un mismo libro.',
        ]);

         if ($datos['cantidad'] > 0 && !$libro->activo) {
            return response()->json(['error' => 'Este libro ya no está disponible.'], 422);
        }

        DB::transaction(function () use ($cliente, $libro, $datos) {
            $this->cancelarPedidoPendiente($cliente);

            if ($datos['cantidad'] === 0) {
                Carrito::where('user_id', $cliente->id)->where('libro_id', $libro->id)->delete();
            } else {
                Carrito::updateOrCreate(
                    ['user_id' => $cliente->id, 'libro_id' => $libro->id],
                    ['cantidad' => $datos['cantidad']],
                );
            }
        });
        return response()->json($this->armarCarrito($cliente));
    }

     public function quitar(Request $request, Libro $libro){
        $cliente = $this->validarCliente($request);

        DB::transaction(function () use ($cliente, $libro) {
            $this->cancelarPedidoPendiente($cliente);
            Carrito::where('user_id', $cliente->id)->where('libro_id', $libro->id)->delete();
        });

        return response()->json($this->armarCarrito($cliente));
    }


    public function vaciar(Request $request){
        $cliente = $this->validarCliente($request);
        DB::transaction(function () use ($cliente) {
            $this->cancelarPedidoPendiente($cliente);
            Carrito::where('user_id', $cliente->id)->delete();
        });
        return response()->json($this->armarCarrito($cliente));
    }

    public function fusionar(Request $request){
        $cliente = $this->validarCliente($request);
        $datos = $request->validate([
            'items' => ['present', 'array', 'max:100'],
            'items.*.libro' => ['required', 'integer', 'distinct', 'exists:libros,id'],
            'items.*.cantidad' => ['required', 'integer', 'min:1', 'max:' . self::CANTIDAD_MAXIMA],
        ]);

        DB::transaction(function () use ($datos, $cliente) {
            foreach ($datos['items'] as $nuevo) {
                $item = Carrito::firstOrNew([
                    'user_id' => $cliente->id,
                    'libro_id' => $nuevo['libro'],
                ]);

                $item->cantidad = min(self::CANTIDAD_MAXIMA, max($item->cantidad ?? 0, $nuevo['cantidad']));
                $item->save();
            }
        });

        return response()->json($this->armarCarrito($cliente));
    }

    private function validarCliente(Request $request): User{
        $usuario = $request->user();
        abort_unless($usuario->isCliente() && $usuario->activo, 403, 'El usuario no posee permisos para realizar esta acción',);
        return $usuario;
    }


    private function armarCarrito(User $cliente): array{
        $items = Carrito::with('libro')          
            ->where('user_id', $cliente->id)         
            ->orderBy('id')
            ->get()
            ->filter(fn ($item) => $item->libro && $item->libro->activo)   
            ->map(function ($item) {
                $precio = (float) $item->libro->precio;
                return [
                    'id' => $item->libro->id,
                    'nombre' => $item->libro->nombre,
                    'precio' => $precio,
                    'imagen' => $item->libro->imagen_original,
                    'quantity' => $item->cantidad,
                    'subtotal' => round($precio * $item->cantidad, 2),
                ];
            })
            ->values();
        return ['items' => $items,'total' => round($items->sum('subtotal'), 2),
        'cantidad' => $items->sum('quantity'), $this->pedidoPendiente($cliente)];
    }

    private function cancelarPedidoPendiente(User $cliente): void{
        Factura::where('cliente', $cliente->id)
            ->where('estado', EstadoFactura::Pendiente)
            ->get()
            ->each(fn (Factura $factura) => $factura->cerrarComo(EstadoFactura::Cancelada));
    }


     private function pedidoPendiente(User $cliente): ?array {
        $minutos = (int) config('pedidos.minutos_vencimiento');

        $factura = Factura::where('cliente', $cliente->id)
            ->where('estado', EstadoFactura::Pendiente)
            ->whereNotNull('mp_init_point')
            ->where('created_at', '>', now()->subMinutes($minutos))
            ->latest('id')
            ->first();

        if (!$factura) {
            return null;
        }

        return [
            'id' => $factura->id,
            'init_point' => $factura->mp_init_point,
            'vence_en' => $factura->created_at->copy()->addMinutes($minutos)->toIso8601String(),
        ];
    }
}
