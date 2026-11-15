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

    const completo = () => input.value.trim() !== '';


    function mostrar(el, msg) {
        if (el === input) {
            feedback.textContent = msg;
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
            ayuda.hidden = true;
            clearTimeout(timer);
        }
        setTimeout(actualizarBoton, 0);
    });


    function filtrarConAviso() {
        input.addEventListener('input', () => {
            const limpio = input.value.replace(CARACTERES_NO_PERMITIDOS, '');

            if (limpio === input.value) return;

            let pos = null;

            try {
                pos = input.selectionStart;
            } catch (e) {}

            input.value = limpio;

            if (pos !== null) {
                const nuevaPosicion = Math.max(0, pos - 1);

                try {
                    input.setSelectionRange(nuevaPosicion, nuevaPosicion);
                } catch (e) {}
            }

            ayuda.textContent = 'El nombre solo admite letras, números, espacios, apóstrofes, puntos y guiones.';
            ayuda.hidden = false;

            clearTimeout(timer);

            timer = setTimeout(() => {
                ayuda.hidden = true;
            }, 3000);
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
        mostrar(input, '');
        actualizarBoton();
    });

    filtrarConAviso();


    actualizarBoton();
    if (window.REABRIR_MODAL_EDITAR_CATEGORIA) {
        new bootstrap.Modal(modalEl).show();
    }
});