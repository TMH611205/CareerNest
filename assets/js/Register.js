document.getElementById("register-form").addEventListener("submit", async function(e) {
    e.preventDefault();

    const fullName = document.getElementById("fullname").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm-password").value;

  
    if (!fullName || !email || !password || !confirmPassword) {
        alert("Vui lòng nhập đầy đủ thông tin!");
        return;
    }

    if (password !== confirmPassword) {
        alert("Mật khẩu xác nhận không khớp!");
        return;
    }

    if (password.length < 6) {
        alert("Mật khẩu phải ít nhất 6 ký tự!");
        return;
    }

    try {
        const response = await fetch("http://localhost:9999/CareerNest/CareerNest_Backend/api/register.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                fullName: fullName,
                email: email,
                password: password
            })
        });

        const result = await response.json();

        if (result.message === "Đăng ký thành công") {
            alert("Đăng ký thành công 🎉");

            // chuyển sang login
            window.location.href = "Login.html";
        } else {
            alert(result.message);
        }

    } catch (error) {
        console.error(error);
        alert("Không kết nối được server!");
    }
});