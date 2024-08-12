const pwGenerators = document.querySelectorAll('.pw-generator');

pwGenerators.forEach(generator => {
    if (generator) {
        generator.addEventListener('click', (e) => {
            e.preventDefault();
            const newPw = generatePassword();
            const password = e.target.parentElement.querySelector('.password');

            if (newPw && newPw !== '') {
                password.value = newPw;
            }

        })
    }
})
    
function generatePassword(length = 12) {
    const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%";
    let password = "";
    for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * charset.length);
        password += charset[randomIndex];
    }
    return password;
}
