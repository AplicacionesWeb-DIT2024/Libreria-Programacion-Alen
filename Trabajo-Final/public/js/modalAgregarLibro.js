document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('modalAgregarLibro');
    const form = document.getElementById('formAgregarLibro');
    const boton = document.getElementById('btnAgregarLibro');


    if (!modalEl || !form || !boton) return;

    const nombre = form.querySelector('#nombre');


    const TIPOS = ['image/jpeg', 'image/png', 'image/webp'];
    const EXTENSIONES = /\.(jpe?g|png|webp)$/i;
    const MAX_MB = 5;
    const NO_PERMITIDOS = /[^\p{L}\p{N}\s'’.,:;!?¡¿-]/gu;

    const timers = {};


    function avisar(el, mensaje) {
        const ayuda = form.querySelector(`#${el.id}-help`);
        if (!ayuda) return;

        if (ayuda.dataset.base === undefined) {
            ayuda.dataset.base = ayuda.textContent;
            ayuda.dataset.claseBase = ayuda.className;
        }

        ayuda.textContent = mensaje;
        ayuda.className = 'form-text text-danger';
        ayuda.hidden = false;

        clearTimeout(timers[el.id]);
        timers[el.id] = setTimeout(() => {
            ayuda.textContent = ayuda.dataset.base;
            ayuda.className = ayuda.dataset.claseBase;
            ayuda.hidden = false;
        }, 4000);
    }

    nombre.addEventListener('input', () => {
        let limpio = nombre.value.replace(NO_PERMITIDOS, '');
        let mensaje = "El nombre solo admite letras, números, espacios y los signos ' . , : ; ! ? ¡ ¿ -.";

        const sinInicio = limpio.replace(/^[\s'’.,:;!?-]+/, '');
        if (sinInicio !== limpio) {
            limpio = sinInicio;
            mensaje = 'El nombre debe empezar con una letra, un número, ¡ o ¿.';
        }

        if (limpio === nombre.value) return;
        nombre.value = limpio;
        avisar(nombre, mensaje);
    });


    form.querySelectorAll('input[type="number"]').forEach(el => {
        const soloEnteros = el.step !== '0.01';

        el.addEventListener('keydown', e => {
            if (['-', '+', 'e', 'E'].includes(e.key)) {
                e.preventDefault();
                avisar(el, e.key === '-'
                    ? 'No se permiten valores negativos.'
                    : 'Solo se permiten dígitos.');
            } else if (soloEnteros && (e.key === '.' || e.key === ',')) {
                e.preventDefault();
                avisar(el, 'Este campo solo admite números enteros, sin decimales.');
            }
        });

        el.addEventListener('blur', () => {
            if (el.value === '') return;
            let n = Number(el.value);
            const original = n;

            if (el.min !== '' && n < Number(el.min)) n = Number(el.min);
            if (el.max !== '' && n > Number(el.max)) n = Number(el.max);

            if (n !== original) {
                el.value = n;
                avisar(el, original > n
                    ? `El valor máximo permitido es ${el.max}. Se ajustó automáticamente.`
                    : `El valor mínimo permitido es ${el.min}. Se ajustó automáticamente.`);
            }
            actualizarBoton();
        });
    });

    const campos = () => Array.from(form.querySelectorAll('input[name], select[name]'))
        .filter(el => el.name !== '_token');

    const feedback = el => form.querySelector(`[data-fb="${el.name}"]`);

    function mostrar(el, msg) {
        const fb = feedback(el);
        if (fb) fb.textContent = msg;
        if (el.tagName !== 'SELECT') el.classList.toggle('is-invalid', msg !== '');

        const ayuda = form.querySelector(`#${el.id}-help`);
        if (ayuda && ayuda.dataset.base === undefined) {
            ayuda.dataset.base = ayuda.textContent;
            ayuda.dataset.claseBase = ayuda.className;
        }
        if (ayuda) ayuda.hidden = msg !== '';
    }

    function errorImagen(el) {
        const file = el.files[0];
        if (!file) return '';
        if (!TIPOS.includes(file.type) || !EXTENSIONES.test(file.name)) {
            return `"${file.name}" no es válido. Solo se permiten imágenes JPG, PNG o WEBP.`;
        }
        if (file.size > MAX_MB * 1024 * 1024) {
            return `La imagen no puede superar los ${MAX_MB} MB.`;
        }
        return '';
    }

    function errorCampo(el) {
        if (el.type === 'file') {
            if (el.files.length === 0) return el.required ? 'Debe seleccionar una imagen.' : '';
            return errorImagen(el);
        }
        if (el.value.trim() === '') return el.required ? 'Este campo es obligatorio.' : '';
        if (el.type === 'number') {
            const n = Number(el.value);
            if (el.min !== '' && n < Number(el.min)) return `El valor mínimo es ${el.min}.`;
            if (el.max !== '' && n > Number(el.max)) return `El valor máximo es ${el.max}.`;
        }
        return '';
    }

    const completo = () => campos().every(el => errorCampo(el) === '');

    const actualizarBoton = () => {
        const bloqueado = !completo();
        boton.classList.toggle('btn-bloqueado', bloqueado);
        boton.setAttribute('aria-disabled', bloqueado ? 'true' : 'false');
    };

    function ocultarVistaPrevia(el) {
        const $img = $(el).closest('.form-group').find('img.preview-nueva');
        const url = $img.data('objectUrl');
        if (url) URL.revokeObjectURL(url);
        $img.removeData('objectUrl').removeAttr('src').hide();
    }

    $(form).on('input change', 'input, select', function () {
        const el = this;
        if (el.type === 'file') {
            const msg = errorImagen(el);
            if (msg) {
                el.value = '';                                  
                setTimeout(() => ocultarVistaPrevia(el), 0);    
                avisar(el, msg);
            }
            mostrar(el, '');
        } else {
            mostrar(el, '');
            if (el.name === 'categoria') {
                mostrar(form.querySelector('[name="subcategoria"]'), '');
            }
        }
        actualizarBoton();
    });

    form.addEventListener('submit', function (e) {
        let primero = null;
        campos().forEach(el => {
            const msg = errorCampo(el);
            mostrar(el, msg);
            if (msg && !primero) primero = el;
        });
        if (primero) {
            e.preventDefault();
            const destino = primero.closest('.form-group') || primero;
            destino.scrollIntoView({ behavior: 'smooth', block: 'center' });
            if (primero.tagName !== 'SELECT' && !primero.disabled) {
                primero.focus({ preventScroll: true });
            }
        }
    });

    modalEl.addEventListener('show.bs.modal', function (e) {
        if (e.relatedTarget) {                      
            form.reset();
            $(form).find('select').val('').trigger('change');
            campos().forEach(el => { mostrar(el, ''); ocultarVistaPrevia(el); });
        }
        setTimeout(actualizarBoton, 0);
    });

    actualizarBoton();

    if (window.REABRIR_MODAL_AGREGAR_LIBRO) {
        const $cat = $(form).find('select[name="categoria"]');
        if ($cat.val()) {
            $cat.trigger('change');                         
            $(form).find('select[name="subcategoria"]')
                .val(window.OLD_SUBCATEGORIA || '')
                .trigger('change');                         
        }
        new bootstrap.Modal(modalEl).show();
    }
});