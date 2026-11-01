document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('modalAgregarSubcategoria');
    const form = document.getElementById('formAgregarSubcategoria');
    const nombre = document.getElementById('sub_nombre');
    const nombreFb = document.getElementById('sub_nombreFeedback');
    const categoria = document.getElementById('sub_categoria');
    const categoriaFb = document.getElementById('sub_categoriaFeedback');
    const boton = document.getElementById('btnAgregarSubcategoria');
    if (!modalEl || !form || !nombre || !categoria || !boton) return;


    const CARACTERES_NO_PERMITIDOS = /[^\p{L}\p{N}\s'’-]/gu;


    const completo = () =>
        nombre.value.trim() !== '' && categoria.value !== '';

    const actualizarBoton = () => { 
        
        const bloqueado = !completo();
        boton.classList.toggle('btn-bloqueado', bloqueado);
        boton.setAttribute('aria-disabled', bloqueado ? 'true' : 'false');
    
    };

    nombre.addEventListener('input', () => {
        nombre.value = nombre.value.replace(CARACTERES_NO_PERMITIDOS, '');
        nombre.classList.remove('is-invalid');
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
            nombreFb.textContent = 'El nombre es obligatorio.';
            nombre.classList.add('is-invalid');
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
        if (e.relatedTarget) { 
            nombre.value = '';
            $(categoria).val('').trigger('change');
            nombre.classList.remove('is-invalid');
            categoriaFb.textContent = '';
        }
        setTimeout(actualizarBoton, 0);
    });

    actualizarBoton();

    if (window.REABRIR_MODAL_AGREGAR_SUBCATEGORIA) {
        new bootstrap.Modal(modalEl).show();
    }
});