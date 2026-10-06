<form id="formEditarEditorial" method = "POST">
            @csrf
            @method('PUT')
            <div class="form-group mt-3 mb-3">
                <label for="edit_nombre">Nombre de la editorial </label>
                <input type="text" class="form-control" data-field="nombre" required = "" id="edit_nombre" name = "nombre" required>
            </div>
            <div class="form-group mt-3">
                <label for="edit_pais"> Pais de origen </label>
            </div>
           <select class="form-control select2" id = "edit_pais" data-field="pais"
                name="pais"
                data-placeholder="Seleccione un país">
                <option value=""></option>
                @foreach ($paises as $pais)
                    <option value="{{ $pais }}">{{ $pais }}</option>
                @endforeach
            </select>
            <div class="form-group mt-3 mb-3">
                <button type="submit" class="btn btn-primary btn-block btn-login align-items-center"> Guardar cambios</button>
            </div>
</form>
        