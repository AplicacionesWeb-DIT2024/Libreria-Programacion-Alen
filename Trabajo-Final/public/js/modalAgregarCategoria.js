document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formAgregarCategoria');
    const input = document.getElementById('nombre');
    const feedback = document.getElementById('nombreFeedback');
    const modalEl = document.getElementById('modalAgregarCategoria');

    if (!form || !input || !modalEl) return;

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

    if (window.REABRIR_MODAL_AGREGAR_CATEGORIA) {
        new bootstrap.Modal(modalEl).show();
    }
});