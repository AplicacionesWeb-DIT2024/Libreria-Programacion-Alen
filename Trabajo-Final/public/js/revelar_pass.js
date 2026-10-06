document.addEventListener("DOMContentLoaded", () => {
    const toggleIcons = document.querySelectorAll("[data-toggle-password]");

    toggleIcons.forEach(icon => {
        icon.addEventListener("click", () => {
            const targetId = icon.getAttribute("data-toggle-password");
            const passwordInput = document.getElementById(targetId);

            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);

            icon.classList.toggle("fa-eye");
            icon.classList.toggle("fa-eye-slash");
        });
    });
});
