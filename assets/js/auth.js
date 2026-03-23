// 🔐 Check login
function checkLogin() {
    const user = localStorage.getItem("user");

    if (!user) {
        return;
    }
}

// 👤 Hiển thị user trên navbar
function renderAuth() {
    const authArea = document.getElementById("auth-area");
    const user = JSON.parse(localStorage.getItem("user"));

    if (!authArea) return;

    if (user) {
        authArea.innerHTML = `
            <span style="font-weight:800;"> ${user.name}</span>
            <button onclick="logout()" 
                style="border: none; background: none; color: var(--color-brand-orange); cursor: pointer; font-weight: 600;">
                Đăng xuất
            </button>
        `;
    } else {
        authArea.innerHTML = `
            <a href="Login.html" class="nav-link" style="font-weight:700;">
                Đăng nhập
            </a>
        `;
    }
}

// 🚪 Logout
function logout() {
    localStorage.removeItem("user");
    window.location.href = "index.html";
}