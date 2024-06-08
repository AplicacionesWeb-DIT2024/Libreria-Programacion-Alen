@extends('main')
@section('title', 'Listado de entidades')
@section('body')
<div class="container mt-5">
    <h1>Listado de entidades</h1>
    <ul class="list-group">
        @foreach ($entidades as $entidad)
                <li class = "list-group-item">
                    <form action="{{route('entidades.delete', $entidad)}}" id = "deleteForm{{ $entidad->id }}" method ="POST">
                        @csrf
                        @include('delete_modal', ['id' => $entidad->id, 'nombre' => $entidad->nombre])
                        @include('modal-entidad', ['id' => $entidad->id, 'nombre' => $entidad->nombre, 'creado' => $entidad->created_at,
                         'actualizado' => $entidad->updated_at, 'tipo' => $entidad->tipo, 'banco_origen' => $entidad->banco->nombre])
                            
                            <div class="float-right ml-5">
                            
                                {{ $entidad->nombre }}
                                
                                <button type = "button" class="btn btn-info btn-sm float-right ml-2" data-toggle = "modal" data-target = "#modal{{ $entidad->id }}" title="Ver editorial">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                                <a href = "{{route('entidades.edit', $entidad)}}" class="btn btn-warning btn-sm float-right ml-2" title="Modificar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @method('delete')
                                <button type= "button" class="btn btn-danger btn-sm float-right ml-2" title="Eliminar" data-toggle = "modal" data-target = "#deleteModal{{ $entidad->id}}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                    </form>
                </li>                  
        @endforeach
        <script src = "{{asset('js/delete_modal.js') }} "></script>
    </ul>
    <div class = "mt-4 d-flex justify-content-start">
        {{ $entidades->links() }}
    </div>
</div>
@endsection
