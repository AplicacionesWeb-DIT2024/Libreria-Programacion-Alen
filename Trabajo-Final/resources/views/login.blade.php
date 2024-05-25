@extends('login_layout')
@section('title', 'Iniciar Sesion')
@section('css')
<link rel ="stylesheet" href=  {{ asset('css/login.css') }}>
@endsection
@section('body')
<div class="container">
    <div>
        @if ($errors -> any())
        <div class = "alert alert-danger mt-3"> 
            <ul>
                @foreach ($errors->all() as $error)
                <li> {{ $error }} </li>
                @endforeach
            </ul>
        </div>
        @endif
        <h2 class="text-center mt-4" style = "max-width: 300px; margin: 2rem auto;"> Login</h2>
    </div>
    <form method = "POST" action='/login'>
        @csrf
        <div class = "form-image">
            <img class="mb-3 img-center" src="images/logobyte.png" alt="50" width="150" height="140">
        </div>
        <div class="form-group">
            <label for="username">Usuario</label>
            <input type="text" class="form-control" required = "" id="username" name = "username">
          </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" class="form-control" required = "" id="password" name = "password">
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-login align-items-center">Iniciar Sesion</button>
    </form>
</div>
@endsection