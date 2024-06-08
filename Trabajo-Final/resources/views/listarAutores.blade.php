@extends('main')
@section('title', 'Listar autores')
@section('body')
<div class="container mt-5">
    <h1>Listado de autores</h1>
    <ul class="list-group">
        @foreach ($autores as $autor)
                <li class = "list-group-item">
                    <form action="{{route('autores.delete', $autor)}}" id = "deleteForm{{ $autor->id }}" method ="POST">
                        @csrf
                        @include('delete_modal', ['id' => $autor->id, 'nombre' => $autor->nombre])
                        @include('modal-autor', ['id' => $autor->id, 'nombre' => $autor->nombre, 'apellido' => $autor->apellido,
                        'pais' => $autor->pais->nombre, 'nacimiento' => $autor->fecha_nacimiento ,'creado' => $autor->created_at,
                         'actualizado' => $autor->updated_at])
                            
                            <div class="float-right ml-5">
                            
                                {{$autor->apellido}}, {{ $autor->nombre }}
                                
                                <button type = "button" class="btn btn-info btn-sm float-right ml-2" data-toggle = "modal" data-target = "#modal{{ $autor->id }}" title="Ver autor">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                                <a href = "{{route('autores.edit', $autor)}}" class="btn btn-warning btn-sm float-right ml-2" title="Modificar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @method('delete')
                                <button type= "button" class="btn btn-danger btn-sm float-right ml-2" title="Eliminar" data-toggle = "modal" data-target = "#deleteModal{{ $autor->id}}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                    </form>
                </li>                  
        @endforeach
        <script src = "{{asset('js/delete_modal.js') }} "></script>
    </ul>
    <div class = "mt-4 d-flex justify-content-start">
        {{ $autores->links() }}
    </div>
</div>
@endsection
