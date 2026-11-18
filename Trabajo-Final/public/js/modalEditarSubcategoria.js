document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('modalEditarSubcategoria');
    const form = document.getElementById('formEditarSubcategoria');
    const nombre = document.getElementById('edit_nombre_sub');
    const nombreFb = document.getElementById('edit_nombre_subFeedback');
    const ayuda = document.getElementById('edit_nombre_sub-help');
    const categoria = document.getElementById('edit_categoria_sub');
    const categoriaFb = document.getElementById('edit_categoria_subFeedback');
    const idInput = document.getElementById('edit_subcategoria_id');
    const boton = document.getElementById('btnEditarSubcategoria');
    if (!modalEl || !form || !nombre || !categoria || !boton) return;

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

    const completo = () => nombre.value.trim() !== '' && categoria.value !== '';

    function mostrar(el, msg) {
        if (el === nombre) {
            nombreFb.textContent = msg;
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

            avisar(mensaje);
        });
    }

    nombre.addEventListener('input', () => {
        nombreFb.textContent = '';
        nombre.classList.remove('is-invalid');
        if (ayuda.className === guiaClase) 
            ayuda.hidden = false;   
        actualizarBoton();
    });

    $(categoria).on('change', () => {
        categoriaFb.textContent = '';
        actualizarBoton();
    });

    form.addEventListener('submit', function (e) {
        nombre.value = nombre.value.trim();
        let ok = true;
        if (nombre.value === '') {
            mostrar(nombre, 'El nombre es obligatorio.');
            ok = false;
        }
        if (categoria.value === '') {
            categoriaFb.textContent = 'Debe seleccionar una categoría.';
            ok = false;
        }
        if (!ok) {
            e.preventDefault();
            const primero = form.querySelector('.is-invalid');
            if (primero) {
                primero.focus();
            } else if (categoria.value === '') {
                 $(categoria).select2('open'); 
            }      
        }
    });

    modalEl.addEventListener('show.bs.modal', function (e) {
        const origen = e.relatedTarget;
        if (origen) {
            idInput.value = origen.dataset.id;
            mostrar(nombre, '');
            nombreFb.textContent = 'El nombre es obligatorio.';
            restaurarGuia();
            categoria.classList.remove('is-invalid');
            categoriaFb.textContent = '';
        }
        setTimeout(actualizarBoton, 0);
    });

    filtrarConAviso();
    if (nombreFb.textContent.trim() !== '' && nombre.classList.contains('is-invalid')) {
        ayuda.hidden = true;
    }
    actualizarBoton();

    if (window.REABRIR_MODAL_EDITAR_SUBCATEGORIA) {
        new bootstrap.Modal(modalEl).show();
    }
});