<form id="formEditarCategoria" method = "POST">
            @csrf
            @method('PUT')
            <div class="form-group mt-3 mb-3">
                <label for="edit_nombre">Nombre de la categoria </label>
                <input type="text" class="form-control" data-field="nombre" required = "" id="edit_nombre" name = "nombre" required>
            </div>
</form>