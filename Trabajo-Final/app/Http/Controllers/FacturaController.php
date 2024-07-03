<?php

namespace App\Http\Controllers;

use App\Models\DetalleFactura;
use App\Models\Factura;
use App\Models\Libro;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\throwException;

class FacturaController extends Controller
{
    //
    /*
    {Viene por React
    fecha:'',
    id_cliente: numerico
    detalles: [{libro_id: 'id', cant: numerico}]
    }
     */


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


        DB::beginTransaction();
        try {
            $factura = new Factura();
            $cliente = User::find($request->id_cliente);
            if ($cliente != null) {
                $factura->cliente = $cliente->id;
                $factura->fecha = $request->fecha; 
                $factura->save();

                $precio_total_factura = 0;
                foreach ($request->detalles as $detalle) {
                    $libro = Libro::find($detalle['libro']);
                    if ($libro != null) {
                        $precio_libro = $libro->precio;
                        $cantidad = $detalle['cantidad']; 
                        if (($libro->stock - $cantidad) >= 0) {
                            $precio_total = $precio_libro * $cantidad;
                            $detalleFactura = new DetalleFactura;
                            $detalleFactura->libro = $libro->id;
                            $detalleFactura->factura = $factura->id;
                            $detalleFactura->precio_unitario = $precio_libro;
                            $detalleFactura->cantidad = $cantidad;
                            $detalleFactura->precio_total = $precio_total;
                            $detalleFactura->save();
                            
                            $libro->stock -= $cantidad; 
                            $libro->save();

                            $precio_total_factura += $precio_total;
                        }
                        else {
                            throw new Exception ("Stock insuficiente para el libro: " . $libro->nombre);
                            break;
                        }
                    }
                    else {
                        throw new Exception ("No existe el libro");
                    }
                }
                $factura->precioTotal = $precio_total_factura;
                $factura->save();
                DB::commit();
                return response()->json(['message' => "Factura creada correctamente"], 200);

            }
            else {
                throw new Exception("No existe el cliente ingresado");
            }
    
        }catch (\Exception $error) {
            DB::rollback();
            return response()->json(['error' => $error->getMessage()], 400);
        } 
    }
}
