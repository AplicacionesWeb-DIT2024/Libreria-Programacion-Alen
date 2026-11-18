
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formAgregarCategoria');
    const input = document.getElementById('nombre');
    const feedback = document.getElementById('nombreFeedback');
    const ayuda = document.getElementById('nombre-help');
    const modalEl = document.getElementById('modalAgregarCategoria');
    const boton = document.getElementById('btnAgregarCategoria');

    if (!form || !input || !modalEl || !boton) return;

    const CARACTERES_NO_PERMITIDOS = /[^\p{L}\p{N}\s'’-]/gu;

    let timer;
    const guiaTexto = ayuda.textContent.trim();
    const guiaClase = ayuda.className;


    function avisar(mensaje) {
        ayuda.textContent = mensaje;
        ayuda.className = 'form-text text-danger';
        ayuda.hidden = false;

        clearTimeout(timer);
        timer = setTimeout(restaurarGuia, 3000);
    }

    function restaurarGuia() {
        clearTimeout(timer);
        ayuda.textContent = guiaTexto;
        ayuda.className = guiaClase;
        ayuda.hidden = false;
    }

    const completo = () => input.value.trim() !== '';

    function mostrar(el, msg) {
            if (el === input) {
                feedback.textContent = msg;
                if (msg !== '') {
                    clearTimeout(timer);
                    ayuda.hidden = true;        
                } else {
                    restaurarGuia();            
                }
            }

        el.classList.toggle('is-invalid', msg !== '');
    }

    function actualizarBoton() {
        const bloqueado = !completo();

        boton.classList.toggle('btn-bloqueado', bloqueado);
        boton.setAttribute('aria-disabled', bloqueado ? 'true' : 'false');
    }

    function filtrarConAviso() {
        input.addEventListener('input', () => {
            let limpio = input.value.replace(CARACTERES_NO_PERMITIDOS, '');
            let mensaje = 'El nombre solo admite letras, números, espacios, apóstrofes y guiones.';

            const sinSimbolosInicio = limpio.replace(/^[\s'’-]+/, '');
            const inicioInvalido = sinSimbolosInicio !== limpio;
            if (inicioInvalido) {
                limpio = sinSimbolosInicio;
                mensaje = 'El nombre debe empezar con una letra o un número.';
            }

            if (limpio === input.value) return;

            let pos = null;

            try {
                pos = input.selectionStart;
            } catch (e) {}

            const quitados = input.value.length - limpio.length;
            input.value = limpio;

            if (pos !== null) {
                const nuevaPosicion = Math.max(0, pos - quitados);

                try {
                    input.setSelectionRange(nuevaPosicion, nuevaPosicion);
                } catch (e) {}
            }

            avisar(mensaje);
        });
    }

    input.addEventListener('input', () => {
        feedback.textContent = '';
        input.classList.remove('is-invalid');
        if (ayuda.className === guiaClase) 
            ayuda.hidden = false;   
        actualizarBoton();
    });

    form.addEventListener('submit', function (e) {
        input.value = input.value.trim();

        if (input.value === '') {
            e.preventDefault();

            mostrar(input, 'El nombre es obligatorio.');

            input.focus();
        }
    });

    modalEl.addEventListener('show.bs.modal', function (e) {
        if (e.relatedTarget) {
            input.value = '';
            mostrar(input, '');
            restaurarGuia();
        }

        actualizarBoton();
    });

    filtrarConAviso();

    if (feedback.textContent.trim() !== '' && input.classList.contains('is-invalid')) {
        ayuda.hidden = true;
    }

    actualizarBoton();

    if (window.REABRIR_MODAL_AGREGAR_CATEGORIA) {
        new bootstrap.Modal(modalEl).show();
    }
});
