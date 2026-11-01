@extends('main')

@section('title', 'Ver perfil')
@section('css')
<link rel ="stylesheet" href=  {{ asset('css/password.css') }}>

@endsection
@section('body')
    <div class = "container mt-5 perfil-form" >
        <h1> Ver perfil </h1>
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
        <form action = "{{route('usuario.perfil.update',$usuario) }}" method = "POST">
            @csrf
            @method('PUT')
            <div class="form-group mt-3 mb-3">
                <label for="nombre"><i class="fa-solid fa-person"></i> Nombre</label>
                <input type="text" class="form-control" required id="nombre" name="nombre"
                       value="{{ old('nombre', $usuario->name) }}" maxlength="255">
            </div>

            <div class="form-group mt-3 mb-3">
                <label for="apellido"><i class="fa-solid fa-person"></i> Apellido</label>
                <input type="text" class="form-control" required id="apellido" name="apellido"
                       value="{{ old('apellido', $usuario->apellido) }}" maxlength="255">
            </div>

            <div class="form-group mt-3 mb-3">
                <label for="username"><i class="fa-solid fa-user"></i> Nombre de usuario</label>
                <input type="text" class="form-control" required id="username" name="username"
                       value="{{ old('username', $usuario->username) }}" maxlength="255">
            </div>

            <div class="form-group mt-3 mb-3">
                <label for="email">Email</label>
                <input type="email" class="form-control" required id="email" name="email"
                       value="{{ old('email', $usuario->email) }}" maxlength="255">
            </div>

            <hr>

            <div class="form-group mt-3 mb-3 position-relative">
                <label for="current_password"><i class="fa-solid fa-key"></i> Contraseña actual</label>
                <div class="password-wrapper">
                    <input type="password" class="form-control" id="current_password" name="current_password"
                           autocomplete="current-password">
                    <i class="fa-solid fa-eye" data-toggle-password="current_password"></i>
                </div>
            </div>

            <div class="form-group mt-3 mb-3 position-relative">
                <label for="password"><i class="fa-solid fa-key"></i> Nueva contraseña</label>
                <div class="password-wrapper">
                    <input type="password" class="form-control" id="password" name="password"
                           autocomplete="new-password">
                    <i class="fa-solid fa-eye" data-toggle-password="password"></i>
                </div>
            </div>

            <div class="form-group mt-3 mb-3 position-relative">
                <label for="password_confirmation"><i class="fa-solid fa-key"></i> Confirmar nueva contraseña</label>
                <div class="password-wrapper">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                           autocomplete="new-password">
                    <i class="fa-solid fa-eye" data-toggle-password="password_confirmation"></i>
                </div>
            </div>

            <div class="form-group mt-3 mb-3">
                <button type="submit" class="btn btn-primary w-100">Guardar cambios</button>
            </div>
        </form>
        
    </div>
    <script src="{{asset('js/revelar_pass.js') }} "></script>

@endsection