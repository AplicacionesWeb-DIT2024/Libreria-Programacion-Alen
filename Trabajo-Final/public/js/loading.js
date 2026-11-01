$(document).ready(function () {

    function showLoading(text) {
        $('#loadingText').text(text || 'Cargando...');
        $('#loadingOverlay').removeClass('d-none');
    }

     function hideLoading() {
        $('#loadingOverlay').addClass('d-none');
    }

    $('form').on('submit', function (e) {
        const texto = $(this).data('loading-text');
        setTimeout(function () {
            if (!e.isDefaultPrevented()) {
                showLoading(texto);
            }
        }, 0);
    });

    $('a[href]:not([href^="#"]):not([data-bs-toggle])').on('click', function () {
        const texto = $(this).data('loading-text');
        showLoading(texto);
    });

    window.addEventListener('pageshow', function (e) {
        if (e.persisted) {
            hideLoading();
        }
    });

});
