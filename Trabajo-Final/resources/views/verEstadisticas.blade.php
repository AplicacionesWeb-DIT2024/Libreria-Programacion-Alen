@extends('main')
@section('title', 'Estadisticas')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/estadisticas.css') }}">
@endsection
@section('body')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Estadisticas</h1>
</div>

<div class="row g-3 mb-4">
    @foreach ([
        ['total', 'Pedidos totales', 'primary'],
        ['aceptadas', 'Completados', 'success'],
        ['pendientes', 'Pendientes', 'warning'],
        ['canceladas', 'Cancelados', 'secondary'],
        ['rechazadas', 'Rechazados', 'danger'],
    ] as [$id, $titulo, $color])
        <div class="col-6 col-lg">
            <div class="card border-{{ $color }}">
                <div class="card-body text-center">
                    <div class="text-muted small">{{ $titulo }}</div>
                    <div class="fs-3 fw-bold text-{{ $color }}" id="k-{{ $id }}">–</div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card"><div class="card-body text-center">
            <div class="text-muted small">Ingresos del mes</div>
            <div class="fs-3 fw-bold" id="k-ingresos_mes">–</div>
        </div></div>
    </div>
    <div class="col-md-6">
        <div class="card"><div class="card-body text-center">
            <div class="text-muted small">Ingresos del año</div>
            <div class="fs-3 fw-bold" id="k-ingresos_anio">–</div>
        </div></div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="mb-0">Evolución de ventas</h5>
            <div class="btn-group btn-group-sm" id="grp-ventas">
                <button class="btn btn-outline-primary" data-v="semana">Semanal</button>
                <button class="btn btn-outline-primary active" data-v="mes">Mensual</button>
                <button class="btn btn-outline-primary" data-v="anio">Anual</button>
            </div>
        </div>
        <div class="chart-box chart-ventas">
            <canvas id="graficoVentas"></canvas>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h5 class="mb-0">Libros y categorías más comprados</h5>
    <div class="btn-group btn-group-sm" id="grp-periodo">
        <button class="btn btn-outline-primary" data-v="dia">Hoy</button>
        <button class="btn btn-outline-primary" data-v="semana">Últimos 7 dias</button>
        <button class="btn btn-outline-primary active" data-v="mes">Mes</button>
        <button class="btn btn-outline-primary" data-v="anio">Año</button>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card h-100"><div class="card-body">
            <h6>Top 5 libros</h6>
            <ol class="list-group" id="listaTop"></ol>
        </div></div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100"><div class="card-body">
            <h6>Categorías</h6>
            <div class="chart-box chart-categorias">
                <canvas id="graficoCategorias"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    window.ESTADISTICAS_URLS = {
        resumen: @json(route('estadisticas.resumen')),
        ventas: @json(route('estadisticas.ventas')),
        top: @json(route('estadisticas.topLibros')),
        categorias: @json(route('estadisticas.categorias')),
    };
</script>
<script src="{{ asset('js/estadisticas.js') }}"></script>
@endsection