@extends('main')
@section('title', 'Listar editoriales')
@section('body')
<div class="container mt-5">
    <h1>Listado de editoriales</h1>
    <ul class="list-group">
        @foreach ($editoriales as $editorial)
                <li class = "list-group-item">
                    <form action="{{route('editoriales.delete', $editorial)}}" id = "deleteForm{{ $editorial->id }}" method ="POST">
                        @csrf
                        @include('delete_modal', ['id' => $editorial->id, 'nombre' => $editorial->nombre])
                        @include('modal-editorial', ['id'=> $editorial->id, 'nombre' => $editorial->nombre,  'pais_origen' => $editorial->pais_origen->nombre, 'creado' => $editorial->created_at,
                        'actualizado' => $editorial->updated_at])
                            
                            <div class="float-right ml-5">
                            
                                {{ $editorial->nombre }}
                                
                                <button type = "button" class="btn btn-info btn-sm float-right ml-2" data-toggle = "modal" data-target = "#modal{{ $editorial->id }}" title="Ver editorial">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                                <a href = "{{route('editoriales.edit', $editorial)}}" class="btn btn-warning btn-sm float-right ml-2" title="Modificar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @method('delete')
                                <button type= "button" class="btn btn-danger btn-sm float-right ml-2" title="Eliminar" data-toggle = "modal" data-target = "#deleteModal{{ $editorial->id}}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                    </form>
                </li>                  
        @endforeach
        <script src = "{{asset('js/delete_modal.js') }} "></script>
    </ul>
    <div class = "mt-4 d-flex justify-content-start">
        {{ $editoriales->links() }}
    </div>
</div>
@endsection
