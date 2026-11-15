<form action = "{{route('categorias.store')}}" method = "POST" id="formAgregarCategoria" novalidate>
            @csrf
            <div class="form-group mt-3 mb-3">
                <label for="nombre">Nombre de la categoria (*) </label>
                <input type="text" class="form-control @error('nombre', 'agregarCategoria') is-invalid @enderror"
                    id="nombre" name="nombre" maxlength="255" value="{{ old('nombre') }}" required>
                <small id="nombre-help" class="form-text text-danger" aria-live="polite" hidden></small>
                <div class="invalid-feedback" id="nombreFeedback">
                    @error('nombre', 'agregarCategoria') {{ $message }} @else El nombre es obligatorio. @enderror
                </div>
            </div>
</form>