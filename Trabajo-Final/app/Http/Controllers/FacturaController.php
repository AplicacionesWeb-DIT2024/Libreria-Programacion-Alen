<?php

namespace App\Http\Controllers;

use App\Models\DetalleFactura;
use App\Models\Factura;
use App\Models\Libro;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Enums\EstadoFactura;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\throwException;

class FacturaController extends Controller
{


    public function index_api() {
            $facturas = Factura::orderBy('id', 'asc') -> get();
            return response()->json($facturas, 200);
    
    }

    public function show_api(Request $request) {
        $factura = Factura::where ('id', $request->id) -> with('detalles')-> first();
        if ($factura) {
            return response()->json($factura, 200);
        } else {
            return response()->json(['message' => 'factura no encontrada'], 404);

        }

    }


    
   
    public function store (Request $request) {

        $cliente = $request->user();
        if (!$cliente->isCliente() || !$cliente->activo) {
            return response()->json(['error' => 'El usuario no posee permisos para realizar esta acción'], 403);
        }


        $request->validate([
            'detalles' => ['required', 'array', 'min:1'],
            'detalles.*.libro' => ['required', 'integer', 'exists:libros,id'],
            'detalles.*.cantidad' => ['required', 'integer', 'min:1'],
            'domicilio_calle' => ['required', 'string', 'max:255'],
            'domicilio_numero' => ['required', 'string', 'max:20'],
            'domicilio_localidad' => ['required', 'string', 'max:255'],
            'domicilio_provincia' => ['required', 'string', 'max:255'],
            'domicilio_codigo_postal' => ['required', 'digits:4'],
        ]);

        try {
            $factura = DB::transaction(function () use ($request,$cliente) {

                $factura = Factura::create([
                    'cliente' => $cliente->id,
                    'fecha' => now()->toDateString(),
                    'hora' => now()->toTimeString(),
                    'estado' => EstadoFactura::Pendiente,
                    ...$request->only([
                        'domicilio_calle',
                        'domicilio_numero',
                        'domicilio_localidad',
                        'domicilio_provincia',
                        'domicilio_codigo_postal',
                    ]),
                ]);

                $detalles = collect($request->detalles)
                    ->groupBy('libro')
                    ->map(fn ($items) => $items->sum('cantidad'))
                    ->sortKeys();

                $total = 0;
                $cantidadTotal = 0;

                foreach ($detalles as $libroId => $cantidad) {
                    $libro = Libro::find($libroId);

                    if ($libro === null) {
                        throw new Exception("El libro con id {$libroId} no existe");
                    }

                    $afectadas = Libro::where('id', $libroId)
                        ->where('stock', '>=', $cantidad)
                        ->decrement('stock', $cantidad);

                    if ($afectadas === 0) {
                        throw new Exception("Stock insuficiente para el libro: " . $libro->nombre);
                    }

                    $precioTotal = $libro->precio * $cantidad;

                    DetalleFactura::create([
                        'libro' => $libro->id,
                        'factura' => $factura->id,
                        'precio_unitario' => $libro->precio,
                        'cantidad' => $cantidad,
                        'precio_total' => $precioTotal,
                    ]);


                    $cantidadTotal += $cantidad;
                    $total += $precioTotal;
                }

                $factura->update(['precioTotal' => $total, 'cantidadTotal' => $cantidadTotal]);
                 return $factura;

            });
            return response()->json(
                ['message' => "Factura creada correctamente",
                'id' => $factura->id,
                'estado'=> $factura->estado],
                200);
    
        }catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 400);
        } 
    }
}
