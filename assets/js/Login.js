function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    const type = input.getAttribute("type") === "password" ? "text" : "password";
    input.setAttribute("type", type);

    icon.classList.toggle("bi-eye-fill");
    icon.classList.toggle("bi-eye-slash-fill");
}

document.getElementById("loginForm").addEventListener("submit", async function (e) {
    e.preventDefault();

    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();

    if (!email || !password) {
        document.getElementById("error").innerText = "Vui lòng nhập đầy đủ thông tin!";
        return;
    }

    try {
        const response = await fetch("http://localhost:9999/CareerNest/CareerNest_Backend/api/login.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                email: email,
                password: password
            })
        });

        const result = await response.json();

        if (result.message === "Đăng nhập thành công") {

            const userData = {
                UserID: result.user.id,
                name: result.user.name,
                email: result.user.email,
                role: result.user.role
            };

            localStorage.setItem("user", JSON.stringify(userData));

       
            fetch('http://localhost:9999/CareerNest/CareerNest_Backend/api/update_active.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    UserID: userData.UserID
                })
            });

            const role = result.user.role;

            if (role === "Student") {
                window.location.replace("index.html");
            } else if (role === "Admin" || role === "Employer") {
                window.location.replace("Admin.html");
            } else {
                window.location.replace("index.html");
            }
        } else {
            document.getElementById("error").innerText = result.message;
        }

    } catch (error) {
        console.error(error);
        document.getElementById("error").innerText = "Lỗi kết nối server!";
    }
});