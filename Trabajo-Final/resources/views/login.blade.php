@extends('login_layout')
@section('title', 'Iniciar Sesion')
@section('css')
<link rel ="stylesheet" href=  {{ asset('css/login.css') }}>
<link rel ="stylesheet" href=  {{ asset('css/password.css') }}>

@endsection
@section('body')
<div class="container login-form">
    <div>
        @if ($errors -> any())
        <div class = "alert alert-danger mt-3"> 
                @foreach ($errors->all() as $error)
                <p class="mb-0"> {{ $error }} </p>
                @endforeach
        </div>
        @endif
        <h2 class="text-center mt-4" style = "max-width: 300px; margin: 2rem auto;"> Administración</h2>
    </div>
    <form method = "POST" action='/login'>
        @csrf
        <div class = "form-image">
            <img class="mb-3 img-center" src="images/logobyte.png" alt="50" width="150" height="140">
        </div>
        <div class="form-group">
            <label for="username"><i class="fa-solid fa-user"></i> Usuario</label>
            <input type="text" class="form-control" required = "" id="username" name = "username">
        </div>
        <br>
        <div class="form-group position-relative">
            <label for="password"><i class="fa-solid fa-key"></i> Contraseña</label>
            <div class="password-wrapper">
                <input type="password" class="form-control" required="" id="password" name="password">
                <i class="fa-solid fa-eye" data-toggle-password="password"></i>
            </div>
        </div>
        <button type="submit" id="btn-login" class="btn btn-primary btn-block btn-login">
            <span class="texto">Iniciar Sesión</span>
            <i class="fa-solid fa-right-to-bracket ms-2 icono-login"></i>
            <i class="fa-solid fa-spinner fa-spin ms-2 d-none spinner-login"></i>
        </button>
    </form> 
</div>

@endsection

@section('js')
<script src="{{asset('js/spinner_login.js') }} "></script>
<script src="{{asset('js/revelar_pass.js') }} "></script>
<script src="{{asset('js/esconderError.js') }} "></script>


@endsection

