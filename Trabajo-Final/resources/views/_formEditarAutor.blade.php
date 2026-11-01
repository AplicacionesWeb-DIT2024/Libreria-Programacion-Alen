<form id="formEditarAutor" method = "POST" action="{{ old('autor_id') ? route('autores.update', old('autor_id')) : '' }}" novalidate>
            @csrf
            @method('PUT')
            <input type="hidden" name="autor_id" id="edit_autor_id" value="{{ old('autor_id') }}">

            <div class="form-group mt-3 mb-3">
                <label for="edit_aut_nombre">Nombre del autor </label>
                <input type="text" class="form-control @error('nombre', 'editarAutor') is-invalid @enderror"
                    data-field="nombre" id="edit_aut_nombre" name="nombre" maxlength="255"
                    value="{{ old('nombre') }}" required>
                <div class="invalid-feedback" id="edit_aut_nombreFeedback">
                    @error('nombre', 'editarAutor') {{ $message }} @else El nombre es obligatorio. @enderror
                </div>
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="edit_aut_apellido">Apellido del autor </label>
                <input type="text" class="form-control @error('apellido', 'editarAutor') is-invalid @enderror"
                    data-field="apellido" id="edit_aut_apellido" name="apellido" maxlength="255" value="{{ old('apellido') }}" required>
                <div class="invalid-feedback" id="edit_aut_apellidoFeedback">
                     @error('apellido', 'editarAutor') {{ $message }} @else El apellido es obligatorio. @enderror
                </div>
            </div>

             <div class="form-group mt-3 mb-3">
                <label for="edit_aut_fechaNac">Fecha de nacimiento</label>
                <input type="date" class="form-control @error('fechaNac', 'editarAutor') is-invalid @enderror"
                    data-field="fechaNacimiento" id="edit_aut_fechaNac" name="fechaNac" max="{{ now()->subYears(18)->toDateString() }}" min="1900-01-01"
                    value="{{ old('fechaNac') }}" required>
                <div class="invalid-feedback" id="edit_aut_fechaNacFeedback">
                    @error('fechaNac', 'editarAutor') {{ $message }} @else La fecha de nacimiento es obligatoria. @enderror
                </div>
            </div>


            <div class="form-group mt-3 mb-3">
                <label for="edit_pais_autor">País de origen</label>
                <select class="form-control select2" id="edit_pais_autor" data-field="pais" name="pais"
                data-placeholder="Seleccione un país" required>
                    <option value=""></option>
                    @foreach ($paises as $pais)
                        <option value="{{ $pais }}" @selected(old('pais') == $pais)>{{ $pais }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback d-block" id="edit_aut_paisFeedback">
                    @error('pais', 'editarAutor') {{ $message }} @enderror
                </div>
            </div>
</form>