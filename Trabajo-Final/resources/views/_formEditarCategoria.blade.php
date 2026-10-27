<form id="formEditarCategoria" method="POST"
      action="{{ old('categoria_id') ? route('categorias.update', old('categoria_id')) : '' }}" novalidate>
    @csrf
    @method('PUT')
    <input type="hidden" name="categoria_id" id="edit_categoria_id" value="{{ old('categoria_id') }}">

    <div class="form-group mt-3 mb-3">
        <label for="edit_nombre">Nombre de la categoría</label>
        <input type="text"
               class="form-control @error('nombre', 'editarCategoria') is-invalid @enderror"
               data-field="nombre" id="edit_nombre" name="nombre" maxlength="255"
               value="{{ old('nombre') }}" required>
        <div class="invalid-feedback" id="edit_nombreFeedback">
            @error('nombre', 'editarCategoria') {{ $message }} @else El nombre es obligatorio. @enderror
        </div>
    </div>
</form>