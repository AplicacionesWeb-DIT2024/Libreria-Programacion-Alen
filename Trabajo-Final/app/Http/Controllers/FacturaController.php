<?php

namespace App\Http\Controllers;

use App\Models\DetalleFactura;
use App\Models\Factura;
use App\Models\Libro;
use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\EstadoFactura;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\PedidoInvalidoException;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentRefundClient;

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

    public function listarPorCliente(User $usuario) {
        abort_unless($usuario->isCliente(), 404);
        $facturas = $usuario->facturas()
                    ->orderByDesc('created_at')
                    ->get();
        return view('listarPedidos', compact('usuario', 'facturas'));
    }


    public function show(User $usuario, Factura $factura)
    {
        abort_unless($usuario->isCliente(), 404);

        $factura->load([
            'usuarioCliente',
            'detalles.datosLibro.autor_origen',
            'detalles.datosLibro.autor_secundario',
            'detalles.datosLibro.autor_secundario2',
            'detalles.datosLibro.editorial_perteneciente',
        ]);

        return view('listarDetallesPedido', compact('usuario', 'factura'));
    }


    public function showPedidos(Factura $factura)
    {

        $factura->load([
            'usuarioCliente',
            'detalles.datosLibro.autor_origen',
            'detalles.datosLibro.autor_secundario',
            'detalles.datosLibro.autor_secundario2',
            'detalles.datosLibro.editorial_perteneciente',
        ]);

        return view('listarDetalles', compact('factura'));
    }

    public function index()
    {
        $facturas = Factura::with('usuarioCliente')
            ->orderByDesc('created_at')
            ->get();

        return view('listarPedidosClientes', compact('facturas'));
    }



    public function avanzarEstado(Factura $factura)
    {
        $actual = $factura->estado;
        $siguiente = $actual->siguiente();

        if ($siguiente === null) {
            return back()->with('error', 'Este pedido no puede avanzar de estado.');
        }

        $cambiadas = Factura::where('id', $factura->id)
            ->where('estado', $actual)
            ->update(['estado' => $siguiente]);

        if ($cambiadas === 0) {
            return back()->with('error', 'El pedido ya fue modificado. Actualice la página');
        }

        return back()->with('success', "Pedido #{$factura->id} - Estado {$siguiente->label()}.");
    }


    
    public function store(Request $request)
    {
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
            [$factura, $preferencia] = DB::transaction(function () use ($request, $cliente) {
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
                $itemsMp = [];

                foreach ($detalles as $libroId => $cantidad) {
                    $libro = Libro::find($libroId);

                    if ($libro === null) {
                        throw new PedidoInvalidoException(
                            'Uno de los libros del carrito ya no está disponible.',
                            ['libro' => (int) $libroId, 'disponible' => 0],
                        );
                    }

                    $afectadas = Libro::where('id', $libroId)
                        ->where('stock', '>=', $cantidad)
                        ->decrement('stock', $cantidad);

                    if ($afectadas === 0) {
                        $disponible = (int) Libro::whereKey($libroId)->value('stock');

                        throw new PedidoInvalidoException(
                            $disponible === 0
                                ? "\"{$libro->nombre}\" ya no tiene stock."
                                : "De \"{$libro->nombre}\" solo quedan {$disponible}.",
                            ['libro' => $libro->id, 'disponible' => $disponible],
                        );
                    }

                    $precioTotal = $libro->precio * $cantidad;

                    DetalleFactura::create([
                        'libro' => $libro->id,
                        'factura' => $factura->id,
                        'precio_unitario' => $libro->precio,
                        'cantidad' => $cantidad,
                        'precio_total' => $precioTotal,
                    ]);

                    $itemsMp[] = array_filter([
                        'id' => (string) $libro->id,
                        'title' => mb_substr(
                            $cantidad > 1 ? "{$libro->nombre} (x{$cantidad})" : $libro->nombre,
                            0,
                            250,
                        ),
                        'description' => mb_substr("Libro: {$libro->nombre}", 0, 250),
                        'picture_url' => $this->urlImagenPublica($libro),
                        'category_id' => 'others',
                        'quantity' => 1,
                        'unit_price' => round((float) $precioTotal, 2),
                        'currency_id' => 'ARS',
                    ], fn ($valor) => $valor !== null);

                    $cantidadTotal += $cantidad;
                    $total += $precioTotal;
                }

                $factura->update(['precioTotal' => $total, 'cantidadTotal' => $cantidadTotal]);

                $preferencia = $this->crearPreferencia($factura, $itemsMp);

                return [$factura, $preferencia];
            });

            return response()->json([
                'message' => 'Factura creada correctamente',
                'id' => $factura->id,
                'estado' => $factura->estado,
                'init_point' => $preferencia->init_point,
                'vence_en' => $this->vencimiento($factura)->toIso8601String(),
            ], 201);
        } catch (MPApiException $error) {
            Log::error('Mercado Pago: error al crear la preferencia', [
                'status' => $error->getApiResponse()?->getStatusCode(),
                'respuesta' => $error->getApiResponse()?->getContent(),
            ]);

            return response()->json(['error' => 'No se pudo iniciar el pago con Mercado Pago. Intente de nuevo.'], 502);
        } catch (PedidoInvalidoException $error) {
            return response()->json(['error' => $error->getMessage(), ...$error->datos], 409);
        } catch (\Throwable $error) {
            report($error); 
            return response()->json(['error' => 'No se pudo crear el pedido. Intente de nuevo.'], 500);
        }
    }



    public function cancelarPedido(Request $request, Factura $factura)
    {
        if ((int) $factura->cliente !== (int) $request->user()->id) {
            return response()->json(['error' => 'Pedido no encontrado'], 404);
        }

        $cancelada = $factura->cerrarComo(EstadoFactura::Cancelada);

        return response()->json([
            'cancelada' => $cancelada,
            'estado' => $factura->fresh()->estado,
        ]);
    }

    public function webhookMercadoPago(Request $request)
    {
        $tipo = $request->input('type') ?? $request->query('topic');
        $pagoId = $request->input('data.id') ?? $request->query('data_id') ?? $request->query('id');

        if ($tipo !== 'payment' || !$pagoId) {
            return response()->json(['ok' => true]);
        }

        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

        try {
            $pago = (new PaymentClient())->get((int) $pagoId);
        } catch (MPApiException $error) {
            Log::warning('Mercado Pago: no se pudo consultar el pago', ['id' => $pagoId]);

            return response()->json(['error' => 'No se pudo consultar el pago'], 500); 
        }

        $factura = Factura::find($pago->external_reference);

        if (!$factura) {
            return response()->json(['ok' => true]);
        }

        if ($pago->status === 'approved') {
            $aceptada = $factura->cerrarComo(EstadoFactura::Aceptada);

            if (!$aceptada) {
                $estadoActual = $factura->fresh()->estado;

                if ($estadoActual !== EstadoFactura::Aceptada) {
                    try {
                        (new PaymentRefundClient())->refundTotal($pago->id);

                        Log::warning('Mercado Pago: pago devuelto por factura no pendiente', [
                            'factura' => $factura->id,
                            'estado' => $estadoActual->value,
                            'pago' => $pago->id,
                        ]);
                    } catch (MPApiException $error) {
                        Log::critical('Mercado Pago: NO se pudo devolver el pago, hacerlo a mano', [
                            'factura' => $factura->id,
                            'estado' => $estadoActual->value,
                            'pago' => $pago->id,
                            'respuesta' => $error->getApiResponse()?->getContent(),
                        ]);
                    }
                }
            }
        }

        return response()->json(['ok' => true]);
    }

    private function vencimiento(Factura $factura)
    {
        return $factura->created_at->copy()->addMinutes((int) config('pedidos.minutos_vencimiento'));
    }

    private function crearPreferencia(Factura $factura, array $items)
    {
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

        $frontend = rtrim(config('services.mercadopago.frontend_url'), '/');

        $datos = [
            'items' => $items,
            'external_reference' => (string) $factura->id,
            'back_urls' => [
                'success' => "{$frontend}/?pago=aprobado",
                'pending' => "{$frontend}/?pago=pendiente",
                'failure' => "{$frontend}/?pago=rechazado",
            ],
            'notification_url' => route('mercadopago.webhook'),

            'expires' => true,
            'expiration_date_from' => $factura->created_at->format('Y-m-d\TH:i:s.vP'),
            'expiration_date_to' => $this->vencimiento($factura)->format('Y-m-d\TH:i:s.vP'),

            'payment_methods' => [
                'excluded_payment_types' => [
                    ['id' => 'ticket'],
                    ['id' => 'atm'],
                ],
            ],
        ];

        if (str_starts_with($frontend, 'https://')) {
            $datos['auto_return'] = 'approved';
        }

        return (new PreferenceClient())->create($datos);
    }


    private function urlImagenPublica(Libro $libro): ?string
    {
        $url = $libro->imagen_original;

        if (!$url || !str_contains($url, '/upload/')) {
            return null;
        }

        return str_replace('/upload/', '/upload/w_300,h_300,c_pad,b_white/', $url);
    }


}
