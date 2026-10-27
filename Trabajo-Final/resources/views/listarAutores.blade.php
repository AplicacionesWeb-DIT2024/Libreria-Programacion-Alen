@extends('main')
@section('title', 'Listar autores')
@section('js')
    <script src="{{ asset('js/modalEditar.js') }}"></script>
@endsection
@section('body')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Listado de autores</h1>

    <button class="btn btn-success"
            title="Agregar autor"
            data-bs-toggle="modal"
            data-bs-target="#modalAgregarAutor">
        <i class="fas fa-plus"></i>
    </button>
</div>

<table id="tablaAutores" class="table table-hover align-middle datatable">
        <thead class="table-light">
            <tr>
                <th></th>
                <th> Nombre </th>
                <th> Apellido </th>
                <th> Fecha de nacimiento </th> 
                <th> Pais </th> 
                <th> Estado </th>
                <th class="text-end">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($autores as $autor)
                <tr>
                    <td></td>
                    <td>{{ $autor->nombre }}</td>
                    <td>{{ $autor->apellido }}</td>
                    <td>{{ $autor->fecha_nacimiento->format('d/m/Y') }}</td>
                    <td>{{ $autor->pais_origen }}</td>
                    <td>
                        <span class="d-none">{{ $autor->activo ? 'Activo' : 'Inactivo' }}</span>
                        @if ($autor->activo)
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
                                data-bs-target="#modal{{ $autor->id }}"
                                title="Ver autor">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            @if ($autor->activo)
                                <button type="button"
                                    class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditarAutor"
                                    data-id="{{ $autor->id }}"
                                    data-nombre="{{ $autor->nombre }}"
                                    data-apellido= "{{ $autor->apellido }}"
                                    data-fecha-nacimiento = "{{ $autor->fecha_nacimiento->format('Y-m-d') }}"
                                    data-pais = "{{ $autor->pais_origen }}"
                                    data-action="{{ route('autores.update', $autor) }}"
                                    title="Modificar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('autores.delete', $autor) }}"
                                    id="deleteForm{{ $autor->id }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button type="button"
                                            class="btn btn-danger btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $autor->id }}"
                                            title="Desactivar">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('autores.activar', $autor) }}"
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
                @include('delete_modal', ['id' => $autor->id, 'nombre' => $autor->nombre])
                @include('modal-autor', [
                    'id' => $autor->id,
                    'nombre' => $autor->nombre,
                    'apellido' => $autor ->apellido,
                    'pais' => $autor->pais_origen,
                    'nacimiento' => $autor -> fecha_nacimiento->format('d/m/Y'),
                    'creado' => $autor->created_at->format('d/m/Y H:i:s'),
                    'actualizado' => $autor->updated_at->format('d/m/Y H:i:s'),
                    'usuario_creacion' => optional($autor->usuarioCreacion)->username,
                    'usuario_actualizacion' => optional($autor->usuarioModificacion)->username ?? '—'
                ])
            @endforeach
        </tbody>
</table>

<div class="modal fade" id="modalAgregarAutor" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fa-solid fa-person"></i> <h5 class="modal-title"><i class="fa-solid fa-building-circle-plus opacity-75"></i> Agregar autor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @include('_formAgregarAutor')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
                <button type="submit" form="formAgregarAutor" class="btn btn-primary">
                    Agregar autor
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarAutor" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fa-solid fa-person"></i> <h5 class="modal-title"><i class="fa-solid fa-building-circle-plus opacity-75"></i> Modificar autor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @include('_formEditarAutor')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
                 <button type="submit" form="formEditarAutor" class="btn btn-primary">
                    Editar autor
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
