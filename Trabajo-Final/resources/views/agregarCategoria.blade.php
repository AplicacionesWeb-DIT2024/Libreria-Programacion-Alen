@extends('main')

@section('title', 'Agregar Categoria')


@section('body')
    <div class = "container mt-5">
        <h1> Agregar categoria</h1>
        <form action = "{{route('categorias.store')}}" method = "POST">
            @csrf
            <div class="form-group mt-3 mb-3">
                <label for="nombre">Nombre de categoria</label>
                <input type="text" class="form-control" required = "" id="nombre" name = "nombre">
                @error('nombre')
                    <span class="text-danger">{{ $message }}</span>
                @enderror  
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-login align-items-center mb-3"> Agregar Categoria</button>
        </form>
        @if (session('success'))
            <div class = "alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>
@endsection