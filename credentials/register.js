function validatePasswords() {
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirmPassword").value;

    if (password !== confirmPassword) {
        alert("Passwords do not match!");
        return false; 
    }
    return true;
}
function toggleShowPassword() {
    const passwordInput = document.getElementById("password");
    const confirmPasswordInput = document.getElementById("confirmPassword");
    const showPasswordCheckbox = document.getElementById("showPassword");
    const showConfirmPasswordCheckbox = document.getElementById("showConfirmPassword");

    passwordInput.type = showPasswordCheckbox.checked ? 'text' : 'password';
    confirmPasswordInput.type = showConfirmPasswordCheckbox.checked ? 'text' : 'password';
}
