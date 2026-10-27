document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('modalEditarCategoria');
    const form = document.getElementById('formEditarCategoria');
    const input = document.getElementById('edit_nombre');
    const feedback = document.getElementById('edit_nombreFeedback');
    const idInput = document.getElementById('edit_categoria_id');
    if (!modalEl || !form || !input) return;

    modalEl.addEventListener('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        if (!btn) return; 
        idInput.value = btn.dataset.id;
        input.classList.remove('is-invalid');
        feedback.textContent = 'El nombre es obligatorio.';
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

    input.addEventListener('input', () => input.classList.remove('is-invalid'));

    if (window.REABRIR_MODAL_EDITAR_CATEGORIA) {
        new bootstrap.Modal(modalEl).show();
    }
});