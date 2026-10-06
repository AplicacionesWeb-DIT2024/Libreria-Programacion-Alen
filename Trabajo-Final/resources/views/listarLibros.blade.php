@extends('main')
@section('title', 'Listar libros')
@section('js')
    <script src="{{ asset('js/modalEditar.js') }}"></script>
    <script>
        const subcategoriasPorCategoria = @json($subcategorias->groupBy('categoria'));
    </script>
    <script src="{{ asset('js/validarLibro.js') }}"></script>
    <script src="{{ asset('js/categoriaSubcategoria.js') }}"></script>
    <script src="{{ asset('js/modalAgregar.js') }}"></script>

@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('css/libros.css') }}">
@endsection
@section('body')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Listado de libros</h1>

    <button class="btn btn-success"
            title="Agregar libro"
            data-bs-toggle="modal"
            data-bs-target="#modalAgregarLibro">
        <i class="fas fa-plus"></i>
    </button>
</div>


    <table id="tablaLibros" class="table table-hover align-middle datatable">
        <thead class="table-light">
            <tr>
                <th></th>
                <th>Nombre</th>
                <th> Categoria </th>
                <th> Subcategoria </th>
                <th> Editorial </th> 
                <th> Idioma </th> 
                <th> Autores </th>
                <th> Stock </th>
                <th> Precio </th>
                <th>Estado</th>
                <th class="text-end">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($libros as $libro)
                <tr>
                    <td></td>
                    <td>{{ $libro->nombre }}</td>
                    <td> {{ $libro->categoria_perteneciente->nombre}}</td>
                    <td> {{$libro-> subcategoria_perteneciente-> nombre}} </td> 
                    <td> {{$libro -> editorial_perteneciente -> nombre}} </td>
                    <td> {{ $idiomas[$libro->idioma] ?? $libro->idioma }}</td> 
                    <td>
                        @foreach ($libro->getAutores() as $autor)
                            {{ $autor }}@if (!$loop->last)<br>@endif
                        @endforeach
                    </td>
                    <td> {{$libro -> stock}} </td> 
                    <td> {{$libro -> precio}} </td>
                    <td>
                        <span class="d-none">{{ $libro->activo ? 'Activo' : 'Inactivo' }}</span>
                        @if ($libro->activo)
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
                                data-bs-target="#modal{{ $libro->id }}"
                                title="Ver libro">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            @if ($libro->activo)
                                <button type="button"
                                    class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditarLibro"
                                    data-id="{{ $libro->id }}"
                                    data-nombre="{{ $libro->nombre }}"
                                    data-idioma= "{{ $libro->idioma }}"
                                    data-categoria="{{ $libro->categoria }}"
                                    data-subcategoria="{{ $libro->subcategoria }}"
                                    data-editorial="{{ $libro->editorial }}"
                                    data-autor="{{ $libro->autor }}"
                                    data-autor2= "{{ $libro->autor2 }}"
                                    data-autor3= "{{ $libro->autor3 }}"
                                    data-pais_origen= "{{$libro->pais_origen }}"
                                    data-pais_impresion= "{{$libro->pais_impresion }}"
                                    data-edicion= "{{$libro->edicion }}"
                                    data-anio_publicacion= "{{$libro->anio_publicacion}}"
                                    data-stock= "{{$libro->stock}}"
                                    data-precio= "{{$libro->precio}}"
                                    data-imagen_original="{{ $libro->imagen_original }}"
                                    data-imagen_referencia_2="{{ $libro->imagen_referencia_2 ?? '' }}"
                                    data-imagen_referencia_3="{{ $libro->imagen_referencia_3 ?? '' }}"

                                    data-action="{{ route('libros.update', $libro) }}"
                                    title="Modificar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('libros.delete', $libro) }}"
                                    id="deleteForm{{ $libro->id }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button type="button"
                                            class="btn btn-danger btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $libro->id }}"
                                            title="Desactivar">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('libros.activar', $libro) }}"
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
                @include('delete_modal', ['id' => $libro->id, 'nombre' => $libro->nombre])
                @include('modal-libro', [
                    'id' => $libro->id,
                    'nombre' => $libro->nombre,
                    'idioma' => $idiomas[$libro->idioma] ?? $libro->idioma,
                    'categoria' => $libro->categoria_perteneciente->nombre,
                    'subcategoria' => $libro->subcategoria_perteneciente-> nombre,
                    'editorial' => $libro->editorial_perteneciente->nombre,
                    'autores' => $libro->getAutores(),
                    'pais_origen' => $libro->pais_origen,
                    'pais_impresion' => $libro->pais_impresion,
                    'stock' => $libro->stock,
                    'edicion' => $libro->edicion,
                    'anio_publicacion' => $libro->anio_publicacion,
                    'precio' => $libro->precio,
                    'imagen_original' => $libro->imagen_original,
                    'imagen_referencia_2' => $libro->imagen_referencia_2,
                    'imagen_referencia_3' => $libro->imagen_referencia_3,
                    'creado' => $libro->created_at->format('d/m/Y H:i:s'),
                    'actualizado' => $libro->updated_at->format('d/m/Y H:i:s'),
                    'usuario_creacion' => optional($libro->usuarioCreacion)->username,
                    'usuario_actualizacion' => optional($libro->usuarioModificacion)->username ?? '—'
                ])
            @endforeach
        </tbody>
    </table>

<div class="modal fade" id="modalAgregarLibro" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fa-solid fa-tags"></i> <h5 class="modal-title"><i class="fa-solid fa-building-circle-plus opacity-75"></i> Agregar libro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @include('_formAgregarLibro')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
                <button type="submit" form="formAgregarLibro" class="btn btn-primary">
                    Agregar libro
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarLibro" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fa-solid fa-tags"></i> <h5 class="modal-title"><i class="fa-solid fa-building-circle-plus opacity-75"></i> Modificar libro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @include('_formEditarLibro')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
                <button type="submit" form="formEditarLibro" class="btn btn-primary">
                    Editar libro
                </button>
            </div>
        </div>
    </div>
</div>


@endsection
