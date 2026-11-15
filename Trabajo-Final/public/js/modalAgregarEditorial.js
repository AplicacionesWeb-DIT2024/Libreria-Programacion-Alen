document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('modalAgregarEditorial');
    const form = document.getElementById('formAgregarEditorial');
    const nombre = document.getElementById('nombre_edi');
    const ayuda = document.getElementById('nombre-help');
    const nombreFb = document.getElementById('edi_nombreFeedback');
    const pais = document.getElementById('pais_edi');
    const paisFb = document.getElementById('edi_paisFeedback');
    const boton = document.getElementById('btnAgregarEditorial');
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
        if (e.relatedTarget) {
            nombre.value = '';
            mostrar(nombre, '');
            ayuda.hidden = true;
            clearTimeout(timer);
            $(pais).val('').trigger('change');
            pais.classList.remove('is-invalid');
            paisFb.textContent = '';
        }
        setTimeout(actualizarBoton, 0);
    });

    filtrarConAviso();
    actualizarBoton();

    if (window.REABRIR_MODAL_AGREGAR_EDITORIAL) {
        new bootstrap.Modal(modalEl).show();
    }
});