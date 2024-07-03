@extends('main')

@section('title', 'Ver perfil')
@section('body')
    <div class = "container mt-5" >
        <h1> Ver perfil </h1>
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
                <label for="domicilio"> Domicilio </label>
                <input type="text" class = "form-control" id="domicilio" name="domicilio" value = "{{$usuario->domicilio}}">
            </div>

            <div class="form-group mt-3 mb-3">
                <label for="password"> Contraseña </label>
                <input type="password" class = "form-control" id="password" name="password">
                <div class = "mt-3 form-check">
                    <input type = "checkbox" class = "form-check-input" id = "revealPassword">
                    <label class = "form-check-label" for = "revealPassword"> Mostrar contraseña </label>
                </div>
            </div>

            <div class="form-group mt-3 mb-3">
                <label for="password_confirmation"> Confirmar contraseña </label>
                <input type="password" class = "form-control" id="password_confirmation" name="password_confirmation">
                <div class = "mt-3 form-check">
                    <input type = "checkbox" class = "form-check-input" id = "revealPassword2">
                    <label class = "form-check-label" for = "revealPassword"> Mostrar contraseña </label>
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
    <script src="{{asset('js/revelar.js') }} "></script>

@endsection