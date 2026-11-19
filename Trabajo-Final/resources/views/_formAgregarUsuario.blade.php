
<form id = "formAgregarAdministrador" action="{{ route('usuarios.store') }}" method="POST" novalidate>
    @csrf

    <div class="form-group mt-3 mb-3">
        <label for="adm_nombre">Nombre del administrador</label>
        <input type="text" class="form-control @error('nombre', 'agregarAdministrador') is-invalid @enderror"
               id="adm_nombre" name="nombre" value="{{ old('nombre') }}" required maxlength="255">
        <small id="adm_nombre-help" class="form-text text-muted" aria-live="polite">Se permiten solo letras, espacios, apóstrofes, puntos y guiones. Debe empezar con una letra.</small>
        @include('_campoError', ['campo' => 'nombre', 'bag' => 'agregarAdministrador'])
    </div>

    <div class="form-group mt-3 mb-3">
        <label for="adm_apellido">Apellido del administrador</label>
        <input type="text" class="form-control @error('apellido', 'agregarAdministrador') is-invalid @enderror"
               id="adm_apellido" name="apellido" value="{{ old('apellido') }}" required maxlength="255">
        <small id="adm_apellido-help" class="form-text text-muted" aria-live="polite">Se permiten solo letras, espacios, apóstrofes, puntos y guiones. Debe empezar con una letra.</small>
        @include('_campoError', ['campo' => 'apellido', 'bag' => 'agregarAdministrador'])
    </div>

    <div class="form-group mt-3 mb-3">
        <label for="adm_email">Email del administrador</label>
        <input type="email" class="form-control @error('email', 'agregarAdministrador') is-invalid @enderror"
               id="adm_email" name="email" value="{{ old('email') }}" required maxlength="255">
    <small id="adm_email-help" class="form-text text-muted" aria-live="polite">Debe tener formato usuario@dominio.com. A este correo se enviará la contraseña temporal.</small>
        @include('_campoError', ['campo' => 'email', 'bag' => 'agregarAdministrador'])
    </div>

    <div class="form-group mt-3 mb-3">
        <label for="adm_username">Usuario del administrador</label>
        <input type="text" class="form-control @error('username', 'agregarAdministrador') is-invalid @enderror"
               id="adm_username" name="username" value="{{ old('username') }}" required minlength="4" maxlength="50">
        <small id="adm_username-help" class="form-text text-muted" aria-live="polite">De 4 a 50 caracteres. se permiten letras, números, puntos, guiones y guiones bajos. No se permiten espacios.</small>
        @include('_campoError', ['campo' => 'username', 'bag' => 'agregarAdministrador'])
    </div>
</form>