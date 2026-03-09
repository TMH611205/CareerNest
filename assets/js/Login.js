document.getElementById("loginForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const user = document.getElementById("username").value.trim();
    const pass = document.getElementById("password").value.trim();
    const errorDiv = document.getElementById("error");

    if (user === "" || pass === "") {
        errorDiv.textContent = "Vui lòng nhập đầy đủ thông tin!";
        return;
    }

    if (user === "admin" && pass === "123") {
        alert("Đăng nhập thành công!");
        window.location.href = "index.html";

    } else if (user === "admin@123" && pass === "123") {
        alert("Đăng nhập Admin thành công!");
        window.location.href = "Admin.html";

    } else {
        errorDiv.textContent = "Sai tài khoản hoặc mật khẩu!";
    }
});