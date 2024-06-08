@extends('main')
@section('title', 'Listar bancos')
@section('body')
<div class="container mt-5">
    <h1>Listado de bancos</h1>
    <ul class="list-group">
        @foreach ($bancos as $banco)
                <li class = "list-group-item">
                    <form action="{{route('bancos.delete', $banco)}}" id = "deleteForm{{ $banco->id }}" method ="POST">
                        @csrf
                        @include('delete_modal', ['id' => $banco->id, 'nombre' => $banco->nombre])
                        @include('modal-banco', ['id' => $banco->id, 'nombre' => $banco->nombre, 'pais_origen' => $banco->pais->nombre,
                        'creado' => $banco->created_at,
                         'actualizado' => $banco->updated_at])
                            
                            <div class="float-right ml-5">
                            
                                {{ $banco->nombre }}
                                
                                <button type = "button" class="btn btn-info btn-sm float-right ml-2" data-toggle = "modal" data-target = "#modal{{ $banco->id }}" title="Ver banco">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                                <a href = "{{route('bancos.edit', $banco)}}" class="btn btn-warning btn-sm float-right ml-2" title="Modificar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @method('delete')
                                <button type= "button" class="btn btn-danger btn-sm float-right ml-2" title="Eliminar" data-toggle = "modal" data-target = "#deleteModal{{ $banco->id}}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                    </form>
                </li>                  
        @endforeach
        <script src = "{{asset('js/delete_modal.js') }} "></script>
    </ul>
    <div class = "mt-4 d-flex justify-content-start">
        {{ $bancos->links() }}
    </div>
</div>
@endsection
