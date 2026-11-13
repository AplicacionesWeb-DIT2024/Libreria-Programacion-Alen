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

    const SOLO_LETRAS = /[^\p{L}\s'’.-]/gu;
    const SOLO_USUARIO = /[^A-Za-z0-9._-]/g;
    const EMAIL = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

    [nombre, apellido].forEach(el => el.addEventListener('input', () => { el.value = el.value.replace(SOLO_LETRAS, ''); }));
    username.addEventListener('input', () => { username.value = username.value.replace(SOLO_USUARIO, ''); });
    email.addEventListener('input', () => { email.value = email.value.replace(/\s/g, ''); });

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

        if (el === nueva) {
            if (nueva.value !== '' && nueva.value.length < 8) return 'La nueva contraseña debe tener al menos 8 caracteres.';
            return '';
        }
        if (el === actual) {
            if (nueva.value !== '' && actual.value === '') return 'Debe ingresar su contraseña actual para cambiarla.';
            return '';
        }
        if (el === confirmacion) {
            if (nueva.value !== '' && confirmacion.value !== nueva.value) return 'La confirmación no coincide.';
            return '';
        }
        return '';
    }

    const completo = () => campos.every(el => errorCampo(el) === '');

    const actualizarBoton = () => {
        const bloqueado = !completo();

        boton.classList.toggle('btn-bloqueado', bloqueado);
    };


    campos.forEach(el => el.addEventListener('input', () => {
        mostrar(el, ''); 
        actualizarBoton();
        if (el === nueva) { mostrar(actual, ''); mostrar(confirmacion, '');  }
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