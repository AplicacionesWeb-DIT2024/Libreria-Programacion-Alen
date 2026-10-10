@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form id = "formAgregarAdministrador" action="{{ route('usuarios.store') }}" method="POST">
    @csrf

    <div class="form-group mt-3 mb-3">
        <label for="nombre">Nombre del administrador</label>
        <input type="text" class="form-control @error('nombre') is-invalid @enderror"
               id="nombre" name="nombre" value="{{ old('nombre') }}" required maxlength="255">
        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group mt-3 mb-3">
        <label for="apellido">Apellido del administrador</label>
        <input type="text" class="form-control @error('apellido') is-invalid @enderror"
               id="apellido" name="apellido" value="{{ old('apellido') }}" required maxlength="255">
        @error('apellido') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group mt-3 mb-3">
        <label for="email">Email del administrador</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror"
               id="email" name="email" value="{{ old('email') }}" required maxlength="255">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group mt-3 mb-3">
        <label for="username">Usuario del administrador</label>
        <input type="text" class="form-control @error('username') is-invalid @enderror"
               id="username" name="username" value="{{ old('username') }}" required maxlength="255">
        @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</form>