<form id="formEditarSubcategoria" method = "POST">
            @csrf
            @method('PUT')
            <div class="form-group mt-3 mb-3">
                <label for="edit_nombre">Nombre de la subcategoria </label>
                <input type="text" class="form-control" data-field="nombre" required = "" id="edit_nombre" name = "nombre" required>
            </div>
            <div class="form-group mt-3">
                <label for="pais"> Categoria a la que pertenece </label>
            </div>
            <select class="form-control select2"  data-field="categoria"
                name="categoria"
                data-placeholder="Seleccione una categoria">
                <option value=""></option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                @endforeach
            </select>
</form>