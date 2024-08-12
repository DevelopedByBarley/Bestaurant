const changePwCheckboxes = document.querySelectorAll('.changePasswordCheckbox');

changePwCheckboxes.forEach(changePwCheck => {
    changePwCheck.addEventListener('change', function (e) {
        console.log(e.target);
        const passwordFields = ['prev_password', 'password', 'repeat'];
        // Eltávolítva az extra pontosvesszőt a parent definícióból
        const parent = e.target.closest('form'); // Ezzel közvetlenül a form szülő elemet találjuk meg
        passwordFields.forEach(id => {
            const field = parent.querySelector(`#${id}`);
            if (field) {
                if (this.checked) {
                    field.disabled = false;
                    field.setAttribute('required', 'required');
                } else {
                    field.disabled = true;
                    field.removeAttribute('required');
                }
            }

        });
    });
});
