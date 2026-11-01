@extends('main')

@section('title', 'Modificar autor')
@section('body')
    <div class = "container mt-5" >
        <h1> Agregar autor</h1>
        <form action = "{{route('autores.update', $autor)}}" method = "POST">
            @csrf
            @method('put')

            <div class="form-group mt-3 mb-3">
                <label for="nombre">Nombre del autor </label>
                <input type="text" class="form-control" required = "" id="nombre" name = "nombre" value = "{{$autor->nombre}}">
            <div class="form-group mt-3 mb-3">
                <label for="nombre">Apellido del autor </label>
                <input type="text" class="form-control" required = "" id="apellido" name = "apellido" value = "{{$autor->apellido}}">
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="fecha"> Fecha de nacimiento </label>
                <input type="date" id="fechaNac" name="fechaNac" value = "{{$autor->fecha_nacimiento}}">
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="pais"> Pais de origen </label>
            </div>
            <select class = "form-control" id = "paisOrigen" name = "pais" > 
                @foreach ($paises as $pais) 
                    <option value = "{{$pais->id}}" @if ($autor->pais_origen == $pais->id) selected @endif> {{$pais->nombre}} </option>
                @endforeach
            </select>
            <div class="form-group mt-3 mb-3">
                <button type="submit" class="btn btn-primary btn-block btn-login align-items-center"> Modificar autor</button>
            </div>
        </form>
    </div>
@endsection