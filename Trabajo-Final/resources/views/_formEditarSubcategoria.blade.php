<form id="formEditarSubcategoria" method = "POST" action="{{ old('subcategoria_id') ? route('subcategorias.update', old('subcategoria_id')) : '' }}" novalidate>
            @csrf
            @method('PUT')
            <input type="hidden" name="subcategoria_id" id="edit_subcategoria_id" value="{{ old('subcategoria_id') }}">
            <div class="form-group mt-3 mb-3">
                <label for="edit_nombre_sub">Nombre de la subcategoria </label>
                <input type="text" class="form-control @error('nombre', 'editarSubcategoria') is-invalid @enderror"  data-field="nombre" id="edit_nombre_sub" name="nombre" maxlength="255"
                value="{{ old('nombre') }}" required>
                <div class="invalid-feedback" id="edit_nombre_subFeedback">
                    @error('nombre', 'editarSubcategoria') {{ $message }} @else El nombre es obligatorio. @enderror
                </div>
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="edit_categoria_sub">Categoría a la que pertenece</label>
                <select class="form-control select2" data-field="categoria"
                id="edit_categoria_sub" name="categoria"
                data-placeholder="Seleccione una categoría" required>
                <option value=""></option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}" @selected(old('categoria') == $categoria->id)>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
                </select>
                <div class="invalid-feedback d-block" id="edit_categoria_subFeedback">
                    @error('categoria', 'editarSubcategoria') {{ $message }} @enderror
                </div>
            </div>
</form>