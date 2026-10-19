$(document).ready(function () {
    $('.select2').each(function () {
        const $modal = $(this).closest('.modal');

        $(this).select2({
            placeholder: $(this).data('placeholder') || 'Seleccione una opción',
            allowClear: true,
            width: '100%',
            dropdownParent: $modal.length
                ? $modal.find('.modal-content')
                : $('body')
        });
    });
});

