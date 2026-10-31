document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('modalEditarAutor');
    const form = document.getElementById('formEditarAutor');
    const nombre = document.getElementById('edit_aut_nombre');
    const nombreFb = document.getElementById('edit_aut_nombreFeedback');
    const apellido = document.getElementById('edit_aut_apellido');
    const apellidoFb = document.getElementById('edit_aut_apellidoFeedback');
    const fecha = document.getElementById('edit_aut_fechaNac');
    const fechaFb = document.getElementById('edit_aut_fechaNacFeedback');
    const pais = document.getElementById('edit_pais_autor');
    const paisFb = document.getElementById('edit_aut_paisFeedback');
    const idInput = document.getElementById('edit_autor_id');
    const boton = document.getElementById('btnEditarAutor');
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
            idInput.value = e.relatedTarget.dataset.id;
            [nombre, apellido, fecha].forEach(el => el.classList.remove('is-invalid'));
            nombreFb.textContent = 'El nombre es obligatorio.';
            apellidoFb.textContent = 'El apellido es obligatorio.';
            fechaFb.textContent = 'La fecha de nacimiento es obligatoria.';
            paisFb.textContent = '';
        }
        setTimeout(actualizarBoton, 0);
    });

    actualizarBoton();

    if (window.REABRIR_MODAL_EDITAR_AUTOR) {
        new bootstrap.Modal(modalEl).show();
    }
});