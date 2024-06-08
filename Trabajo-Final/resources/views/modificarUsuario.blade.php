@extends('main')

@section('title', 'Modificar usuario')
@section('body')
    <div class = "container mt-5" >
        <h1> Agregar usuario</h1>
        <form action = "{{route('usuarios.update',$usuario) }}" method = "POST">
            @csrf
            @method('put')
            <div class="form-group mt-3 mb-3">
                <label for="nombre">Nombre de nacimiento </label>
                <input type="text" class="form-control" required = "" id="nombre" name = "nombre" value = "{{$usuario->name}}">
            <div class="form-group mt-3 mb-3">
                <label for="username"> Nombre de usuario</label>
                <input type="text" class="form-control" required = "" id="username" name = "username" value = "{{$usuario->username}}">
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="email"> Email </label>
                <input type="email" class = "form-control" id="email" name="email" value = "{{$usuario->email}}">
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="pais"> Domicilio </label>
                <input type="text" class = "form-control" id="domicilio" name="domicilio" value = "{{$usuario->domicilio}}">
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="pais"> Contraseña </label>
                <input type="password" class = "form-control" id="password" name="password" value = "{{$usuario->password}}">
            </div>
            
            <div class="form-group mt-3 mb-3">
                <button type="submit" class="btn btn-primary btn-block btn-login align-items-center"> Modificar usuario</button>
            </div>
        </form>
        @if (session('success'))
        <div class = "alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    </div>
@endsection