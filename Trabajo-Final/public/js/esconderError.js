document.addEventListener('DOMContentLoaded', (event) => {
    const error = document.querySelector('.alert.alert-danger');

    if (error) {
        setTimeout(() => {
            error.style.transition = 'opacity 1s';
            error.style.opacity = '0';
            setTimeout(() => {
                error.remove();
            }, 1000); 
        }, 2000); 
    }
});