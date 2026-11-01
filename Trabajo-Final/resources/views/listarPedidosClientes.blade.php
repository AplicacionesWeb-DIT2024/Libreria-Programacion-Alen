@extends('main')
@section('title', 'Listado de pedidos')
@section('body')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Listado de pedidos</h1>
</div>

<table id="tablaPedidosClientes" class="table table-hover align-middle datatable">
        <thead class="table-light">
            <tr>
                <th></th>
                <th>Número de pedido</th>
                <th>Cliente</th>
                <th>Fecha de compra</th>
                <th>Hora de compra</th> 
                <th> Cantidad de productos</th> 
                <th> Precio Total </th> 
                <th> Domicilio de envío </th>
                <th> Estado</th>
                <th class="text-end">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($facturas as $factura)
                <tr>
                    <td></td>
                    <td>{{ $factura->id }}</td>
                    <td>{{ $factura->usuarioCliente->name }} , {{ $factura->usuarioCliente->apellido }}</td>

                    <td data-order="{{ $factura->fecha->format('Y-m-d') }}">{{ $factura->fecha->format('d/m/Y') }}</td>
                    <td>{{ $factura->hora}} </td>
                    <td> {{ $factura->cantidadTotal}} </td>
                    <td>${{ number_format($factura->precioTotal, 2, ',', '.') }}</td>
                    <td> {{ $factura->domicilio_completo ?? '—' }} </td>
                    <td>
                        <span class="d-none">{{ $factura->estado->label() }}</span>
                        @switch($factura->estado)
                            @case(\App\Enums\EstadoFactura::Aceptada)
                                <span class="badge rounded-pill bg-success-subtle text-success px-3">Aceptada</span>
                                @break
                            @case(\App\Enums\EstadoFactura::Pendiente)
                                <span class="badge rounded-pill bg-warning-subtle text-warning px-3">Pendiente</span>
                                @break
                            @case(\App\Enums\EstadoFactura::Rechazada)
                                <span class="badge rounded-pill bg-danger-subtle text-danger px-3">Rechazada</span>
                                @break
                            @default
                                <span class="badge rounded-pill bg-secondary-subtle text-secondary px-3">Cancelada</span>
                        @endswitch
                    </td>
                    <td class="text-end">
                        <a href="{{ route('pedidos.detalle', [$factura]) }}" class="btn btn-info btn-sm" title="Ver detalle">
                            <i class="fas fa-list"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
