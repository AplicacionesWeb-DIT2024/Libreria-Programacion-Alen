document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('modalAgregarAdministrador');
    const form = document.getElementById('formAgregarAdministrador');
    const boton = document.getElementById('btnAgregarAdministrador');
    if (!modalEl || !form || !boton) return;

    const nombre = form.querySelector('#adm_nombre');
    const apellido = form.querySelector('#adm_apellido');
    const email = form.querySelector('#adm_email');
    const username = form.querySelector('#adm_username');

    const SOLO_LETRAS = /[^\p{L}\s'’.-]/gu;
    const SOLO_USUARIO = /[^A-Za-z0-9._-]/g;
    const EMAIL = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

    [nombre, apellido].forEach(el => el.addEventListener('input', () => { el.value = el.value.replace(SOLO_LETRAS, ''); }));
    username.addEventListener('input', () => { username.value = username.value.replace(SOLO_USUARIO, ''); });
    email.addEventListener('input', () => { email.value = email.value.replace(/\s/g, ''); });

    const campos = [nombre, apellido, email, username];
    const feedback = el => form.querySelector(`[data-fb="${el.name}"]`);

    function mostrar(el, msg) {
        const fb = feedback(el);
        if (fb) fb.textContent = msg;
        el.classList.toggle('is-invalid', msg !== '');
    }

    function errorCampo(el) {
        const v = el.value.trim();
        if (v === '') return 'Este campo es obligatorio.';
        if (el === email && !EMAIL.test(v)) return 'El email no es válido.';
        if (el === username && v.length < 4) return 'El usuario debe tener al menos 4 caracteres.';
        return '';
    }

    const completo = () => campos.every(el => errorCampo(el) === '');

    const actualizarBoton = () => {
        const bloqueado = !completo();
        boton.classList.toggle('btn-bloqueado', bloqueado);
        boton.setAttribute('aria-disabled', bloqueado ? 'true' : 'false');
    };

    campos.forEach(el => el.addEventListener('input', () => { mostrar(el, ''); actualizarBoton(); }));

    form.addEventListener('submit', function (e) {
        let primero = null;
        campos.forEach(el => {
            el.value = el.value.trim();
            const msg = errorCampo(el);
            mostrar(el, msg);
            if (msg && !primero) primero = el;
        });
        if (primero) {
            e.preventDefault();
            primero.focus();
        }
    });

    modalEl.addEventListener('show.bs.modal', function (e) {
        if (e.relatedTarget) {            
            form.reset();
            campos.forEach(el => mostrar(el, ''));
        }
        setTimeout(actualizarBoton, 0);
    });

    actualizarBoton();

    if (window.REABRIR_MODAL_AGREGAR_ADMINISTRADOR) {
        new bootstrap.Modal(modalEl).show();
    }
});