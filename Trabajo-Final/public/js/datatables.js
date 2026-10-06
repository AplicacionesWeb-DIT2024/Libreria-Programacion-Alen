$(document).ready(function () {

    $('.datatable').each(function () {

        const table = $(this).DataTable({
            responsive: true,
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json"
            },
            autoWidth: false,
            pageLength: 10,
            lengthChange: true,
            ordering: true,
            info: true,
            columnDefs: [
                {
                    className: 'dtr-control',
                    orderable: false,
                    targets: 0
                },
                { orderable: false, targets: -1 }, 
                {responsivePriority: 1, targets: -1}
            ]
        });

        $(this).data('datatable-instance', table);
    });

    $('.datatable-search').on('keyup', function () {
        const table = $($(this).data('table')).data('datatable-instance');
        table.search(this.value).draw();
    });

    $('select[data-column]').on('change', function () {
        const table = $($(this).data('table')).data('datatable-instance');
        const column = $(this).data('column');

        table.column(column).search(this.value).draw();
    });

});
