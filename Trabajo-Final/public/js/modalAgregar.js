$(document).on('change', '#modalAgregarLibro input[type="file"]', function () {
    const $img = $(this).closest('.form-group').find('img.preview-nueva');

    const anterior = $img.data('objectUrl');
    if (anterior) URL.revokeObjectURL(anterior);

    const file = this.files[0];

    if (file) {
        const url = URL.createObjectURL(file);
        $img.data('objectUrl', url).attr('src', url).show();
    } else {
        $img.removeData('objectUrl').removeAttr('src').hide();
    }
});


$('#modalAgregarLibro').on('hidden.bs.modal', function () {
    const $modal = $(this);

    this.querySelector('form').reset();

    $modal.find('img.preview-nueva').each(function () {
        const url = $(this).data('objectUrl');
        if (url) URL.revokeObjectURL(url);
        $(this).removeData('objectUrl').removeAttr('src').hide();
    });

    $modal.find('select').trigger('change');
});