
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    const type = input.getAttribute("type") === "password" ? "text" : "password";
    input.setAttribute("type", type);
    icon.classList.toggle("bi-eye-fill");
    icon.classList.toggle("bi-eye-slash-fill");
}


document.getElementById("loginForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

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
            alert("Đăng nhập thành công 🔥");

            // lưu user
            localStorage.setItem("user", JSON.stringify(result.user));

            // chuyển trang
            window.location.href = "index.html";
        } else {
            document.getElementById("error").innerText = result.message;
        }

    } catch (error) {
        document.getElementById("error").innerText = "Lỗi kết nối server!";
    }
    
});