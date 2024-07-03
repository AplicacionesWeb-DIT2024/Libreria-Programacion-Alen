

document.addEventListener('DOMContentLoaded', (event) => {
    const checkRevelarPass = document.querySelector('#revealPassword');
    const checkConfirmed = document.querySelector('#revealPassword2');
    const password = document.querySelector('#password');
    const password2 = document.querySelector('#password_confirmation')

    checkRevelarPass.addEventListener('change', (event) => {
        if (event.target.checked) {
            password.type = 'text';
        } else {
            password.type = 'password';
        }
    });

    checkConfirmed.addEventListener('change', (event) => {
        if (event.target.checked) {
            password2.type = 'text';
        } else {
            password2.type = 'password';
        }
    })
});