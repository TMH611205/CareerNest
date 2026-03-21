// Initialize Lucide Icons
lucide.createIcons();

// Password Visibility Toggle
const togglePassword = (inputId, iconId) => {
  const passwordInput = document.getElementById(inputId);
  const icon = document.querySelector(`#${iconId}`);
  
  if (passwordInput && icon) {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    
    // Update icon
    const iconName = type === 'password' ? 'eye' : 'eye-off';
    icon.setAttribute('data-lucide', iconName);
    lucide.createIcons();
  }
};

const registerForm = document.getElementById('register-form');
registerForm?.addEventListener('submit', (e) => {
  e.preventDefault();
  const password = document.getElementById('password').value;
  const confirmPassword = document.getElementById('confirm-password').value;

  if (password !== confirmPassword) {
    alert('Mật khẩu xác nhận không khớp!');
    return;
  }

  alert('Đăng ký thành công! (Đây là bản demo)');
  window.location.href = 'Login.html';
});