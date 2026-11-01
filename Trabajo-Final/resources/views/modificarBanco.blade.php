@extends('main')

@section('title', 'Modificar banco')
@section('body')
    <div class = "container mt-5" >
        <h1> Modificar banco</h1>
        <form action = "{{route('bancos.update', $banco)}}" method = "POST">
            @csrf
            @method('put')
            <div class="form-group mt-3 mb-3">
                <label for="nombre">Nombre del banco </label>
                <input type="text" class="form-control" required = "" id="nombre" name = "nombre" value = "{{$banco->nombre}}">
            <div class="form-group mt-3 mb-3">
                <label for="pais"> Pais de origen </label>
            </div>
            <select class = "form-control" id = "paisOrigen" name = "pais"> 
                @foreach ($paises as $pais) 
                    <option value = "{{$pais->id}}" @if ($banco->pais_origen == $pais->id) selected @endif> {{$pais->nombre}} </option>
                @endforeach
            </select>
            <div class="form-group mt-3 mb-3">
                <button type="submit" class="btn btn-primary btn-block btn-login align-items-center"> Modificar banco</button>
            </div>
        </form>
        @if (session('success'))
        <div class = "alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    </div>
@endsection