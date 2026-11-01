$(document).ready(function () {

    $('.datatable').each(function () {
        const table = $(this).DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json"
            }
        });

        const filtroEstado = $(this).data('filtro-estado');

        if (filtroEstado) {
            $(filtroEstado).on('change', function () {
                table.column(2).search(this.value).draw();
            });
        }
    });

});
