
<form id = "formAgregarAdministrador" action="{{ route('usuarios.store') }}" method="POST" novalidate>
    @csrf

    <div class="form-group mt-3 mb-3">
        <label for="adm_nombre">Nombre del administrador</label>
        <input type="text" class="form-control @error('nombre', 'agregarAdministrador') is-invalid @enderror"
               id="adm_nombre" name="nombre" value="{{ old('nombre') }}" required maxlength="255">
        @include('_campoError', ['campo' => 'nombre', 'bag' => 'agregarAdministrador'])
    </div>

    <div class="form-group mt-3 mb-3">
        <label for="adm_apellido">Apellido del administrador</label>
        <input type="text" class="form-control @error('apellido', 'agregarAdministrador') is-invalid @enderror"
               id="adm_apellido" name="apellido" value="{{ old('apellido') }}" required maxlength="255">
        @include('_campoError', ['campo' => 'apellido', 'bag' => 'agregarAdministrador'])
    </div>

    <div class="form-group mt-3 mb-3">
        <label for="adm_email">Email del administrador</label>
        <input type="email" class="form-control @error('email', 'agregarAdministrador') is-invalid @enderror"
               id="adm_email" name="email" value="{{ old('email') }}" required maxlength="255">
        @include('_campoError', ['campo' => 'email', 'bag' => 'agregarAdministrador'])
    </div>

    <div class="form-group mt-3 mb-3">
        <label for="adm_username">Usuario del administrador</label>
        <input type="text" class="form-control @error('username', 'agregarAdministrador') is-invalid @enderror"
               id="adm_username" name="username" value="{{ old('username') }}" required maxlength="255">
        @include('_campoError', ['campo' => 'username', 'bag' => 'agregarAdministrador'])
    </div>
</form>