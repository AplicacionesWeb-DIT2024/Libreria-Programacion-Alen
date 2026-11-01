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


    nombre.addEventListener('input', () => {
        nombre.value = nombre.value.replace(NO_PERMITIDOS, '');
    });


    form.querySelectorAll('input[type="number"]').forEach(el => {
        const soloEnteros = el.step !== '0.01';   
        el.addEventListener('keydown', e => {
            if (['-', '+', 'e', 'E'].includes(e.key)) e.preventDefault();
            if (soloEnteros && (e.key === '.' || e.key === ',')) e.preventDefault();
        });

        el.addEventListener('blur', () => {
            if (el.value === '') return;
            let n = Number(el.value);
            if (el.min !== '' && n < Number(el.min)) n = Number(el.min);
            if (el.max !== '' && n > Number(el.max)) n = Number(el.max);
            el.value = n;
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
    }

    function errorImagen(el) {
        const file = el.files[0];
        if (!file) return '';
        if (!TIPOS.includes(file.type) || !EXTENSIONES.test(file.name)) {
            return 'Solo se permiten imágenes JPG, PNG o WEBP.';
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
            }
            mostrar(el, msg);
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