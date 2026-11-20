document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formPerfil');
    if (!form) return;

    const nombre = form.querySelector('#nombre');
    const apellido = form.querySelector('#apellido');
    const username = form.querySelector('#username');
    const email = form.querySelector('#email');
    const actual = form.querySelector('#current_password');
    const nueva = form.querySelector('#password');
    const confirmacion = form.querySelector('#password_confirmation');
    const boton = form.querySelector('button[type="submit"]');
    const obligatorio = form.dataset.obligatorio === '1';
    const ayudaPassword = form.querySelector('#password-help');


    const SOLO_LETRAS = /[^\p{L}\s'’.-]/gu;
    const SOLO_USUARIO = /[^A-Za-z0-9._-]/g;
    const EMAIL = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

    const ayudaDe = el => form.querySelector(`#${el.id}-help`);
    const guias = new Map();
    const timers = new Map();

    [nombre, apellido, username, email].forEach(el => {
        const a = ayudaDe(el);
        if (a) guias.set(el, { texto: a.textContent.trim(), clase: a.className });
    });


    const campos = [nombre, apellido, username, email, actual, nueva, confirmacion];
    const feedback = el => form.querySelector(`[data-fb="${el.name}"]`);

    [nombre, apellido, username, email].forEach(el => {
        const fb = feedback(el);
        if (fb && fb.textContent.trim() !== '') {
            const a = ayudaDe(el);
            if (a) a.hidden = true;
        }
    });

    function mostrar(el, msg) {
        const fb = feedback(el);
        if (fb) 
            fb.textContent = msg;
        el.classList.toggle('is-invalid', msg !== '');

        if (el === nueva) ayudaPassword.hidden = msg !== '';

        const g = guias.get(el);
        const a = ayudaDe(el);
        if (!g || !a) return;
        if (msg !== '') {
            clearTimeout(timers.get(el));
            a.hidden = true;
        } else if (a.className === g.clase) {
            a.hidden = false;
        }
    }

    function errorCampo(el) {
        const v = el.value.trim();

        if (el === nombre || el === apellido) {
            return v === '' ? 'Este campo es obligatorio.' : '';
        }
        if (el === username) {
            if (v === '') return 'Este campo es obligatorio.';
            if (v.length < 4) return 'El usuario debe tener al menos 4 caracteres.';
            return '';
        }
        if (el === email) {
            if (v === '') return 'Este campo es obligatorio.';
            if (!EMAIL.test(v)) return 'El email no es válido.';
            return '';
        }

        if (el === actual) {
            if ((obligatorio || nueva.value !== '') && actual.value === '') return 'Debe ingresar su contraseña actual para cambiarla.';
            return '';
        }
        if (el === nueva) {
            if (obligatorio && nueva.value === '') return 'Debe establecer una nueva contraseña.';
            if (nueva.value !== '' && nueva.value.length < 8) return 'La nueva contraseña debe tener al menos 8 caracteres.';
            return '';
        }
        if (el === confirmacion) {
            if ((obligatorio || nueva.value !== '') && confirmacion.value !== nueva.value) return 'La confirmación no coincide.';
            return '';
        }
        return '';
    }

    nueva.addEventListener('focus', () => {
        actualizarAyudaPassword();
    });

    nueva.addEventListener('blur', () => {
        if (nueva.value === '' && !obligatorio) {
            ayudaPassword.hidden = true;
        }
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
                const n = Math.max(0, pos - quitados);
                try { el.setSelectionRange(n, n); } catch (e) {}
            }
            avisar(el, msg);
        });
    }

    filtrarConAviso(nombre, SOLO_LETRAS, 'El nombre solo admite letras, espacios, apóstrofes, puntos y guiones.',/^[\s'’.-]+/, 'El nombre debe empezar con una letra.');
    filtrarConAviso(apellido, SOLO_LETRAS, 'El apellido solo admite letras, espacios, apóstrofes, puntos y guiones.',/^[\s'’.-]+/, 'El apellido debe empezar con una letra.');
    filtrarConAviso(username, SOLO_USUARIO, 'El usuario solo admite letras, números, puntos, guiones y guiones bajos, sin espacios.');
    filtrarConAviso(email, /\s/g, 'El email no puede contener espacios.');



    email.addEventListener('blur', () => {
        const v = email.value.trim();
        if (v !== '' && !EMAIL.test(v)) mostrar(email, 'El email no es válido.');
    });

    function actualizarAyudaPassword() {
        const longitud = nueva.value.length;

        ayudaPassword.hidden = false;

         if (longitud === 0 && !obligatorio) {
            ayudaPassword.textContent = 'Opcional. Si la cambiás, debe tener al menos 8 caracteres.';
            ayudaPassword.className = 'form-text text-muted';
        } else if (longitud < 8) {
            ayudaPassword.textContent = 'La contraseña debe tener al menos 8 caracteres.';
            ayudaPassword.className = 'form-text text-danger';
        } else {
            ayudaPassword.textContent = 'Longitud correcta.';
            ayudaPassword.className = 'form-text text-success';
        }
    }

  

    const completo = () => campos.every(el => errorCampo(el) === '');

    const actualizarBoton = () => {
        const bloqueado = !completo();

        boton.classList.toggle('btn-bloqueado', bloqueado);
    };

    actualizarBoton(); 

    campos.forEach(el => el.addEventListener('input', () => {
        mostrar(el, ''); 
        actualizarBoton();
        if (el === nueva) { mostrar(actual, ''); mostrar(confirmacion, '');  }
        if (el === nueva && !ayudaPassword.hidden) {
            actualizarAyudaPassword();
        }
    }));

    

   

    form.addEventListener('submit', function (e) {
        let primero = null;
        [nombre, apellido, username, email].forEach(el => { el.value = el.value.trim(); });

        campos.forEach(el => {
            const msg = errorCampo(el);
            mostrar(el, msg);
            if (msg && !primero) primero = el;
        });

        if (primero) {
            e.preventDefault();
            const destino = primero.closest('.form-group') || primero;
            destino.scrollIntoView({ behavior: 'smooth', block: 'center' });
            primero.focus({ preventScroll: true });
        }
    });


});