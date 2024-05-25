@extends('main')
@section('title', 'Listar Categorias')
@section('body')
<div class="container mt-5">
    <h1>Listado de categorías</h1>
    <ul class="list-group">
        @foreach ($categorias as $categoria)
                <li class = "list-group-item">
                    <form action="{{route('categorias.delete', $categoria)}}" id = "deleteForm{{ $categoria->id }}" method ="POST">
                        @csrf
                        @include('delete_modal', ['id' => $categoria->id, 'nombre' => $categoria->nombre])
                        @include('modal', ['id' => $categoria->id, 'nombre' => $categoria->nombre, 'creado' => $categoria->created_at,
                         'actualizado' => $categoria->updated_at])
                            
                            <div class="float-right ml-5">
                            
                                {{ $categoria->nombre }}
                                
                                <button type = "button" class="btn btn-info btn-sm float-right ml-2" data-toggle = "modal" data-target = "#modal{{ $categoria->id }}" title="Ver categoria">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                                <a href = "{{route('categorias.edit', $categoria)}}" class="btn btn-warning btn-sm float-right ml-2" title="Modificar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @method('delete')
                                <button type= "button" class="btn btn-danger btn-sm float-right ml-2" title="Eliminar" data-toggle = "modal" data-target = "#deleteModal{{ $categoria->id}}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                    </form>
                </li>                  
        @endforeach
        <script src = "{{asset('js/delete_modal.js') }} "></script>
    </ul>
    <div class = "mt-4 d-flex justify-content-start">
        {{ $categorias->links() }}
    </div>
</div>
@endsection



