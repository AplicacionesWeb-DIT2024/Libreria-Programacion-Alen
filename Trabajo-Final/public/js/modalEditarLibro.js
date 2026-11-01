document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('modalEditarLibro');
    const form = document.getElementById('formEditarLibro');
    const boton = document.getElementById('btnEditarLibro');
    if (!modalEl || !form || !boton) return;

    const idInput = document.getElementById('editar_libro_id');
    const nombre = form.querySelector('#editar_nombre');

    const TIPOS = ['image/jpeg', 'image/png', 'image/webp'];
    const EXTENSIONES = /\.(jpe?g|png|webp)$/i;
    const MAX_MB = 5;
    const NO_PERMITIDOS = /[^\p{L}\p{N}\s'’.,:;!?¡¿-]/gu;
    const urlOriginal = {};  

    nombre.addEventListener('input', () => {
        nombre.value = nombre.value.replace(NO_PERMITIDOS, '');
    });

    form.querySelectorAll('input[type="number"]').forEach(el => {
        const soloEnteros = el.step !== '0.01';
        el.addEventListener('keydown', e => {
            if (['-', '+', 'e', 'E'].includes(e.key)) e.preventDefault();
            if (soloEnteros && (e.key === '.' || e.key === ',')) e.preventDefault();
        });
        el.addEventListener('input', () => {
            if (el.min !== '' && Number(el.min) >= 0 && el.value.startsWith('-')) {
                el.value = el.value.replace('-', '');
            }
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
        .filter(el => !['_token', '_method', 'libro_id'].includes(el.name) && el.type !== 'checkbox');

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
        if (file.size > MAX_MB * 1024 * 1024) return `La imagen no puede superar los ${MAX_MB} MB.`;
        return '';
    }

    function errorCampo(el) {
        if (el.type === 'file') return errorImagen(el);   
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

    function restaurarVistaPrevia(el) {
        const $img = $(el).closest('.form-group').find('img[data-preview]');
        const clave = $img.data('preview');
        const url = urlOriginal[clave];
        if (url) $img.attr('src', url).show();
        else $img.removeAttr('src').hide();
    }

    $(form).on('input change', 'input, select', function () {
        const el = this;
        if (el.type === 'checkbox') return;
        if (el.type === 'file') {
            const msg = errorImagen(el);
            if (msg) {
                el.value = '';
                setTimeout(() => restaurarVistaPrevia(el), 0);   
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
        const origen = e.relatedTarget;
        if (origen) {  
            idInput.value = origen.dataset.id;
            campos().forEach(el => mostrar(el, ''));
            form.querySelectorAll('img[data-preview]').forEach(img => {
                urlOriginal[img.dataset.preview] = origen.dataset[img.dataset.preview] || '';
            });
            setTimeout(() => {
                $('#editar_categoria').val(origen.dataset.categoria).trigger('change');   
                $('#editar_subcategoria').val(origen.dataset.subcategoria).trigger('change');
                actualizarBoton();
            }, 0);
        }
        setTimeout(actualizarBoton, 0);
    });

    actualizarBoton();

    if (window.REABRIR_MODAL_EDITAR_LIBRO) {
        const $cat = $(form).find('select[name="categoria"]');
        if ($cat.val()) {
            $cat.trigger('change');   
            $(form).find('select[name="subcategoria"]')
                .val(window.OLD_SUBCATEGORIA_EDITAR || '')
                .trigger('change');
        }
        new bootstrap.Modal(modalEl).show();
    }
});