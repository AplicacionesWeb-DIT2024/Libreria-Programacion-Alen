<form id="formModificarEditorial" method = "POST" action="{{ old('editorial_id') ? route('editoriales.update', old('editorial_id')) : '' }}" novalidate>
            @csrf
            @method('PUT')
            <input type="hidden" name="editorial_id" id="edit_editorial_id" value="{{ old('editorial_id') }}">

            <div class="form-group mt-3 mb-3">
                <label for="edit_nombre_edi">Nombre de la editorial </label>
                <input type="text" class="form-control @error('nombre', 'editarEditorial') is-invalid @enderror"
                        data-field="nombre" id="edit_nombre_edi" name="nombre" maxlength="255" value="{{ old('nombre') }}" required>
                <div class="invalid-feedback" id="edit_nombre_ediFeedback">
                    @error('nombre', 'editarEditorial') {{ $message }} @else El nombre es obligatorio. @enderror
                </div>
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="edit_pais">País</label>
                <select class="form-control select2" data-field="pais" id="edit_pais" name="pais"
                    data-placeholder="Seleccione un país" required>
                <option value=""></option>
                @foreach ($paises as $pais)
                    <option value="{{ $pais }}" @selected(old('pais') == $pais)>{{ $pais }}</option>
                @endforeach
                </select>
                <div class="invalid-feedback d-block" id="edit_paisFeedback">
                    @error('pais', 'editarEditorial') {{ $message }} @enderror
                </div>
            </div>
</form>
        