document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('btn-login');
    if (btn) {
        btn.disabled = false;
        btn.querySelector('.spinner-login')?.classList.add('d-none');
        btn.querySelector('.icono-login')?.classList.remove('d-none');
        btn.querySelector('.texto').textContent = "Iniciar Sesión";
    }

    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            const btn = document.getElementById('btn-login');
            
            btn.querySelector('.icono-login').classList.add('d-none');
            btn.querySelector('.spinner-login').classList.remove('d-none');
            btn.querySelector('.texto').textContent = "Iniciando...";
            btn.disabled = true;
           
        });
    }
});