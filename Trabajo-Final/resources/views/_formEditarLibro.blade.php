<form id="formEditarLibro" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="mb-4">
        <h5 class="mb-3">
            <i class="fas fa-book"></i> Información General
        </h5>
        <hr class="mb-3">
        <div class="row">
            <div class="col">

                <div class="form-group">
                    <label for="editar_nombre"><i class="fa-solid fa-book"></i> Nombre del libro</label>
                    <input type="text" class="form-control" data-field = "nombre" id="editar_nombre" name="nombre" required>
                </div>

                <div class="form-group mt-3 mb-3">
                    <label for="editar_idioma"><i class="fas fa-language"></i> Idioma</label>
                </div>
                <select class="form-control select2" id="editar_idioma" name="idioma" data-field = "idioma" required data-placeholder="Seleccione un idioma">
                        <option value="">Seleccione un idioma</option>
                        @foreach ($idiomas as $codigo => $nombreIdioma)
                            <option value="{{ $codigo }}">{{ $nombreIdioma }}</option>
                        @endforeach
                </select>

                <div class="form-group mt-3 mb-3">
                    <label for="editar_categoria">
                        <i class="fas fa-tag"></i> Categoría
                    </label>
                </div>
                <select class="form-control select2" id="editar_categoria" name="categoria" data-field = "categoria" data-placeholder="Seleccione una categoria" required>
                        <option value="">Seleccione una categoria</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                        @endforeach
                </select>
                <div class="form-group mt-3 mb-3">
                    <label for="editar_subcategoria">
                        <i class="fas fa-bookmark"></i> Subcategoría
                    </label>
                </div>
                <select class="form-control select2" id="editar_subcategoria" data-field = "subcategoria" name="subcategoria" required disabled>
                        <option value="">Seleccione primero una categoría</option>
                </select>
                <div class="form-group mt-3 mb-3">
                    <label for="editar_editorial">
                        <i class="fas fa-building"></i> Editorial
                    </label>
                </div>
                <select class="form-control select2" id="editar_editorial" data-field = "editorial" name="editorial" required data-placeholder="Seleccione una editorial">
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
                    <label for="editar_autor">Autor principal</label>
                </div>
                <select class="form-control select2" id="editar_autor" data-field = "autor" name="autor">
                        <option value="">Seleccione un autor</option>
                        @foreach ($autores as $autor)
                            <option value="{{ $autor->id }}"> {{$autor->apellido}}, {{ $autor->nombre }}</option>
                        @endforeach
                </select>

                <div class="form-group mt-3 mb-3">
                    <label for="editar_autor2">Autor secundario (opcional)</label>
                </div>
                <select class="form-control select2" id="editar_autor2" name="autor2" data-field = "autor2">
                        <option value="">Ninguno</option>
                        @foreach ($autores as $autor)
                            <option value="{{ $autor->id }}">{{$autor->apellido}}, {{ $autor->nombre }}</option>
                        @endforeach
                </select>

                <div class="form-group mt-3 mb-3">
                    <label for="editar_autor3">Autor secundario (opcional)</label>
                </div>
                <select class="form-control select2" id="editar_autor3" name="autor3" data-field = "autor3">
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
                    <label for="editar_pais_origen"><i class="fas fa-flag"></i> País de origen</label>
                </div>
                <select class="form-control select2"
                        name="pais_origen"
                        data-placeholder="Seleccione un país" id="editar_pais_origen" data-field = "pais_origen"required>
                        <option value=""></option>
                        @foreach ($paises as $pais)
                            <option value="{{ $pais }}">{{ $pais }}</option>
                        @endforeach
                </select>

                <div class="form-group mt-3 mb-3">
                    <label for="editar_pais_impresion"><i class="fas fa-print"></i> País donde se imprimió</label>
                </div>
                <select class="form-control select2"
                        name="pais_impresion" id="editar_pais_impresion"
                        data-placeholder="Seleccione un país" data-field = "pais_impresion" required>
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
                    <label for="editar_edicion"><i class="fas fa-layer-group"></i> Edición</label>
                    <input type="number" class="form-control" id="editar_edicion" data-field = "edicion" name="edicion" required>
                </div>

                <div class="form-group mt-3 mb-3">
                    <label for="editar_anio_publicacion"><i class="fas fa-calendar-alt"></i> Año de publicación</label>
                    <input type="number" class="form-control" id="editar_anio_publicacion" data-field = "anio_publicacion" name="anio_publicacion" required max="{{ date('Y') }}">
                </div>

                <h5 class="mb-3">
                    <i class="fas fa-boxes"></i> Inventario
                </h5>
                <hr class="mb-4">

                <div class="form-group mt-3 mb-3">
                    <label for="editar_stock"><i class="fas fa-cubes"></i> Stock disponible</label>
                    <input type="number" class="form-control" id="editar_stock" data-field = "stock" name="stock" required>
                </div>

                <div class="form-group mt-3 mb-3">
                    <label for="editar_precio"><i class="fas fa-dollar-sign"></i> Precio</label>
                    <input type="text" class="form-control" id="editar_precio" data-field = "precio" name="precio" required>
                </div>

                <h5 class="mb-3">
                    <i class="fas fa-image"></i> Imágenes
                </h5>
                <hr class="mb-4">

                <div class="form-group mt-3 mb-3">
                    <label for="editar_imagen_original">Imagen principal</label>
                    <img data-preview="imagen_original" class="img-thumbnail d-block mb-2"
                    style="display:none; max-height:120px;">
                    <input type="file" class="form-control" id="editar_imagen_original" data-field = "imagen_original" accept="image/*" name="imagen_original">
                    <small class="text-muted">Dejar vacío para conservar la imagen actual.</small>
                </div>

                <div class="form-group mt-3 mb-3">
                    <label for="editar_imagen_referencia_2">Imagen de referencia 2 (opcional)</label>
                    <img data-preview="imagen_referencia_2" class="img-thumbnail d-block mb-2"
                    style="display:none; max-height:120px;">
                    <div class="eliminar-wrapper form-check mb-2" style="display:none;">
                        <input type="checkbox" class="form-check-input chk-eliminar"
                            id="editar_eliminar_imagen_referencia_2"
                            name="eliminar_imagen_referencia_2" value="1">
                        <label class="form-check-label text-danger" for="editar_eliminar_imagen_referencia_2">
                            <i class="fas fa-trash-alt"></i> Eliminar esta imagen
                        </label>
                    </div>
                    <input type="file" class="form-control" id="editar_imagen_referencia_2" accept="image/*" data-field = "imagen_referencia_2" name="imagen_referencia_2">
                </div>

                <div class="form-group mt-3 mb-3">
                    <label for="editar_imagen_referencia_3">Imagen de referencia 3 (opcional)</label>
                    <img data-preview="imagen_referencia_3" class="img-thumbnail d-block mb-2"
                    style="display:none; max-height:120px;">
                    <div class="eliminar-wrapper form-check mb-2" style="display:none;">
                        <input type="checkbox" class="form-check-input chk-eliminar"
                            id="editar_eliminar_imagen_referencia_3"
                            name="eliminar_imagen_referencia_3" value="1">
                        <label class="form-check-label text-danger" for="editar_eliminar_imagen_referencia_3">
                            <i class="fas fa-trash-alt"></i> Eliminar esta imagen
                        </label>
                    </div>
                    <input type="file" class="form-control" id="editar_imagen_referencia_3" accept="image/*" data-field = "imagen_referencia_3" name="imagen_referencia_3">
                </div>
            </div>
        </div>
    </div>
</form>
