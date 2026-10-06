<form id="formEditarAutor" method = "POST">
            @csrf
            @method('PUT')
            <div class="form-group mt-3 mb-3">
                <label for="nombre">Nombre del autor </label>
                <input type="text" class="form-control" data-field = "nombre" required = "" id="nombre" name = "nombre">
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="nombre">Apellido del autor </label>
                <input type="text" class="form-control" data-field = "apellido" required = "" id="apellido" name = "apellido">
            </div>
             <div class="form-group mt-3 mb-3">
                <label for="fecha"> Fecha de nacimiento </label>
                <input type="date" class = "form-control" id="fechaNac" data-field = "fechaNacimiento" name="fechaNac">
            </div>
            <div class="form-group mt-3">
                <label for="pais"> Pais de origen </label>
            </div>
           <select class="form-control select2" id ="edit_pais_autor" data-field="pais"
                name="pais"
                data-placeholder="Seleccione un país">
                <option value=""></option>
                @foreach ($paises as $pais)
                    <option value="{{ $pais }}">{{ $pais }}</option>
                @endforeach
            </select>
</form>