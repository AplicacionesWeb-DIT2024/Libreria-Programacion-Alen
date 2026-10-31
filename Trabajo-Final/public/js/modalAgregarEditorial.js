document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('modalAgregarEditorial');
    const form = document.getElementById('formAgregarEditorial');
    const nombre = document.getElementById('nombre_edi');
    const nombreFb = document.getElementById('edi_nombreFeedback');
    const pais = document.getElementById('pais_edi');
    const paisFb = document.getElementById('edi_paisFeedback');
    const boton = document.getElementById('btnAgregarEditorial');
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
            nombre.value = '';
            $(pais).val('').trigger('change');
            nombre.classList.remove('is-invalid');
            paisFb.textContent = '';
        }
        setTimeout(actualizarBoton, 0);
    });

    actualizarBoton();

    if (window.REABRIR_MODAL_AGREGAR_EDITORIAL) {
        new bootstrap.Modal(modalEl).show();
    }
});