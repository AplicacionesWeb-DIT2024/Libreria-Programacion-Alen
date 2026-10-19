@extends('main')

@section('title', 'Modificar categoria')
@section('body')
    <div class = "container mt-5">
        <h1> Modificar categoria</h1>
        <form action = "{{route('categorias.update', $categoria)}}" method = "POST">
            @csrf

            @method('put')
            <div class="form-group mt-3 mb-3">
                <label for="nombre">Nombre de categoria</label>
                <input type="text" class="form-control" required = "" id="nombre"  name = "nombre" value = "{{$categoria->nombre}}">
                @error('nombre')
                    <span class="text-danger">{{ $message }}</span>
                @enderror  
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-login align-items-center"> Modificar Categoria</button>
        </form>
        @if (session('success'))
            <div class = "alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>
@endsection