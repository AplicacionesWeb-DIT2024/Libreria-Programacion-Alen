document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('modalAgregarAutor');
    const form = document.getElementById('formAgregarAutor');
    const nombre = document.getElementById('aut_nombre');
    const nombreFb = document.getElementById('aut_nombreFeedback');
    const apellido = document.getElementById('aut_apellido');
    const apellidoFb = document.getElementById('aut_apellidoFeedback');
    const fecha = document.getElementById('aut_fechaNac');
    const fechaFb = document.getElementById('aut_fechaNacFeedback');
    const pais = document.getElementById('aut_pais');
    const paisFb = document.getElementById('aut_paisFeedback');
    const boton = document.getElementById('btnAgregarAutor');
    if (!modalEl || !form || !nombre || !apellido || !fecha || !pais || !boton) return;

    const fechaValida = () => fecha.value !== '' && fecha.value <= fecha.max && fecha.value >= fecha.min;

    const completo = () =>
        nombre.value.trim() !== '' &&
        apellido.value.trim() !== '' &&
        fechaValida() &&
        pais.value !== '';

    const actualizarBoton = () => { boton.disabled = !completo(); };

    nombre.addEventListener('input', () => { nombre.classList.remove('is-invalid'); actualizarBoton(); });
    apellido.addEventListener('input', () => { apellido.classList.remove('is-invalid'); actualizarBoton(); });
    fecha.addEventListener('input', () => { fecha.classList.remove('is-invalid'); actualizarBoton(); });
    $(pais).on('change', () => { paisFb.textContent = ''; actualizarBoton(); });

    form.addEventListener('submit', function (e) {
        nombre.value = nombre.value.trim();
        apellido.value = apellido.value.trim();
        let ok = true;

        if (nombre.value === '') {
            nombreFb.textContent = 'El nombre es obligatorio.';
            nombre.classList.add('is-invalid');
            ok = false;
        }
        if (apellido.value === '') {
            apellidoFb.textContent = 'El apellido es obligatorio.';
            apellido.classList.add('is-invalid');
            ok = false;
        }
        if (fecha.value === '') {
            fechaFb.textContent = 'La fecha de nacimiento es obligatoria.';
            fecha.classList.add('is-invalid');
            ok = false;
        } else if (!fechaValida()) {
            fechaFb.textContent = 'El autor debe tener al menos 18 años.';
            fecha.classList.add('is-invalid');
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
            apellido.value = '';
            fecha.value = '';
            $(pais).val('').trigger('change');
            [nombre, apellido, fecha].forEach(el => el.classList.remove('is-invalid'));
            paisFb.textContent = '';
        }
        setTimeout(actualizarBoton, 0);
    });

    actualizarBoton();

    if (window.REABRIR_MODAL_AGREGAR_AUTOR) {
        new bootstrap.Modal(modalEl).show();
    }
});