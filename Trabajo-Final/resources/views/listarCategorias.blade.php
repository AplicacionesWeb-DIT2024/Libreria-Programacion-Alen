@extends('main')
@section('title', 'Listar Categorias')
@section('js')
    <script src="{{ asset('js/modalEditar.js') }}"></script>
@endsection
@section('body')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Listado de categorias</h1>

    <button class="btn btn-success"
            title="Agregar Categoria"
            data-bs-toggle="modal"
            data-bs-target="#modalAgregarCategoria">
        <i class="fas fa-plus"></i>
    </button>
</div>

    <table id="tablaCategorias" class="table table-hover align-middle datatable">
        <thead class="table-light">
            <tr>
                <th></th>
                <th>Nombre</th>
                <th>Estado</th>
                <th class="text-end">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categorias as $categoria)
                <tr>
                    <td></td>
                    <td>{{ $categoria->nombre }}</td>
                    <td>
                        <span class="d-none">{{ $categoria->activo ? 'Activo' : 'Inactivo' }}</span>
                        @if ($categoria->activo)
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
                                data-bs-target="#modal{{ $categoria->id }}"
                                title="Ver categoria">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            @if ($categoria->activo)
                                <button type="button"
                                    class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditarCategoria"
                                    data-id="{{ $categoria->id }}"
                                    data-nombre="{{ $categoria->nombre }}"
                                    data-action="{{ route('categorias.update', $categoria) }}"
                                    title="Modificar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('categorias.delete', $categoria) }}"
                                    id="deleteForm{{ $categoria->id }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button type="button"
                                            class="btn btn-danger btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $categoria->id }}"
                                            title="Desactivar">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('categorias.activar', $categoria) }}"
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
                @include('delete_modal', ['id' => $categoria->id, 'nombre' => $categoria->nombre])
                @include('modal-categoria', [
                    'id' => $categoria->id,
                    'nombre' => $categoria->nombre,
                    'creado' => $categoria->created_at->format('d/m/Y H:i:s'),
                    'actualizado' => $categoria->updated_at->format('d/m/Y H:i:s'),
                    'usuario_creacion' => optional($categoria->usuarioCreacion)->username,
                    'usuario_actualizacion' => optional($categoria->usuarioModificacion)->username ?? '—'
                ])
            @endforeach
        </tbody>
    </table>

<div class="modal fade" id="modalAgregarCategoria" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fa-solid fa-tags"></i> <h5 class="modal-title"><i class="fa-solid fa-building-circle-plus opacity-75"></i> Agregar categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @include('_formAgregarCategoria')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
                <button type="submit" form="formAgregarCategoria" class="btn btn-primary">
                    Agregar categoria
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarCategoria" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fa-solid fa-tags"></i> <h5 class="modal-title"><i class="fa-solid fa-building-circle-plus opacity-75"></i> Modificar categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @include('_formEditarCategoria')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
                <button type="submit" form="formEditarCategoria" class="btn btn-primary">
                    Editar categoria
                </button>
            </div>
        </div>
    </div>
</div>


@endsection