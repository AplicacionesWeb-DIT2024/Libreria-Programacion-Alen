@extends('main')
@section('title', 'Listado de clientes')
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
            @foreach ($clientes as $usuario)
                <tr>
                    <td></td>
                    <td>{{ $usuario->name }}</td>
                    <td>{{ $usuario->apellido}} </td>
                    <td> {{ $usuario->username}} </td>
                    <td> {{ $usuario->email}} </td>
                    <td> {{ $usuario->cliente?->domicilio_completo ?? '—' }} </td>
                    <td>{{ $usuario->cliente?->telefono ?? '—' }}</td>
                    <td>
                        <span class="d-none">{{ $usuario->activo ? 'Activo' : 'Inactivo' }}</span>
                        @if ($usuario->activo)
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
                                data-bs-target="#modal{{ $usuario->id }}"
                                title="Ver usuario">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <a href="{{ route('clientes.pedidos', $usuario) }}" class="btn btn-primary btn-sm" title="Ver pedidos">
                                <i class="fas fa-receipt"></i>
                            </a>
                            @php $puede = auth()->user()->puedeGestionarA($usuario); @endphp
                            @if ($puede)
                                @if ($usuario->activo)
                                    <form action="{{ route('usuarios.desactivar', $usuario) }}"
                                        id="deleteForm{{ $usuario->id }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" class="btn btn-danger btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $usuario->id }}"
                                                title="Desactivar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('usuarios.activar', $usuario) }}" method="POST" class="d-inline">
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
                @if ($usuario->activo)
                    @include('delete_modal', ['id' => $usuario->id, 'nombre' => $usuario->username])
                @endif
                @include('modal-usuario', [
                    'id' => $usuario->id,
                    'nombre' => $usuario->name,
                    'apellido' => $usuario->apellido,
                    'usuario' => $usuario->username,
                    'email' => $usuario->email,
                    'esCliente' => $usuario->isCliente(),
                    'domicilio' => $usuario->cliente?->domicilio_completo ?? '—',
                    'telefono' => $usuario->cliente?->telefono ?? '—',
                    'creado' => $usuario->created_at->format('d/m/Y H:i:s'),
                    'actualizado' => $usuario->updated_at->format('d/m/Y H:i:s'),
                    'usuario_creacion' => optional($usuario->usuarioCreacion)->username,
                    'usuario_actualizacion' => optional($usuario->usuarioModificacion)->username ?? '—'
                ])
            @endforeach
        </tbody>
    </table>
@endsection
