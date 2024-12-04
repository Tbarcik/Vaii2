document.addEventListener('DOMContentLoaded', function() {
    // Získanie všetkých tlačidiel na upgradovanie
    const promoteButtons = document.querySelectorAll('.promote-btn');

    promoteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const username = row.querySelector('td:first-child').textContent;
            const userEmail = row.querySelector('td:nth-child(2)').textContent;

            // Potvrdenie pred upgrade
            if (confirm(`Naozaj chcete povýšiť ${username} (${userEmail}) na administrátora?`)) {
                // Po kliknutí na tlačidlo sa používateľ stane adminom
                row.querySelector('td:nth-child(3)').textContent = 'Admin';
                this.setAttribute('disabled', 'true');
                this.textContent = 'Je admin';
            }
        });
    });
});
