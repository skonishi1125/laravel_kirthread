document.getElementById('password').addEventListener('input', function () {
    const pw = this.value;
    const error = document.getElementById('passwordError');

    if (pw.length > 0 && pw.length < 8) {
        error.textContent = 'パスワードは8文字以上で入力してください。';
        error.style.display = 'block';
        this.classList.add('is-invalid');
    } else {
        error.textContent = '';
        error.style.display = 'none';
        this.classList.remove('is-invalid');
    }
});
