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
    const SOLO_EMAIL = /\s/g;
    const EMAIL = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

    const campos = [nombre, apellido, email, username];
    const feedback = el => form.querySelector(`[data-fb="${el.name}"]`);
    const ayudaDe = el => form.querySelector(`#${el.id}-help`);

    const guias = new Map();   
    const timers = new Map();

    campos.forEach(el => {
        const a = ayudaDe(el);
        if (a) guias.set(el, { texto: a.textContent.trim(), clase: a.className });
    });

    function restaurarGuia(el) {
        const a = ayudaDe(el), g = guias.get(el);
        if (!a || !g) return;
        clearTimeout(timers.get(el));
        a.textContent = g.texto;
        a.className = g.clase;
        a.hidden = false;
    }

    function avisar(el, mensaje) {
        const a = ayudaDe(el);
        if (!a) return;
        a.textContent = mensaje;
        a.className = 'form-text text-danger';
        a.hidden = false;
        clearTimeout(timers.get(el));
        timers.set(el, setTimeout(() => restaurarGuia(el), 3000));
    }


    function filtrarConAviso(el, regex, mensaje, inicioRegex, mensajeInicio) {
        el.addEventListener('input', () => {
            let limpio = el.value.replace(regex, '');
            let msg = mensaje;

            if (inicioRegex) {
                const sinInicio = limpio.replace(inicioRegex, '');
                if (sinInicio !== limpio) { limpio = sinInicio; msg = mensajeInicio; }
            }

            if (limpio === el.value) return;

            let pos = null;
            try { pos = el.selectionStart; } catch (e) {}
            const quitados = el.value.length - limpio.length;
            el.value = limpio;
            if (pos !== null) {
                const nueva = Math.max(0, pos - quitados);
                try { el.setSelectionRange(nueva, nueva); } catch (e) {}
            }

            avisar(el, msg);
        });
    }


    filtrarConAviso(nombre, SOLO_LETRAS, 'El nombre solo admite letras, espacios, apóstrofes, puntos y guiones.', /^[\s'’.-]+/, 'El nombre debe empezar con una letra.');
    filtrarConAviso(apellido, SOLO_LETRAS, 'El apellido solo admite letras, espacios, apóstrofes, puntos y guiones.', /^[\s'’.-]+/, 'El apellido debe empezar con una letra.');
    filtrarConAviso(username, SOLO_USUARIO,'El usuario solo admite letras, números, puntos, guiones y guiones bajos, sin espacios.');
    filtrarConAviso(email, SOLO_EMAIL,'El email no puede contener espacios.');

     function mostrar(el, msg) {
        const fb = feedback(el);
        if (fb) fb.textContent = msg;
        el.classList.toggle('is-invalid', msg !== '');

        const a = ayudaDe(el);
        if (!a) return;
        if (msg !== '') {
            clearTimeout(timers.get(el));
            a.hidden = true;            
        } else if (a.className === guias.get(el).clase) {
            a.hidden = false;           
        }
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
            campos.forEach(el => { mostrar(el, ''); restaurarGuia(el); });
        }
        setTimeout(actualizarBoton, 0);
    });

    campos.forEach(el => {
        const fb = feedback(el);
        if (fb && fb.textContent.trim() !== '') {
            const a = ayudaDe(el);
            if (a) a.hidden = true;
        }
    });

    actualizarBoton();

    if (window.REABRIR_MODAL_AGREGAR_ADMINISTRADOR) {
        new bootstrap.Modal(modalEl).show();
    }
});