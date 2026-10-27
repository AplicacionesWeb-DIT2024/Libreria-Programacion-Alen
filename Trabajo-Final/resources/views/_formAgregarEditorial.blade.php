<form action = "{{route('editoriales.store')}}" method = "POST" id="formAgregarEditorial">
            @csrf
            <div class="form-group mt-3 mb-3">
                <label for="nombre">Nombre de la editorial </label>
                <input type="text" class="form-control" required = "" id="nombre" name = "nombre">
            </div>
            <div class="form-group mt-3">
                <label for="pais"> Pais de origen </label>
            </div>
           <select class="form-control select2"
                name="pais"
                data-placeholder="Seleccione un país">
                <option value=""></option>
                @foreach ($paises as $pais)
                    <option value="{{ $pais }}">{{ $pais }}</option>
                @endforeach
            </select>
</form>
        