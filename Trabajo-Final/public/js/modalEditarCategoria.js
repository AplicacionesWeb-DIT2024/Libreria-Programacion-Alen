document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('modalEditarCategoria');
    const form = document.getElementById('formEditarCategoria');
    const input = document.getElementById('edit_nombre');
    const feedback = document.getElementById('edit_nombreFeedback');
    const idInput = document.getElementById('edit_categoria_id');
    const botonEditar = document.getElementById('btnEditarCategoria');
    if (!modalEl || !form || !input || !botonEditar) return;

     const actualizarBoton = () => {
        botonEditar.disabled = input.value.trim() === '';
    };

     modalEl.addEventListener('show.bs.modal', function (e) {
        const origen = e.relatedTarget;
        if (origen) {
            idInput.value = origen.dataset.id;
            input.classList.remove('is-invalid');
            feedback.textContent = 'El nombre es obligatorio.';
        }
        setTimeout(actualizarBoton, 0);
    });

    form.addEventListener('submit', function (e) {
        input.value = input.value.trim();
        if (input.value === '') {
            e.preventDefault();
            feedback.textContent = 'El nombre es obligatorio.';
            input.classList.add('is-invalid');
            input.focus();
        }
    });
    
    input.addEventListener('input', () => {
        input.classList.remove('is-invalid');
        actualizarBoton();
    });

    actualizarBoton();
    if (window.REABRIR_MODAL_EDITAR_CATEGORIA) {
        new bootstrap.Modal(modalEl).show();
    }
});