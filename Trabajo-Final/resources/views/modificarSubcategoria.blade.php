@extends('main')

@section('title', 'Modificar subcategoria')
@section('body')
    <div class = "container mt-5">
        <h1> Modificar subcategoria</h1>
        <form action = "{{route('subcategorias.update', $subcategoria)}}" method = "POST">
            @csrf

            @method('put')
            <div class="form-group mt-3 mb-3">
                <label for="nombre">Nombre de subcategoria</label>
                <input type="text" class="form-control" required = "" id="nombre"  name = "nombre" value = "{{$subcategoria->nombre}}">
                @error('nombre')
                    <span class="text-danger">{{ $message }}</span>
                @enderror  
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-login align-items-center"> Modificar subcategoria</button>
        </form>
        @if (session('success'))
            <div class = "alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>
@endsection