@extends('main')

@section('title', 'Agregar Subcategoria')
@section('body')
    <div class = "container mt-5">
        <h1> Agregar categoria</h1>
        <form action = "{{route('subcategorias.store')}}" method = "POST">
            @csrf
            <div class="form-group mb-3 mt-3">
                <label for="nombre">Nombre de subcategoria</label>
                <input type="text" class="form-control" required = "" id="nombre" name = "nombre">
                @error('nombre')
                    <span class="text-danger">{{ $message }}</span>
                @enderror  
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-login align-items-center"> Agregar subcategoria</button>
        </form>
        @if (session('success'))
            <div class = "alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>
@endsection