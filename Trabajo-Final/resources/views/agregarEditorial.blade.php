@extends('main')

@section('title', 'Agregar editorial')
@section('body')
    <div class = "container mt-5" >
        <h1> Agregar editorial</h1>
        <form action = "{{route('editoriales.store')}}" method = "POST">
            @csrf
            <div class="form-group mt-3 mb-3">
                <label for="nombre">Nombre de la editorial </label>
                <input type="text" class="form-control" required = "" id="nombre" name = "nombre">
            <div class="form-group mt-3 mb-3">
                <label for="pais"> Pais de origen </label>
            </div>
            <select class = "form-control" id = "paisOrigen" name = "pais"> 
                @foreach ($paises as $pais) 
                    <option value = "{{$pais->id}}"> {{$pais->nombre}} </option>
                @endforeach
            </select>
            <div class="form-group mt-3 mb-3">
                <button type="submit" class="btn btn-primary btn-block btn-login align-items-center"> Agregar editorial</button>
            </div>
        </form>
        @if (session('success'))
        <div class = "alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    </div>
@endsection