document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('modalEditarEditorial');
    const form = document.getElementById('formModificarEditorial');
    const nombre = document.getElementById('edit_nombre_edi');
    const nombreFb = document.getElementById('edit_nombre_ediFeedback');
    const pais = document.getElementById('edit_pais');
    const paisFb = document.getElementById('edit_paisFeedback');
    const idInput = document.getElementById('edit_editorial_id');
    const boton = document.getElementById('btnModificarEditorial');
    if (!modalEl || !form || !nombre || !pais || !boton) return;

    const actualizarBoton = () => {
        boton.disabled = !(nombre.value.trim() !== '' && pais.value !== '');
    };

    nombre.addEventListener('input', () => {
        nombre.classList.remove('is-invalid');
        actualizarBoton();
    });

    $(pais).on('change', () => {
        paisFb.textContent = '';
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
        if (pais.value === '') {
            paisFb.textContent = 'Debe seleccionar un país.';
            ok = false;
        }
        if (!ok) e.preventDefault();
    });

    modalEl.addEventListener('show.bs.modal', function (e) {
        if (e.relatedTarget) {
            idInput.value = e.relatedTarget.dataset.id;
            nombre.classList.remove('is-invalid');
            nombreFb.textContent = 'El nombre es obligatorio.';
            paisFb.textContent = '';
        }
        setTimeout(actualizarBoton, 0); 
    });

    actualizarBoton();

    if (window.REABRIR_MODAL_MODIFICAR_EDITORIAL) {
        new bootstrap.Modal(modalEl).show();
    }
});