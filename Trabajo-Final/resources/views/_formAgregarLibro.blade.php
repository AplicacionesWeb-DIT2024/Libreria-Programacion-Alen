<form action="{{ route('libros.store') }}" method="POST" enctype="multipart/form-data" id="formAgregarLibro">
    @csrf
    <div class="mb-4">
        <h5 class="mb-3">
            <i class="fas fa-book"></i> Información General
        </h5>
        <hr class="mb-3">
        <div class="row">
            <div class="col">

                <div class="form-group">
                    <label for="nombre"><i class="fa-solid fa-book"></i> Nombre del libro</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>

                <div class="form-group mt-3 mb-3">
                    <label for="idioma"><i class="fas fa-language"></i> Idioma</label>
                </div>
                <select class="form-control select2" id="idioma" name="idioma" required data-placeholder="Seleccione un idioma">
                        <option value="">Seleccione un idioma</option>
                        @foreach ($idiomas as $codigo => $nombreIdioma)
                            <option value="{{ $codigo }}">{{ $nombreIdioma }}</option>
                        @endforeach
                </select>

                <div class="form-group mt-3 mb-3">
                    <label for="categoria">
                        <i class="fas fa-tag"></i> Categoría
                    </label>
                </div>
                <select class="form-control select2" id="categoria" name="categoria" data-placeholder="Seleccione una categoria" required>
                        <option value="">Seleccione una categoria</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                        @endforeach
                </select>
                <div class="form-group mt-3 mb-3">
                    <label for="subcategoria">
                        <i class="fas fa-bookmark"></i> Subcategoría
                    </label>
                </div>
                <select class="form-control select2" id="subcategoria" name="subcategoria" required disabled>
                        <option value="">Seleccione primero una categoría</option>
                </select>
                <div class="form-group mt-3 mb-3">
                    <label for="editorial">
                        <i class="fas fa-building"></i> Editorial
                    </label>
                </div>
                <select class="form-control select2" id="editorial" name="editorial" required data-placeholder="Seleccione una editorial">
                        <option value="">Seleccione una editorial</option>
                        @foreach ($editoriales as $editorial)
                            <option value="{{ $editorial->id }}">{{ $editorial->nombre }}</option>
                        @endforeach
                </select>

                <h5 class="mb-3 mt-3">
                    <i class="fas fa-user-edit"></i> Autores
                </h5>
                <hr class="mb-2">
                <div class="form-group mt-3 mb-3">
                    <label for="autor">Autor principal</label>
                </div>
                <select class="form-control select2" id="autor" name="autor">
                        <option value="">Seleccione un autor</option>
                        @foreach ($autores as $autor)
                            <option value="{{ $autor->id }}"> {{$autor->apellido}}, {{ $autor->nombre }}</option>
                        @endforeach
                </select>

                <div class="form-group mt-3 mb-3">
                    <label for="autor2">Autor secundario (opcional)</label>
                </div>
                <select class="form-control select2" id="autor2" name="autor2">
                        <option value="">Ninguno</option>
                        @foreach ($autores as $autor)
                            <option value="{{ $autor->id }}">{{$autor->apellido}}, {{ $autor->nombre }}</option>
                        @endforeach
                </select>

                <div class="form-group mt-3 mb-3">
                    <label for="autor3">Autor secundario (opcional)</label>
                </div>
                <select class="form-control select2" id="autor3" name="autor3">
                        <option value="">Ninguno</option>
                        @foreach ($autores as $autor)
                            <option value="{{ $autor->id }}">{{$autor->apellido}}, {{ $autor->nombre }}</option>
                        @endforeach
                </select>

                <h5 class="mb-3 mt-3">
                    <i class="fas fa-globe-americas"></i> Ubicación
                </h5>
                <hr class="mb-4">

                <div class="form-group mt-3 mb-3">
                    <label for="pais_origen"><i class="fas fa-flag"></i> País de origen</label>
                </div>
                <select class="form-control select2"
                        name="pais_origen"
                        data-placeholder="Seleccione un país" id="pais_origen" required>
                        <option value=""></option>
                        @foreach ($paises as $pais)
                            <option value="{{ $pais }}">{{ $pais }}</option>
                        @endforeach
                </select>

                <div class="form-group mt-3 mb-3">
                    <label for="pais_impresion"><i class="fas fa-print"></i> País donde se imprimió</label>
                </div>
                <select class="form-control select2"
                        name="pais_impresion" id="pais_impresion"
                        data-placeholder="Seleccione un país" required>
                        <option value=""></option>
                        @foreach ($paises as $pais)
                            <option value="{{ $pais }}">{{ $pais }}</option>
                        @endforeach
                </select>
            </div>
            <div class="col"> 
                <h5 class="mb-3">
                    <i class="fas fa-info-circle"></i> Detalles de Edición
                </h5>
                <hr class="mb-4">

                <div class="form-group mt-3 mb-3">
                    <label for="edicion"><i class="fas fa-layer-group"></i> Edición</label>
                    <input type="number" class="form-control" id="edicion" name="edicion" required>
                </div>

                <div class="form-group mt-3 mb-3">
                    <label for="anio_publicacion"><i class="fas fa-calendar-alt"></i> Año de publicación</label>
                    <input type="number" class="form-control" id="anio_publicacion" name="anio_publicacion" required max="{{ date('Y') }}">
                </div>

                <h5 class="mb-3">
                    <i class="fas fa-boxes"></i> Inventario
                </h5>
                <hr class="mb-4">

                <div class="form-group mt-3 mb-3">
                    <label for="stock"><i class="fas fa-cubes"></i> Stock disponible</label>
                    <input type="number" class="form-control" id="stock" name="stock" required>
                </div>

                <div class="form-group mt-3 mb-3">
                    <label for="precio"><i class="fas fa-dollar-sign"></i> Precio</label>
                    <input type="text" class="form-control" id="precio" name="precio" required>
                </div>

                <h5 class="mb-3">
                    <i class="fas fa-image"></i> Imágenes
                </h5>
                <hr class="mb-4">

                <div class="form-group mt-3 mb-3">
                    <label for="imagen_original">Imagen principal</label>
                    <img class="preview-nueva img-thumbnail mb-2" style="display:none; max-height:120px;" alt="Vista previa">
                    <input type="file" class="form-control" id="imagen_original" accept="image/*" name="imagen_original" required>
                </div>

                <div class="form-group mt-3 mb-3">
                    <label for="imagen_referencia_2">Imagen de referencia 2 (opcional)</label>
                    <img class="preview-nueva img-thumbnail mb-2" style="display:none; max-height:120px;" alt="Vista previa">
                    <input type="file" class="form-control" id="imagen_referencia_2" accept="image/*" name="imagen_referencia_2">
                </div>

                <div class="form-group mt-3 mb-3">
                    <label for="imagen_referencia_3">Imagen de referencia 3 (opcional)</label>
                    <img class="preview-nueva img-thumbnail mb-2" style="display:none; max-height:120px;" alt="Vista previa">
                    <input type="file" class="form-control" id="imagen_referencia_3" accept="image/*" name="imagen_referencia_3">
                </div>
            </div>
        </div>
    </div>

</form>