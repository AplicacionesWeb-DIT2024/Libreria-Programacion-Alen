@extends('main')
@section('title', 'Listar editoriales')
@section('js')
    <script src="{{ asset('js/modalEditar.js') }}"></script>
@endsection
@section('body')

<div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Listado de editoriales</h1>

        <button class="btn btn-success"
                title="Agregar editorial"
                data-bs-toggle="modal"
                data-bs-target="#modalAgregarEditorial">
            <i class="fas fa-plus"></i>
        </button>
    </div>

    <table id="tablaEditoriales" class="table table-hover align-middle datatable">
         <thead class="table-light">
            <tr>
                <th></th>
                <th>Nombre</th>
                <th>País</th>
                <th>Estado</th>
                <th class="text-end">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($editoriales as $editorial)
                <tr>
                    <td></td>
                    <td>{{ $editorial->nombre }}</td>
                    <td>{{ $editorial->pais }}</td>
                    <td>
                        <span class="d-none">{{ $editorial->activo ? 'Activo' : 'Inactivo' }}</span>
                        @if ($editorial->activo)
                            <span class="badge rounded-pill bg-success-subtle text-success px-3">
                                Activo
                            </span>
                        @else
                            <span class="badge rounded-pill bg-danger-subtle text-danger px-3">
                                Inactivo
                            </span>
                        @endif
                    </td>
                    <!-- ACCIONES -->
                    <td class="text-end" >
                        <div class="d-flex justify-content-end gap-1">
                        <button type="button"
                            class="btn btn-info btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#modal{{ $editorial->id }}"
                            title="Ver editorial">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        @if ($editorial->activo)
                            <button type="button"
                                                class="btn btn-warning btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEditarEditorial"
                                                data-id="{{ $editorial->id }}"
                                                data-nombre="{{ $editorial->nombre }}"
                                                data-pais="{{ $editorial->pais }}"
                                                data-action="{{ route('editoriales.update', $editorial) }}" 
                                                title="Modificar">
                                                <i class="fas fa-edit"></i>
                            </button>

                                            <form action="{{ route('editoriales.delete', $editorial) }}"
                                            id="deleteForm{{ $editorial->id }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <button type="button"
                                                        class="btn btn-danger btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal{{ $editorial->id }}"
                                                        title="Desactivar">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @else

                                            <form action="{{ route('editoriales.activar', $editorial) }}"
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
                            @include('delete_modal', ['id' => $editorial->id, 'nombre' => $editorial->nombre])
                            @include('modal-editorial', [
                                'id' => $editorial->id,
                                'nombre' => $editorial->nombre,
                                'pais_origen' => $editorial->pais,
                                'creado' => $editorial->created_at->format('d/m/Y H:i:s'),
                                'actualizado' => $editorial->updated_at->format('d/m/Y H:i:s'),
                                'usuario_creacion' => optional($editorial->usuarioCreacion)->username,
                                'usuario_actualizacion' => optional($editorial->usuarioModificacion)->username ?? '—'
                            ])
                        @endforeach            
        </tbody>
    </table>

<div class="modal fade" id="modalAgregarEditorial" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <i class="fa-solid fa-building"></i> <h5 class="modal-title"><i class="fa-solid fa-building-circle-plus opacity-75"></i> Agregar editorial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                @include('_formAgregarEditorial')
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
                <button type="submit" form="formAgregarEditorial" class="btn btn-primary">
                    Agregar editorial
                </button>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="modalEditarEditorial" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <i class="fa-solid fa-building"></i> <h5 class="modal-title"><i class="fa-solid fa-building-circle-plus opacity-75"></i> Modificar editorial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                @include('_formModificarEditorial')
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    document.getElementById('btnLimpiarFiltros').addEventListener('click', function () {
        document.getElementById('nombre').value = '';
        document.getElementById('activo').value = '';

        const table = $('#tablaEditoriales').data('datatable-instance');
        table.search('').columns().search('').draw();
    });
</script>

@endsection

