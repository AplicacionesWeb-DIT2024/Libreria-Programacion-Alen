@extends('main')
@section('title', 'Listar Subcategorias')
@section('body')
<div class="container mt-5">
    <h1>Listado de subcategorias</h1>
    <ul class="list-group">
        @foreach ($subcategorias as $subcategoria)
                <li class = "list-group-item">
                    <form action="{{route('subcategorias.delete', $subcategoria)}}" id = "deleteForm{{ $subcategoria->id }}" method ="POST">
                        @csrf
                        @include('delete_modal', ['id'=> $subcategoria->id, 'nombre' => $subcategoria->nombre])
                        @include('modal', ['id' => $subcategoria->id, 'nombre' => $subcategoria->nombre, 'creado' => $subcategoria->created_at,
                         'actualizado' => $subcategoria->updated_at])
                            
                            <div class="float-right ml-5">
                            
                                {{ $subcategoria->nombre }}
                                
                                <button type = "button" class="btn btn-info btn-sm float-right ml-2" data-toggle = "modal" data-target = "#modal{{ $subcategoria->id }}" title="Ver categoria">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                                <a href = "{{route('subcategorias.edit', $subcategoria)}}" class="btn btn-warning btn-sm float-right ml-2" title="Modificar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @method('delete')
                                <button type= "button" class="btn btn-danger btn-sm float-right ml-2" title="Eliminar" data-toggle = "modal" data-target = "#deleteModal{{ $subcategoria->id }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                    </form>
                </li>                  
        @endforeach
        <script src = " {{asset('js/delete_modal.js') }} "></script>
    </ul>
    <div class = "mt-4 d-flex justify-content-start">
        {{ $subcategorias->links() }}
    </div>
</div>
@endsection



