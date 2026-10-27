document.addEventListener('DOMContentLoaded', function () {
    var confirmDeleteButtons = document.querySelectorAll('[id^="confirmDeleteButton"]');

    confirmDeleteButtons.forEach(function(button) {
        button.addEventListener('click', function () {
            var modalId = this.id.replace('confirmDeleteButton', '');
            var deleteForm = document.getElementById('deleteForm' + modalId);
            deleteForm.submit();
        });
    });
});