<form action = "{{route('editoriales.store')}}" method = "POST" id="formAgregarEditorial" novalidate>
            @csrf
            <div class="form-group mt-3 mb-3">
                <label for="nombre_edi">Nombre de la editorial </label>
                <input type="text" class="form-control @error('nombre', 'agregarEditorial') is-invalid @enderror"
                    id="nombre_edi" name="nombre" maxlength="255" value="{{ old('nombre') }}" required>
                <div class="invalid-feedback" id="edi_nombreFeedback">
                    @error('nombre', 'agregarEditorial') {{ $message }} @else El nombre es obligatorio. @enderror
                </div>
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="nombre_edi">País</label>
                <select class="form-control select2" id="pais_edi" name="pais"
                        data-placeholder="Seleccione un país" required>
                <option value=""></option>
                @foreach ($paises as $pais)
                    <option value="{{ $pais }}" @selected(old('pais') == $pais)>{{ $pais }}</option>
                @endforeach
                </select>
                <div class="invalid-feedback d-block" id="edi_paisFeedback">
                    @error('pais', 'agregarEditorial') {{ $message }} @enderror
                </div>
            </div>
</form>
        