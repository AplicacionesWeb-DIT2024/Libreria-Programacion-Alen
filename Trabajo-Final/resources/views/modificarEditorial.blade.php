@extends('main')

@section('title', 'Modificar editorial')
@section('body')
    <div class = "container mt-5" >
        <h1> Modificar editorial</h1>
        <form action = "{{route('editoriales.update', $editorial)}}" method = "POST">
            @csrf
            @method('put')
            <div class="form-group mt-3 mb-3">
                <label for="nombre">Nombre de la editorial </label>
                <input type="text" class="form-control" required = "" id="nombre" name = "nombre" value = "{{ $editorial->nombre}}">
            <div class="form-group mt-3 mb-3">
                <label for="pais"> Pais de origen </label>
            </div>
            <select class = "form-control" id = "paisOrigen" name = "pais"> 
                @foreach ($paises as $pais) 
                    <option value = "{{$pais->id}}" @if ($editorial->pais == $pais->id) selected @endif> {{$pais->nombre}} </option>
                @endforeach
            </select>
            <div class="form-group mt-3 mb-3">
                <button type="submit" class="btn btn-primary btn-block btn-login align-items-center"> Modificar editorial</button>
            </div>
        </form>
        @if (session('success'))
        <div class = "alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    </div>
@endsection