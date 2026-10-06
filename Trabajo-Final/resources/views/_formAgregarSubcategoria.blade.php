<form action = "{{route('subcategorias.store')}}" method = "POST" id="formAgregarSubcategoria">
            @csrf
            <div class="form-group mt-3 mb-3">
                <label for="nombre">Nombre de la subcategoria </label>
                <input type="text" class="form-control" required = "" id="nombre" name = "nombre">
            </div>
            <div class="form-group mt-3">
                <label for="pais"> Categoria a la que pertenece </label>
            </div>
           <select class="form-control select2"
                name="categoria"
                data-placeholder="Seleccione una categoria">
                <option value=""></option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                @endforeach
            </select>
</form>