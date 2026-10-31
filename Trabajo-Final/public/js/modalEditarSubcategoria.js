document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('modalEditarSubcategoria');
    const form = document.getElementById('formEditarSubcategoria');
    const nombre = document.getElementById('edit_nombre_sub');
    const nombreFb = document.getElementById('edit_nombre_subFeedback');
    const categoria = document.getElementById('edit_categoria_sub');
    const categoriaFb = document.getElementById('edit_categoria_subFeedback');
    const idInput = document.getElementById('edit_subcategoria_id');
    const boton = document.getElementById('btnEditarSubcategoria');
    if (!modalEl || !form || !nombre || !categoria || !boton) return;

    const actualizarBoton = () => {
        boton.disabled = !(nombre.value.trim() !== '' && categoria.value !== '');
    };

    nombre.addEventListener('input', () => {
        nombre.classList.remove('is-invalid');
        actualizarBoton();
    });

    $(categoria).on('change', () => {
        categoriaFb.textContent = '';
        actualizarBoton();
    });

    form.addEventListener('submit', function (e) {
        nombre.value = nombre.value.trim();
        let ok = true;
        if (nombre.value === '') {
            nombreFb.textContent = 'El nombre es obligatorio.';
            nombre.classList.add('is-invalid');
            ok = false;
        }
        if (categoria.value === '') {
            categoriaFb.textContent = 'Debe seleccionar una categoría.';
            ok = false;
        }
        if (!ok) e.preventDefault();
    });

    modalEl.addEventListener('show.bs.modal', function (e) {
        if (e.relatedTarget) {
            idInput.value = e.relatedTarget.dataset.id;
            nombre.classList.remove('is-invalid');
            nombreFb.textContent = 'El nombre es obligatorio.';
            categoriaFb.textContent = '';
        }
        setTimeout(actualizarBoton, 0);
    });

    actualizarBoton();

    if (window.REABRIR_MODAL_EDITAR_SUBCATEGORIA) {
        new bootstrap.Modal(modalEl).show();
    }
});