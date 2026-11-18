document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('modalEditarCategoria');
    const form = document.getElementById('formEditarCategoria');
    const input = document.getElementById('edit_nombre');
    const ayuda = document.getElementById('nombre-edicion-help');
    const feedback = document.getElementById('edit_nombreFeedback');
    const idInput = document.getElementById('edit_categoria_id');
    const botonEditar = document.getElementById('btnEditarCategoria');
    if (!modalEl || !form || !input || !botonEditar) return;

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

        botonEditar.classList.toggle('btn-bloqueado', bloqueado);
        botonEditar.setAttribute('aria-disabled', bloqueado ? 'true' : 'false');
    }

     modalEl.addEventListener('show.bs.modal', function (e) {
        const origen = e.relatedTarget;
        if (origen) {
            idInput.value = origen.dataset.id;
            input.classList.remove('is-invalid');
            feedback.textContent = 'El nombre es obligatorio.';
            restaurarGuia();

        }
        setTimeout(actualizarBoton, 0);
    });


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


    form.addEventListener('submit', function (e) {
        input.value = input.value.trim();
        if (input.value === '') {
            e.preventDefault();
            mostrar(input, 'El nombre es obligatorio.');
            input.focus();
        }
    });
    
    input.addEventListener('input', () => {
        feedback.textContent = '';
        input.classList.remove('is-invalid');
        if (ayuda.className === guiaClase) 
            ayuda.hidden = false;   
        actualizarBoton();
    });

    filtrarConAviso();

    if (feedback.textContent.trim() !== '' && input.classList.contains('is-invalid')) {
        ayuda.hidden = true;
    }


    actualizarBoton();
    if (window.REABRIR_MODAL_EDITAR_CATEGORIA) {
        new bootstrap.Modal(modalEl).show();
    }
});