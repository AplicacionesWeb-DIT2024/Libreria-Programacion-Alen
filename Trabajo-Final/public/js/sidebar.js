    const navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            const subMenu = this.nextElementSibling;
            if (subMenu) {
                e.preventDefault(); 
                subMenu.style.display = subMenu.style.display === 'block' ? 'none' : 'block'; 
            }
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('.nav-link');
    
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navLinks.forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
            });
        });


    });