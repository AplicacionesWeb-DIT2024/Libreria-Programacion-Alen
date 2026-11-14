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


    const campos = [nombre, apellido, username, email, actual, nueva, confirmacion];
    const feedback = el => form.querySelector(`[data-fb="${el.name}"]`);

    function mostrar(el, msg) {
        const fb = feedback(el);
        if (fb) fb.textContent = msg;
        el.classList.toggle('is-invalid', msg !== '');
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


    function filtrarConAviso(el, regex, mensaje) {
        const ayuda = form.querySelector(`#${el.id}-help`);
        let timer;

        el.addEventListener('input', () => {
            const limpio = el.value.replace(regex, '');
            if (limpio === el.value) return;

            let pos = null;
            try { pos = el.selectionStart; } catch (e) {}

            el.value = limpio;

            if (pos !== null) {
                const nueva = pos - 1 < 0 ? 0 : pos - 1;
                try { el.setSelectionRange(nueva, nueva); } catch (e) {}
            }

            if (!ayuda) return;
            ayuda.textContent = mensaje;
            ayuda.hidden = false;
            clearTimeout(timer);
            timer = setTimeout(() => { ayuda.hidden = true; }, 3000);
        });
    }


    filtrarConAviso(nombre, SOLO_LETRAS, 'El nombre solo admite letras, espacios, apóstrofes, puntos y guiones.');
    filtrarConAviso(apellido, SOLO_LETRAS, 'El apellido solo admite letras, espacios, apóstrofes, puntos y guiones.');
    filtrarConAviso(username, SOLO_USUARIO, 'El usuario solo admite letras, números, puntos, guiones y guiones bajos.');
    filtrarConAviso(email, /\s/g, 'El email no puede contener espacios.');




    email.addEventListener('blur', () => {
        const v = email.value.trim();
        if (v !== '' && !EMAIL.test(v)) mostrar(email, 'El email no es válido.');
    });

    function actualizarAyudaPassword() {
        const longitud = nueva.value.length;

        ayudaPassword.hidden = false;

        if (longitud < 8) {
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