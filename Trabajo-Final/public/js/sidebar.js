    const navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            const subMenu = this.nextElementSibling;
            if (subMenu) {
                e.preventDefault(); // Evitar el comportamiento predeterminado del enlace
                subMenu.style.display = subMenu.style.display === 'block' ? 'none' : 'block'; // Alternar la visualización del submenú
            }
        });
    });