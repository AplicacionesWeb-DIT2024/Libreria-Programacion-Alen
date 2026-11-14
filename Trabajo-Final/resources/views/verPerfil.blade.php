@extends('main')

@section('title', 'Ver perfil')
@section('css')
<link rel ="stylesheet" href=  {{ asset('css/password.css') }}>

@endsection
@section('body')
    <div class = "container mt-5 perfil-form" >
        <h1> Ver perfil </h1>
        @if (session('aviso'))
            <div class="alert alert-warning">
                {{ session('aviso') }}
            </div>
        @endif
        <form action = "{{route('usuario.perfil.update',$usuario) }}" id="formPerfil" method = "POST" data-obligatorio="{{ $usuario->debe_cambiar_password ? '1' : '0' }}" novalidate>
            @csrf
            @method('PUT')
            <div class="form-group mt-3 mb-3">
                <label for="nombre"><i class="fa-solid fa-person"></i> Nombre</label>
                <input type="text" class="form-control @error('nombre') is-invalid @enderror" required id="nombre" name="nombre"
                       value="{{ old('nombre', $usuario->name) }}" maxlength="255">
                @include('_campoError', ['campo' => 'nombre', 'bag' => 'default'])
            </div>

            <div class="form-group mt-3 mb-3">
                <label for="apellido"><i class="fa-solid fa-person"></i> Apellido</label>
                <input type="text" class="form-control @error('apellido') is-invalid @enderror" required id="apellido" name="apellido"
                       value="{{ old('apellido', $usuario->apellido) }}" maxlength="255">
                @include('_campoError', ['campo' => 'apellido', 'bag' => 'default'])
            </div>

            <div class="form-group mt-3 mb-3">
                <label for="username"><i class="fa-solid fa-user"></i> Nombre de usuario</label>
                <input type="text" class="form-control @error('username') is-invalid @enderror" required id="username" name="username"
                       value="{{ old('username', $usuario->username) }}" minlength="4" maxlength="50">
                @include('_campoError', ['campo' => 'username', 'bag' => 'default'])
            </div>

            <div class="form-group mt-3 mb-3">
                <label for="email">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" required id="email" name="email"
                       value="{{ old('email', $usuario->email) }}" maxlength="255">
                @include('_campoError', ['campo' => 'email', 'bag' => 'default'])
            </div>

            <hr>

            <div class="form-group mt-3 mb-3 position-relative">
                <label for="current_password"><i class="fa-solid fa-key"></i> Contraseña actual</label>
                <div class="password-wrapper">
                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password"
                           autocomplete="current-password">
                    <i class="fa-solid fa-eye" data-toggle-password="current_password"></i>
                </div>
                @include('_campoError', ['campo' => 'current_password', 'bag' => 'default'])
            </div>

            <div class="form-group mt-3 mb-3 position-relative">
                <label for="password"><i class="fa-solid fa-key"></i> Nueva contraseña</label>
                <div class="password-wrapper">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password"
                           autocomplete="new-password">
                    <i class="fa-solid fa-eye" data-toggle-password="password"></i>
                </div>
                <small id="password-help" class="form-text text-muted" aria-live="polite" hidden> </small>
                @include('_campoError', ['campo' => 'password', 'bag' => 'default'])
            </div>

            <div class="form-group mt-3 mb-3 position-relative">
                <label for="password_confirmation"><i class="fa-solid fa-key"></i> Confirmar nueva contraseña</label>
                <div class="password-wrapper">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                           autocomplete="new-password">
                    <i class="fa-solid fa-eye" data-toggle-password="password_confirmation"></i>
                </div>
                @include('_campoError', ['campo' => 'password_confirmation', 'bag' => 'default'])
            </div>

            <div class="form-group mt-3 mb-3">
                <button type="submit" class="btn btn-primary w-100">Guardar cambios</button>
            </div>
        </form>
        
    </div>
    <script src="{{asset('js/revelar_pass.js') }} "></script>
    <script src="{{ asset('js/validarPerfil.js') }}"></script>

@endsection