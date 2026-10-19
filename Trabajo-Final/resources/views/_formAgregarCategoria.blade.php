<form action = "{{route('categorias.store')}}" method = "POST" id="formAgregarCategoria">
            @csrf
            <div class="form-group mt-3 mb-3">
                <label for="nombre">Nombre de la categoria </label>
                <input type="text" class="form-control" required = "" id="nombre" name = "nombre">
            </div>
</form>