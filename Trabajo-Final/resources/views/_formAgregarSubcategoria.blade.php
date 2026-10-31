<form action = "{{route('subcategorias.store')}}" method = "POST" id="formAgregarSubcategoria" novalidate>
            @csrf
            <div class="form-group mt-3 mb-3">
                <label for="nombre">Nombre de la subcategoria </label>
                <input type="text" class="form-control @error('nombre', 'agregarSubcategoria') is-invalid @enderror"
                    id="sub_nombre" name="nombre" maxlength="255" value="{{ old('nombre') }}" required>
                <div class="invalid-feedback" id="sub_nombreFeedback">
                    @error('nombre', 'agregarSubcategoria') {{ $message }} @else El nombre es obligatorio. @enderror
                </div>
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="sub_categoria">Categoría a la que pertenece</label>
                <select class="form-control select2 @error('categoria', 'agregarSubcategoria') is-invalid @enderror"
                id="sub_categoria" name="categoria"
                data-placeholder="Seleccione una categoría" required>
                    <option value=""></option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}" @selected(old('categoria') == $categoria->id)>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback d-block" id="sub_categoriaFeedback">
                    @error('categoria', 'agregarSubcategoria') {{ $message }} @enderror
                </div>
            </div>
</form>