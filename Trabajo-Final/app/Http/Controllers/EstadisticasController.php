<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Enums\EstadoFactura;
use App\Models\Factura;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EstadisticasController extends Controller
{
     public function index()
    {
        return view('verEstadisticas');
    }

    public function resumen()
    {
        $porEstado = Factura::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado');

        $aceptada = EstadoFactura::Aceptada;

        $ingresosMes = Factura::where('estado', $aceptada)
            ->whereBetween('fecha', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
            ->sum('precioTotal');

        $ingresosAnio = Factura::where('estado', $aceptada)
            ->whereBetween('fecha', [now()->startOfYear()->toDateString(), now()->endOfYear()->toDateString()])
            ->sum('precioTotal');

        return response()->json([
            'total'      => $porEstado->sum(),
            'aceptadas'  => $porEstado[EstadoFactura::Aceptada->value] ?? 0,
            'pendientes' => $porEstado[EstadoFactura::Pendiente->value] ?? 0,
            'canceladas' => $porEstado[EstadoFactura::Cancelada->value] ?? 0,
            'rechazadas' => $porEstado[EstadoFactura::Rechazada->value] ?? 0,
            'ingresos_mes'  => (float) $ingresosMes,
            'ingresos_anio' => (float) $ingresosAnio,
        ]);
    }


     public function ventas(Request $request){
        $periodo = in_array($request->query('periodo'), ['semana', 'mes', 'anio']) ? $request->query('periodo') : 'mes';

        $desde = match ($periodo) {
            'semana' => now()->startOfWeek()->subWeeks(11),
            'mes'    => now()->startOfMonth()->subMonths(11),
            'anio'   => now()->startOfYear()->subYears(4),
        };

        $facturas = Factura::where('estado', EstadoFactura::Aceptada)
            ->where('fecha', '>=', $desde->toDateString())
            ->get(['fecha', 'precioTotal']);

        $clave = fn (Carbon $f) => match ($periodo) {
            'semana' => $f->copy()->startOfWeek()->format('Y-m-d'),
            'mes'    => $f->format('Y-m'),
            'anio'   => $f->format('Y'),
        };

        $buckets = [];
        $n = $periodo === 'anio' ? 5 : 12;
        for ($i = 0; $i < $n; $i++) {
            $d = match ($periodo) {
                'semana' => $desde->copy()->addWeeks($i),
                'mes'    => $desde->copy()->addMonths($i),
                'anio'   => $desde->copy()->addYears($i),
            };
            $etiqueta = match ($periodo) {
                'semana' => $d->format('d/m'),
                'mes'    => $d->translatedFormat('M Y'),
                'anio'   => $d->format('Y'),
            };
            $buckets[$clave($d)] = ['label' => $etiqueta, 'ingresos' => 0, 'pedidos' => 0];
        }

        foreach ($facturas as $f) {
            $k = $clave($f->fecha);
            if (isset($buckets[$k])) {
                $buckets[$k]['ingresos'] += $f->precioTotal;
                $buckets[$k]['pedidos']++;
            }
        }

        $buckets = array_values($buckets);

        return response()->json([
            'labels'   => array_column($buckets, 'label'),
            'ingresos' => array_map(fn ($b) => round($b['ingresos'], 2), $buckets),
            'pedidos'  => array_column($buckets, 'pedidos'),
        ]);
    }

    public function topLibros(Request $request)
    {
        [$desde, $hasta] = $this->rango($request->query('periodo'));

        $libros = $this->detallesAceptados($desde, $hasta)
            ->join('libros as l', 'l.id', '=', 'd.libro')
            ->groupBy('l.id', 'l.nombre')
            ->orderByDesc('total')
            ->limit(5)
            ->get(['l.nombre', DB::raw('SUM(d.cantidad) as total')]);

        return response()->json($libros);
    }

    public function categorias(Request $request)
    {
        [$desde, $hasta] = $this->rango($request->query('periodo'));

        $cats = $this->detallesAceptados($desde, $hasta)
            ->join('libros as l', 'l.id', '=', 'd.libro')
            ->join('categorias as c', 'c.id', '=', 'l.categoria')
            ->groupBy('c.id', 'c.nombre')
            ->orderByDesc('total')
            ->get(['c.nombre', DB::raw('SUM(d.cantidad) as total')]);

        return response()->json($cats);
    }

    private function detallesAceptados(Carbon $desde, Carbon $hasta)
    {
        return DB::table('detalle_facturas as d')
            ->join('facturas as f', 'f.id', '=', 'd.factura')
            ->where('f.estado', EstadoFactura::Aceptada->value)
            ->whereBetween('f.fecha', [$desde->toDateString(), $hasta->toDateString()]);
    }

     private function rango(?string $periodo): array{
        return match ($periodo) {
            'dia'    => [now()->startOfDay(), now()->endOfDay()],
            'semana' => [now()->subDays(6)->startOfDay(), now()->endOfDay()], 
            'anio'   => [now()->startOfYear(), now()->endOfYear()],
            default  => [now()->startOfMonth(), now()->endOfMonth()],
        };
    }


}
