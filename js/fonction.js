var password = document.getElementById('fakePassword');
var toggler = document.getElementById('toggler');
showHidePassword = () => {
    if (password.type == 'password') {
        password.setAttribute('type', 'text');
        toggler.classList.add('fa-eye-slash');
    } else {
        toggler.classList.remove('fa-eye-slash');
        password.setAttribute('type', 'password');
    }
};
toggler.addEventListener('click', showHidePassword);

const icone = document.getElementById("toggler");
const passwordField = document.getElementById("fakePassword");

icone.addEventListener("click", function() {
    if (icone.classList.contains("fa-eye-slash")) {
        icone.classList.remove("fa-eye-slash");
        icone.classList.add("fa-eye");
        passwordField.type = "text";
    } else {
        icone.classList.remove("fa-eye");
        icone.classList.add("fa-eye-slash");
        passwordField.type = "password";
    }
});