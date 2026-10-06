@extends('main')

@section('title', 'Ver perfil')
@section('css')
<link rel ="stylesheet" href=  {{ asset('css/password.css') }}>

@endsection
@section('body')
    <div class = "container mt-5 perfil-form" >
        <h1> Ver perfil </h1>
        <form action = "{{route('usuarios.update',$usuario) }}" method = "POST">
            @csrf
            @method('put')
            <div class="form-group mt-3 mb-3">
                <label for="nombre"><i class="fa-solid fa-person"></i> Nombre y apellido </label>
                <input type="text" class="form-control" required = "" id="nombre" name = "nombre" value = "{{$usuario->name}}">
            </div> 
                <div class="form-group mt-3 mb-3">
                <label for="username"> <i class="fa-solid fa-user"></i> Nombre de usuario</label>
                <input type="text" class="form-control" required = "" id="username" name = "username" value = "{{$usuario->username}}">
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="email"> Email </label>
                <input type="email" class = "form-control" id="email" name="email" value = "{{$usuario->email}}">
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="domicilio"> Domicilio </label>
                <input type="text" class = "form-control" id="domicilio" name="domicilio" value = "{{$usuario->domicilio}}">
            </div>

            <div class="form-group mt-3 mb-3 position-relative">
                <label for="password"><i class="fa-solid fa-key"></i> Contraseña</label>
                <div class="password-wrapper">
                    <input type="password" class="form-control" required="" id="password" name="password">
                    <i class="fa-solid fa-eye" data-toggle-password="password"></i>
                </div>
            </div>

            <div class="form-group mt-3 mb-3 position-relative">
                <label for="password_confirmation"><i class="fa-solid fa-key"></i> Confirmar Contraseña</label>
                <div class="password-wrapper">
                    <input type="password" class="form-control" required="" id="password_confirmation" name="password_confirmation">
                    <i class="fa-solid fa-eye" data-toggle-password="password_confirmation"></i>
                </div>
            </div>
            <div class="form-group mt-3 mb-3">
                <button type="submit" class="btn btn-primary btn-block btn-login align-items-center"> Guardar cambios </button>
            </div>
        </form>
        @if (session('success'))
        <div class = "alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        @if (session('aviso'))
            <div class="alert alert-warning">
                {{ session('aviso') }}
            </div>
        @endif
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    <script src="{{asset('js/revelar_pass.js') }} "></script>

@endsection