@extends('main')
@section('title', 'Listar usuarios')
@section('body')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Listado de usuarios</h1>

    <button class="btn btn-success"
            title="Agregar usuario"
            data-bs-toggle="modal"
            data-bs-target="#modalAgregarUsuario">
        <i class="fas fa-plus"></i>
    </button>
</div>


    <table id="tablaUsuarios" class="table table-hover align-middle datatable">
        <thead class="table-light">
            <tr>
                <th></th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Usuario</th> 
                <th>Rol</th> 
                <th>Estado</th>
                <th class="text-end">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
                <tr>
                    <td></td>
                    <td>{{ $usuario->name }}</td>
                    <td>{{ $usuario->apellido}} </td>
                    <td> {{ $usuario->username}} </td>
                    <td>
                        <span class="d-none">{{ $usuario->is_admin ? 'Admin' : 'Cliente' }}</span>
                        @if ($usuario->is_admin)
                            <span class="badge rounded-pill bg-primary-subtle text-primary px-3">
                                Admin
                            </span>
                        @else
                            <span class="badge rounded-pill bg-secondary-subtle text-secondary px-3">
                                Cliente
                            </span>
                        @endif
                    </td>
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
                            @if ($usuario->activo)
                                @if ($usuario->id !== auth()->id())
                                    <form action="{{ route('usuarios.delete', $usuario) }}"
                                        id="deleteForm{{ $usuario->id }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="button"
                                                class="btn btn-danger btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $usuario->id }}"
                                                title="Desactivar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="badge bg-secondary" title="No se puede desactivar tu propia cuenta">
                                        Tu cuenta
                                    </span>
                                @endif
                            @else
                                <form action="{{ route('usuarios.activar', $usuario) }}"
                                    method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="btn btn-success btn-sm"
                                            title="Reactivar">
                                        <i class="fas fa-rotate-left"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @include('delete_modal', ['id' => $usuario->id, 'nombre' => $usuario->username])
                @include('modal-usuario', [
                    'id' => $usuario->id,
                    'nombre' => $usuario->name,
                    'apellido' => $usuario->apellido,
                    'usuario' => $usuario->username,
                    'domicilio' => $usuario->domicilio,
                    'creado' => $usuario->created_at->format('d/m/Y H:i:s'),
                    'actualizado' => $usuario->updated_at->format('d/m/Y H:i:s'),
                    'usuario_creacion' => optional($usuario->usuarioCreacion)->username,
                    'usuario_actualizacion' => optional($usuario->usuarioModificacion)->username ?? '—'
                ])
            @endforeach
        </tbody>
    </table>

<div class="modal fade" id="modalAgregarUsuario" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fa-solid fa-tags"></i> <h5 class="modal-title"><i class="fa-solid fa-building-circle-plus opacity-75"></i> Agregar usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @include('_formAgregarUsuario')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
