const SELECTORES_AUTOR = 'select[name="autor"], select[name="autor2"], select[name="autor3"]';

function syncAuthorSelects($form) {
    const $selects = $form.find(SELECTORES_AUTOR);

    const selectedValues = $selects.map(function () {
        return $(this).val();
    }).get().filter(v => v !== "" && v !== null);

    $selects.each(function () {
        const $select = $(this);
        $select.find('option').each(function () {
            const value = $(this).val();
            const usadaEnOtro = value !== "" &&
                                selectedValues.includes(value) &&
                                $select.val() !== value;
            $(this).prop('disabled', usadaEnOtro);
        });
    });
}

$(document).on('change', SELECTORES_AUTOR, function () {
    syncAuthorSelects($(this).closest('form'));
});

$(document).ready(function () {
    $('form').each(function () { syncAuthorSelects($(this)); });
});