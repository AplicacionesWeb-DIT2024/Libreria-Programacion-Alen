document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('modalEditarEditorial');
    const form = document.getElementById('formModificarEditorial');
    const nombre = document.getElementById('edit_nombre_edi');
    const nombreFb = document.getElementById('edit_nombre_ediFeedback');
    const ayuda = document.getElementById('nombre-edicion-help');
    const pais = document.getElementById('edit_pais');
    const paisFb = document.getElementById('edit_paisFeedback');
    const idInput = document.getElementById('edit_editorial_id');
    const boton = document.getElementById('btnModificarEditorial');
    if (!modalEl || !form || !nombre || !pais || !boton) return;

    const CARACTERES_NO_PERMITIDOS = /[^\p{L}\p{N}\s'’-]/gu;

    let timer;

    const completo = () => nombre.value.trim() !== '' && pais.value !== '';


    function mostrar(el, msg) {
        if (el === nombre) {
            nombreFb.textContent = msg;
        }

        el.classList.toggle('is-invalid', msg !== '');
    }

    function actualizarBoton() {
        const bloqueado = !completo();

        boton.classList.toggle('btn-bloqueado', bloqueado);
        boton.setAttribute('aria-disabled', bloqueado ? 'true' : 'false');
    }

    

    function filtrarConAviso() {
        nombre.addEventListener('input', () => {
            let limpio = nombre.value.replace(CARACTERES_NO_PERMITIDOS, '');
            let mensaje = 'El nombre solo admite letras, números, espacios, apóstrofes y guiones.';

            const sinSimbolosInicio = limpio.replace(/^[\s'’-]+/, '');
            const inicioInvalido = sinSimbolosInicio !== limpio;
            if (inicioInvalido) {
                limpio = sinSimbolosInicio;
                mensaje = 'El nombre debe empezar con una letra o un número.';
            }

            if (limpio === nombre.value) return;

            let pos = null;

            try {
                pos = nombre.selectionStart;
            } catch (e) {}

            const quitados = nombre.value.length - limpio.length;
            nombre.value = limpio;

            if (pos !== null) {
                const nuevaPosicion = Math.max(0, pos - quitados);

                try {
                    nombre.setSelectionRange(nuevaPosicion, nuevaPosicion);
                } catch (e) {}
            }

            ayuda.textContent = mensaje;
            ayuda.hidden = false;


            clearTimeout(timer);

            timer = setTimeout(() => {
                ayuda.hidden = true;
            }, 3000);
        });
    }

    nombre.addEventListener('input', () => {
        mostrar(nombre, '');
        actualizarBoton();
    });

    $(pais).on('change', () => {
        paisFb.textContent = '';
        pais.classList.remove('is-invalid');
        actualizarBoton();
    });

    form.addEventListener('submit', function (e) {
        nombre.value = nombre.value.trim();
        let ok = true;
        if (nombre.value === '') {
            mostrar(nombre, 'El nombre es obligatorio.');
            ok = false;
        }
        if (pais.value === '') {
            paisFb.textContent = 'Debe seleccionar un país.';
            pais.classList.add('is-invalid');
            ok = false;
        }
        if (!ok) {
            e.preventDefault();
            const primero = form.querySelector('.is-invalid');
            if (primero) {
                primero.focus();
            } else if (pais.value === '') {
                 $(pais).select2('open'); 
            }      
        }
    });

    modalEl.addEventListener('show.bs.modal', function (e) {
        const origen = e.relatedTarget;
        if (origen) {
            idInput.value = origen.dataset.id;
            mostrar(nombre, '');
            nombreFb.textContent = 'El nombre es obligatorio.';
            ayuda.hidden = true;
            clearTimeout(timer);
            pais.classList.remove('is-invalid');
            paisFb.textContent = '';
        }
        setTimeout(actualizarBoton, 0); 
    });

    filtrarConAviso();
    actualizarBoton();

    if (window.REABRIR_MODAL_MODIFICAR_EDITORIAL) {
        new bootstrap.Modal(modalEl).show();
    }
});