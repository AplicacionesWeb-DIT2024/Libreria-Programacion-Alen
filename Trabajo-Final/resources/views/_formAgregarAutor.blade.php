<form action = "{{route('autores.store')}}" method = "POST" id="formAgregarAutor" novalidate>
            @csrf
            <div class="form-group mt-3 mb-3">
                <label for="aut_nombre">Nombre del autor (*) </label>
                <input type="text" class="form-control @error('nombre', 'agregarAutor') is-invalid @enderror" id="aut_nombre" name="nombre" maxlength="255" value="{{ old('nombre') }}" required>
                <small id="aut_nombre-help" class="form-text text-muted" aria-live="polite">El nombre solo admite letras, espacios, apóstrofes, puntos y guiones. Debe empezar con una letra.</small>
                <div class="invalid-feedback" id="aut_nombreFeedback">
                    @error('nombre', 'agregarAutor') {{ $message }} @else El nombre es obligatorio. @enderror
                </div>
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="aut_apellido">Apellido del autor (*) </label>
                <input type="text" class="form-control @error('apellido', 'agregarAutor') is-invalid @enderror" id="aut_apellido" name="apellido" maxlength="255" value="{{ old('apellido') }}" required>
                <small id="aut_apellido-help" class="form-text text-muted" aria-live="polite">El apellido solo admite letras, espacios, apóstrofes, puntos y guiones. Debe empezar con una letra.</small>
                <div class="invalid-feedback" id="aut_apellidoFeedback">
                    @error('apellido', 'agregarAutor') {{ $message }} @else El apellido es obligatorio. @enderror
                </div>
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="aut_fechaNac"> Fecha de nacimiento (*) </label>
                <input type="date" class="form-control @error('fechaNac', 'agregarAutor') is-invalid @enderror"
                    id="aut_fechaNac" name="fechaNac" max="{{ now()->subYears(18)->toDateString() }}" min="1900-01-01" value="{{ old('fechaNac') }}" required>
                <small id="aut_fechaNac-help" class="form-text text-muted"></small>
                <div class="invalid-feedback" id="aut_fechaNacFeedback">
                    @error('fechaNac', 'agregarAutor') {{ $message }} @else La fecha de nacimiento es obligatoria. @enderror
                </div>
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="aut_pais">País de nacimiento (*) </label>
                <select class="form-control select2" id="aut_pais" name="pais" data-placeholder="Seleccione un país" required>
                    <option value=""></option>
                    @foreach ($paises as $pais)
                    <option value="{{ $pais }}" @selected(old('pais') == $pais)>{{ $pais }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback d-block" id="aut_paisFeedback">
                    @error('pais', 'agregarAutor') {{ $message }} @enderror
                </div>
            </div>
</form>