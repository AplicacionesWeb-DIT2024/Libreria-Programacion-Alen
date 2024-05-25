@extends('main')
@section('title', 'Listar paises')
@section('body')
<div class="container mt-5">
    <h1>Listado de paises</h1>
    <ul class="list-group">
        @foreach ($paises as $pais)
                <li class = "list-group-item">
                    <form action="{{route('paises.delete', $pais)}}" id = "deleteForm{{ $pais->id }}" method ="POST">
                        @csrf
                        @include('delete_modal', ['id'=> $pais->id, 'nombre' => $pais->nombre])
                        @include('modal', ['id' => $pais->id, 'nombre' => $pais->nombre, 'creado' => $pais->created_at,
                         'actualizado' => $pais->updated_at])
                            
                            <div class="float-right ml-5">
                            
                                {{ $pais->nombre }}
                                
                                <button type = "button" class="btn btn-info btn-sm float-right ml-2" data-toggle = "modal" data-target = "#modal{{ $pais->id }}" title="Ver categoria">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                                <a href = "{{route('paises.edit', $pais)}}" class="btn btn-warning btn-sm float-right ml-2" title="Modificar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @method('delete')
                                <button type= "button" class="btn btn-danger btn-sm float-right ml-2" title="Eliminar" data-toggle = "modal" data-target = "#deleteModal{{ $pais->id }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                    </form>
                </li>                  
        @endforeach
        <script src = "js/delete_modal.js"></script>
    </ul>
    <div class = "mt-4 d-flex justify-content-start">
        {{ $paises->links() }}
    </div>
</div>
@endsection



