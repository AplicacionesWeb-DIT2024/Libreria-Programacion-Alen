@extends('main')

@section('title', 'Agregar usuario')
@section('body')
    <div class = "container mt-5" >
        <h1> Agregar usuario</h1>
        <form action = "{{route('usuarios.store')}}" method = "POST">
            @csrf
            <div class="form-group mt-3 mb-3">
                <label for="nombre">Nombre de nacimiento </label>
                <input type="text" class="form-control" required = "" id="nombre" name = "nombre">
            <div class="form-group mt-3 mb-3">
                <label for="username"> Nombre de usuario</label>
                <input type="text" class="form-control" required = "" id="username" name = "username">
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="email"> Email </label>
                <input type="email" class = "form-control" id="email" name="email">
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="pais"> Domicilio </label>
                <input type="text" class = "form-control" id="domicilio" name="domicilio">
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="pais"> Contraseña </label>
                <input type="password" class = "form-control" id="password" name="password">
            </div>
            
            <div class="form-group mt-3 mb-3">
                <button type="submit" class="btn btn-primary btn-block btn-login align-items-center"> Agregar usuario</button>
            </div>
        </form>
        @if (session('success'))
        <div class = "alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    </div>
@endsection