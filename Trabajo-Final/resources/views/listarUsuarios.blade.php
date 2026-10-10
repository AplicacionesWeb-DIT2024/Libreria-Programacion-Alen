@extends('main')
@section('title', 'Listar usuarios')
@section('body')

@php $esSuper = auth()->user()->isSuperAdmin(); @endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Listado de usuarios</h1>

    @if ($esSuper)
        <button class="btn btn-success"
                title="Agregar usuario"
                data-bs-toggle="modal"
                data-bs-target="#modalAgregarUsuario">
            <i class="fas fa-plus"></i>
        </button>
    @endif

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
                         <span class="d-none">{{ $usuario->role->label() }}</span>
                        @switch($usuario->role)
                            @case(\App\Enums\Role::SuperAdmin)
                                <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis px-3">Superadmin</span>
                                @break
                            @case(\App\Enums\Role::Admin)
                                <span class="badge rounded-pill bg-primary-subtle text-primary px-3">Admin</span>
                                @break
                            @default
                                <span class="badge rounded-pill bg-secondary-subtle text-secondary px-3">Cliente</span>
                        @endswitch
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
                            @if ($esSuper)
                                @if ($usuario->id === auth()->id())
                                    <span class="badge bg-secondary" title="No se puede modificar tu propia cuenta">Tu cuenta</span>
                                @elseif ($usuario->activo)

                                    @if ($usuario->role === \App\Enums\Role::Admin)
                                        <form action="{{ route('usuarios.transferir', $usuario) }}" method="POST"
                                            onsubmit='return confirm(@json("Al realizar esta acción dejará de ser superadmin y {$usuario->name} pasará a serlo. ¿Está seguro de proceder?"))'>
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-warning btn-sm" title="Transferir superadmin">
                                                <i class="fas fa-crown"></i>
                                            </button>
                                        </form>
                                    @endif

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

    @if ($esSuper)

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
                        <button type="submit" form="formAgregarAdministrador" class="btn btn-primary">
                            Agregar administrador
                        </button>
                    </div>
                </div>
            </div>
        </div>

         @if ($errors->any() || session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    new bootstrap.Modal(document.getElementById('modalAgregarUsuario')).show();
                });
            </script>
        @endif
    @endif
@endsection
