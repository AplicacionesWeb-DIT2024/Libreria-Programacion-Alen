@extends('main')
@section('title', 'Listar libros')
@section('body')
<div class="container mt-5">
    <h1>Listado de libros</h1>
    <table class="table">
        <thead class="thead-dark">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Nombre</th>
            <th scope="col">Categoria</th>
            <th scope="col">Editorial</th>
            <th scope="col">Autor/es</th>
            <th scope="col">Imagen</th>
            <th scope = "col"> Opciones </th>
          </tr>
        </thead>
        <tbody>
            @foreach($libros as $libro)
            <tr>
                <th scope="row"> {{ $libro->id }} </th>
                <td> {{ $libro->nombre}} </td>
                <td> {{ $libro->categoria_perteneciente->nombre }} </td>
                <td> {{ $libro->editorial_perteneciente->nombre }} </td>
                <td> {{$libro->getAutores()}} </td>
                <td> 
                    <img src = "{{ asset($libro->imagen_referencia)}} " alt ="{{ $libro->nombre }}" class = "img-fluid img-thumbnail" width="120px">
                </td>
                <td> 
                    <form action="{{route('libros.delete', $libro)}}" id = "deleteForm{{ $libro->id }}" method ="POST">
                        @csrf
                        @include('delete_modal', ['id' => $libro->id, 'nombre' => $libro->nombre])
                        @include('modal_libro', ['id' => $libro->id, 'subcategoria' => $libro->subcategoria_perteneciente->nombre, 
                        'pais_origen' => $libro->pais_de_origen->nombre,'pais_impresion' => $libro->pais_de_impresion->nombre, 
                        'stock' => $libro->stock, 'edicion' => $libro->edicion, 
                        'anio_publicacion' => $libro->anio_publicacion,
                        'precio' => $libro->precio,'creado' => $libro->created_at,
                         'actualizado' => $libro->updated_at])
                            
                            <div class="float-right ml-5">                                
                                <button type = "button" class="btn btn-info btn-sm float-right ml-2" data-toggle = "modal" data-target = "#modal{{ $libro->id }}" title="Mas información">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                                <a href = "{{route('libros.edit', $libro)}}" class="btn btn-warning btn-sm float-right ml-2" title="Modificar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @method('delete')
                                <button type= "button" class="btn btn-danger btn-sm float-right ml-2" title="Eliminar" data-toggle = "modal" data-target = "#deleteModal{{ $libro->id}}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                    </form>
            </tr>
            @endforeach
            <script src = "{{asset('js/delete_modal.js') }} "></script>
        </tbody>
    </table>
    <div class = "mt-4 d-flex justify-content-start">
        {{ $libros->links() }}
    </div>
</div>
@endsection