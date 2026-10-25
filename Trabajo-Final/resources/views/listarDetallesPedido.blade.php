@extends('main')
@section('title', 'Pedido #' . $factura->id)
@section('body')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Pedido #{{ $factura->id}} </h1>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-2"><b>Cliente:</b> {{ $factura->usuarioCliente->name }} , {{ $factura->usuarioCliente->apellido }}</div>
                <div class="mb-2"><b>Fecha:</b> {{ $factura->fecha->format('d/m/Y') }} - {{ $factura->hora }}</div>
                <div class="mb-2"><b>Estado:</b> {{ $factura->estado->label() }}</div>
            </div>
            <div class="col-md-6">
                <div class="mb-2"><b>Envío a:</b> {{ $factura->domicilio_completo }}</div>
                <div class="mb-2"><b>Cantidad de productos:</b> {{ $factura->cantidadTotal }}</div>
                <div class="mb-2"><b>Total:</b> ${{ number_format($factura->precioTotal, 2, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

<table id="tablaDetalles" class="table table-hover align-middle datatable">
        <thead class="table-light">
           <tr>
                <th></th>
                <th>Libro</th>
                <th>Autor/es</th>
                <th>Editorial</th>
                <th>Precio unitario</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($factura->detalles as $detalle)
                @php $libro = $detalle->datosLibro; @endphp
            <tr>
                <td></td>
                <td>{{ $libro->nombre ?? 'Libro eliminado' }}</td>
                <td>{{ $libro ? implode(', ', $libro->getAutores()) : '—' }}</td>
                <td>{{ $libro?->editorial_perteneciente?->nombre ?? '—' }}</td>
                <td data-order="{{ $detalle->precio_unitario }}">${{ number_format($detalle->precio_unitario, 2, ',', '.') }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td data-order="{{ $detalle->precio_total }}">${{ number_format($detalle->precio_total, 2, ',', '.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection