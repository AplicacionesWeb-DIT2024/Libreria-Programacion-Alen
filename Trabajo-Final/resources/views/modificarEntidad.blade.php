@extends('main')

@section('title', 'Modificar entidad crediticia')
@section('body')
    <div class = "container mt-5" >
        <h1> Modificar entidad crediticia</h1>
        <form action = "{{route('entidades.update', $entidad)}}" method = "POST">
            @csrf
            @method('put')
            <div class="form-group mt-3 mb-3">
                <label for="nombre">Nombre de la entidad </label>
                <input type="text" class="form-control" required = "" id="nombre" name = "nombre" value = "{{$entidad->nombre}}">
            </div>
            <div class = "form-group mt-3 mb-3">
                <label for= "tipo"> Tipo de tarjeta </label>
            </div>
            <select class = "form-control" id = "tipo" name = "tipo">
                <option value = "Debito" @if ($entidad->tipo == 'Debito') selected @endif> Debito </option>
                <option value = "Credito" @if ($entidad->tipo == 'Credito') selected @endif> Credito </option>
            </select>
            

            <div class="form-group mt-3 mb-3">
                <label for="pais"> Banco propietario </label>
            </div>
            <select class = "form-control" id = "bancoPropietario" name = "banco"> 
                @foreach ($bancos as $banco) 
                    <option value = "{{$banco->id}}"@if ($entidad->codigo_banco == $banco->id) selected @endif> {{$banco->nombre}}</option>
                @endforeach
            </select>
            <div class="form-group mt-3 mb-3">
                <button type="submit" class="btn btn-primary btn-block btn-login align-items-center"> Modificar entidad crediticia</button>
            </div>
        </form>
        @if (session('success'))
        <div class = "alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    </div>
@endsection