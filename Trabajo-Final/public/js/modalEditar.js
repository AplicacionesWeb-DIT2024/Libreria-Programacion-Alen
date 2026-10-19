document.addEventListener('DOMContentLoaded', function () {

     $('#edit_pais').select2({
        dropdownParent: $('#modalEditarEditorial')
    });

    $('#edit_pais_autor').select2({
        dropdownParent: $('#modalEditarAutor')
    });

    $('#modalEditarSubcategoria select.select2').select2({
        dropdownParent: $('#modalEditarSubcategoria')
    });

     $('#modalEditarLibro select.select2').select2({
        dropdownParent: $('#modalEditarLibro')
    });

    const modalesEditar = [
        'modalEditarEditorial',
        'modalEditarCategoria',
        'modalEditarSubcategoria',
        'modalEditarAutor',
        'modalEditarLibro'
    ];

    modalesEditar.forEach(function (modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return; 

        modal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const form = modal.querySelector('form');

            if (button.dataset.action) {
                form.setAttribute('action', button.dataset.action);
            }

        
            form.querySelectorAll('[data-field]').forEach(function (input) {
                if (input.type === 'file') {
                    input.value = '';
                    return;
                }
                const campo = input.dataset.field; 
                const valor = button.dataset[campo]; 

                if (valor === undefined) return;

                if (window.jQuery && $(input).hasClass('select2-hidden-accessible')) {
                    $(input).val(valor).trigger('change');
                } else {
                    input.value = valor;
                }
            });
        });
    });

    $(document).on('change', '#modalEditarLibro input[type="file"]', function () {
        const file = this.files[0];
        const $img = $(this).closest('.form-group').find('img[data-preview]');
        if (file && $img.length) {
            $img.attr('src', URL.createObjectURL(file)).show();
        }
    });


    const modalEditarLibro = document.getElementById('modalEditarLibro');
    if (modalEditarLibro) {
        modalEditarLibro.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            modalEditarLibro.querySelectorAll('[data-preview]').forEach(function (img) {
                const url = button.dataset[img.dataset.preview];
                const grupo = img.closest('.form-group');
                const wrapper = grupo.querySelector('.eliminar-wrapper');
                const chk = grupo.querySelector('.chk-eliminar');

                if (chk) chk.checked = false;
                img.style.opacity = '1';

                if (url) {
                    img.src = url;
                    img.style.display = 'block';
                    if (wrapper) wrapper.style.display = 'block'; 
                } else {
                    img.style.display = 'none';
                    if (wrapper) wrapper.style.display = 'none';
                }
            });
        });


        
    }

    $(document).on('change', '#modalEditarLibro .chk-eliminar', function () {
        const $grupo = $(this).closest('.form-group');
        if (this.checked) {
            $grupo.find('input[type="file"]').val('');
        }
        $grupo.find('img[data-preview]').css('opacity', this.checked ? 0.4 : 1);
    });

    $(document).on('change', '#modalEditarLibro input[type="file"]', function () {
        const file = this.files[0];
        const $grupo = $(this).closest('.form-group');
        const $img = $grupo.find('img[data-preview]');

        if (file) {
            $grupo.find('.chk-eliminar').prop('checked', false);
            $img.css('opacity', 1).attr('src', URL.createObjectURL(file)).show();
        }
    });
});