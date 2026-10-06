@extends('main')
@section('title', 'Listar Subcategorias')
@section('js')
    <script src="{{ asset('js/modalEditar.js') }}"></script>
@endsection
@section('body')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Listado de subcategorias</h1>

    <button class="btn btn-success"
            title="Agregar subcategoria"
            data-bs-toggle="modal"
            data-bs-target="#modalAgregarSubcategoria">
        <i class="fas fa-plus"></i>
    </button>
</div>


    <table id="tablaSubcategorias" class="table table-hover align-middle datatable">
        <thead class="table-light">
            <tr>
                <th></th>
                <th>Nombre</th>
                <th> Categoria </th>
                <th>Estado</th>
                <th class="text-end">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subcategorias as $subcategoria)
                <tr>
                    <td></td>
                    <td>{{ $subcategoria->nombre }}</td>
                    <td> {{ $subcategoria->categoria_perteneciente->nombre}}</td>
                    <td>
                        <span class="d-none">{{ $subcategoria->activo ? 'Activo' : 'Inactivo' }}</span>
                        @if ($subcategoria->activo)
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
                                data-bs-target="#modal{{ $subcategoria->id }}"
                                title="Ver subcategoria">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            @if ($subcategoria->activo)
                                <button type="button"
                                    class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditarSubcategoria"
                                    data-id="{{ $subcategoria->id }}"
                                    data-nombre="{{ $subcategoria->nombre }}"
                                    data-categoria="{{ $subcategoria->categoria }}"
                                    data-action="{{ route('subcategorias.update', $subcategoria) }}"
                                    title="Modificar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('subcategorias.delete', $subcategoria) }}"
                                    id="deleteForm{{ $subcategoria->id }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button type="button"
                                            class="btn btn-danger btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $subcategoria->id }}"
                                            title="Desactivar">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('subcategorias.activar', $subcategoria) }}"
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
                @include('delete_modal', ['id' => $subcategoria->id, 'nombre' => $subcategoria->nombre])
                @include('modal-subcategoria', [
                    'id' => $subcategoria->id,
                    'nombre' => $subcategoria->nombre,
                    'categoria' => $subcategoria->categoria_perteneciente->nombre,
                    'creado' => $subcategoria->created_at->format('d/m/Y H:i:s'),
                    'actualizado' => $subcategoria->updated_at->format('d/m/Y H:i:s'),
                    'usuario_creacion' => optional($subcategoria->usuarioCreacion)->username,
                    'usuario_actualizacion' => optional($subcategoria->usuarioModificacion)->username ?? '—'
                ])
            @endforeach
        </tbody>
    </table>

<div class="modal fade" id="modalAgregarSubcategoria" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fa-solid fa-tags"></i> <h5 class="modal-title"><i class="fa-solid fa-building-circle-plus opacity-75"></i> Agregar subcategoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @include('_formAgregarSubcategoria')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
                <button type="submit" form="formAgregarSubcategoria" class="btn btn-primary">
                    Agregar subcategoria
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarSubcategoria" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fa-solid fa-tags"></i> <h5 class="modal-title"><i class="fa-solid fa-building-circle-plus opacity-75"></i> Modificar subcategoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @include('_formEditarSubcategoria')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
                <button type="submit" form="formEditarSubcategoria" class="btn btn-primary">
                    Editar subcategoria
                </button>
            </div>
        </div>
    </div>
</div>
@endsection



