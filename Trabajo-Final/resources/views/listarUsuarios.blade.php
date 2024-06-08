@extends('main')
@section('title', 'Listar autores')
@section('body')
<div class="container mt-5">
    <h1>Listado de usuarios</h1>
    <ul class="list-group">
        @foreach ($usuarios as $usuario)
                <li class = "list-group-item">
                    <form action="{{route('usuarios.delete', $usuario)}}" id = "deleteForm{{ $usuario->id }}" method ="POST">
                        @csrf
                        @include('delete_modal', ['id' => $usuario->id, 'nombre' => $usuario->username])
                        @include('modal_usuario', ['id' => $usuario->id, 'nombre' => $usuario->nombre, 
                        'domicilio' => $usuario->domicilio, 'admin' => $usuario->admin, 'creado' => $usuario->created_at,
                         'actualizado' => $usuario->updated_at])
                            
                            <div class="float-right ml-5">
                            
                                {{$usuario->username}}
                                
                                <button type = "button" class="btn btn-info btn-sm float-right ml-2" data-toggle = "modal" data-target = "#modal{{ $usuario->id }}" title="Ver usuario">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                                <a href = "{{route('usuarios.edit', $usuario)}}" class="btn btn-warning btn-sm float-right ml-2" title="Modificar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @method('delete')
                                <button type= "button" class="btn btn-danger btn-sm float-right ml-2" title="Eliminar" data-toggle = "modal" data-target = "#deleteModal{{ $usuario->id}}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                    </form>
                </li>                  
        @endforeach
        <script src = "{{asset('js/delete_modal.js') }} "></script>
    </ul>
    <div class = "mt-4 d-flex justify-content-start">
        {{ $usuarios->links() }}
    </div>
</div>
@endsection
