function syncAuthorSelects() {
    const $selects = $('#autor, #autor2, #autor3');

    // Obtener valores elegidos (solo los que NO sean "")
    const selectedValues = $selects.map(function () {
        return $(this).val();
    }).get().filter(function (v) {
        return v !== "";
    });

    $selects.each(function () {
        const $select = $(this);

        $select.find('option').each(function () {
            const $option = $(this);
            const value = $option.val();

            const usadaEnOtroSelect =
                value !== "" &&                          
                selectedValues.includes(value) &&        
                $select.val() !== value;                 

            $option.prop('disabled', usadaEnOtroSelect);
        });
    });
}

$(document).ready(function () {
    $('#autor, #autor2, #autor3').on('change', syncAuthorSelects);
    syncAuthorSelects();
});