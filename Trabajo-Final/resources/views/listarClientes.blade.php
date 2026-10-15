@extends('main')
@section('title', 'Listar clientes')
@section('body')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Listado de clientes</h1>
</div>

<table id="tablaClientes" class="table table-hover align-middle datatable">
        <thead class="table-light">
            <tr>
                <th></th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Usuario</th> 
                <th> Email </th> 
                <th> Domicilio </th> 
                <th> Telefono </th>
                <th>Estado</th>
                <th class="text-end">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clientes as $cliente)
                <tr>
                    <td></td>
                    <td>{{ $cliente->name }}</td>
                    <td>{{ $cliente->apellido}} </td>
                    <td> {{ $cliente->username}} </td>
                    <td> {{ $cliente->email}} </td>
                    <td> {{ $cliente->domicilio}} </td>
                    <td> {{ $cliente->telefono}} </td>
                    <td>
                        <span class="d-none">{{ $cliente->activo ? 'Activo' : 'Inactivo' }}</span>
                        @if ($cliente->activo)
                            <span class="badge rounded-pill bg-success-subtle text-success px-3">
                                Activo
                            </span>
                        @else
                            <span class="badge rounded-pill bg-danger-subtle text-danger px-3">
                                Inactivo
                            </span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <button type="button"
                                class="btn btn-info btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modal{{ $cliente->id }}"
                                title="Ver usuario">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <a href="#" class="btn btn-primary btn-sm" title="Ver pedidos">
                                <i class="fas fa-receipt"></i>
                            </a>
                            @php $puede = auth()->user()->puedeGestionarA($cliente); @endphp
                            @if ($puede)
                                @if ($cliente->activo)
                                    <form action="{{ route('usuarios.desactivar', $cliente) }}"
                                        id="deleteForm{{ $cliente->id }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" class="btn btn-danger btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $cliente->id }}"
                                                title="Desactivar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('usuarios.activar', $cliente) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm" title="Reactivar">
                                            <i class="fas fa-rotate-left"></i>
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @if ($cliente->activo)
                    @include('delete_modal', ['id' => $cliente->id, 'nombre' => $cliente->username])
                @endif
                @include('modal-usuario', [
                    'id' => $cliente->id,
                    'nombre' => $cliente->name,
                    'apellido' => $cliente->apellido,
                    'usuario' => $cliente->username,
                    'email' => $cliente->email,
                    'esCliente' => $cliente->isCliente(),
                    'domicilio' => $cliente->domicilio ?? '—',
                    'telefono' => $cliente->telefono ?? '—',
                    'creado' => $cliente->created_at->format('d/m/Y H:i:s'),
                    'actualizado' => $cliente->updated_at->format('d/m/Y H:i:s'),
                    'usuario_creacion' => optional($cliente->usuarioCreacion)->username,
                    'usuario_actualizacion' => optional($cliente->usuarioModificacion)->username ?? '—'
                ])
            @endforeach
        </tbody>
    </table>
@endsection
