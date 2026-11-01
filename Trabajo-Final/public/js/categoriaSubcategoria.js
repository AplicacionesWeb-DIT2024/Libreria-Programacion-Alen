function cargarSubcategorias($sub, categoriaId) {
    $sub.empty();

    const lista = subcategoriasPorCategoria[categoriaId];

    if (!categoriaId || !lista) {
        $sub.append('<option value="">Seleccione primero una categoría</option>');
        $sub.prop('disabled', true).trigger('change.select2');
        return;
    }

    $sub.append('<option value="">Seleccione una subcategoría</option>');
    lista.forEach(function (sub) {
        $sub.append(new Option(sub.nombre, sub.id));
    });
    $sub.prop('disabled', false).trigger('change.select2');
}

$(document).on('change', 'select[name="categoria"]', function () {
    const $sub = $(this).closest('form').find('select[name="subcategoria"]');
    cargarSubcategorias($sub, this.value);
});