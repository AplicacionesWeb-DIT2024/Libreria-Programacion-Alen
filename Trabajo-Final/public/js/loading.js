$(document).ready(function () {

    function showLoading(text) {
        $('#loadingText').text(text || 'Cargando...');
        $('#loadingOverlay').removeClass('d-none');
    }

    $('form').on('submit', function () {
        const texto = $(this).data('loading-text');
        showLoading(texto);
    });

    // Links que cambian página (editar, etc.)
    $('a[href]:not([href^="#"]):not([data-bs-toggle])').on('click', function () {
        const texto = $(this).data('loading-text');
        showLoading(texto);
    });

});
