@extends('main')
@section('title', 'Pedidos de ' . $usuario->username)
@section('body')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Pedidos de {{ $usuario->name }}, {{ $usuario->apellido }}</h1>
</div>

<div class= "table-responsive">
    <table id="tablaPedidos" class="table table-hover align-middle datatable">
            <thead class="table-light">
                <tr>
                    <th></th>
                    <th>Número de pedido</th>
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
                        <td data-order="{{ $factura->fecha->format('Y-m-d') }}">{{ $factura->fecha->format('d/m/Y') }}</td>
                        <td>{{ $factura->hora}} </td>
                        <td> {{ $factura->cantidadTotal}} </td>
                        <td>${{ number_format($factura->precioTotal, 2, ',', '.') }}</td>
                        <td> {{ $factura->domicilio_completo ?? '—' }} </td>
                        <td>
                            <span class="d-none">{{ $factura->estado->label() }}</span>
                            <span class="badge rounded-pill px-3 {{ $factura->estado->badgeClass() }}">
                                {{ $factura->estado->label() }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end align-items-center gap-1">
                                <a href="{{ route('pedidos.show', [$usuario, $factura]) }}" class="btn btn-info btn-sm" title="Ver detalle">
                                    <i class="fas fa-list"></i>
                                </a>
                                @if ($factura->estado->siguiente())
                                    <form action="{{ route('pedidos.avanzar', $factura) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-primary btn-sm text-nowrap" >
                                            <i class="fas fa-forward"></i> {{ $factura->estado->textoAccion() }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
    </table>
</div>
@endsection
