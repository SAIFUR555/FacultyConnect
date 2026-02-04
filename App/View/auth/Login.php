<?php require_once __DIR__ . '/../common/header.php'; ?>

<!-- Professional login page styles -->
<style>
/* Body and background */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(to right, #6a11cb, #2575fc);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

/* Login container */
.login-container {
    background: #ffffff;
    width: 380px;
    padding: 40px 30px;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    text-align: center;
}

/* Heading */
.login-container h2 {
    color: #333;
    margin-bottom: 25px;
    font-weight: 700;
    font-size: 24px;
}

/* Input fields */
.login-container input[type="text"],
.login-container input[type="password"] {
    width: 100%;
    padding: 12px 15px;
    margin: 10px 0 20px 0;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    box-sizing: border-box;
    transition: border 0.3s;
}

.login-container input[type="text"]:focus,
.login-container input[type="password"]:focus {
    border-color: #3b4e70;
    outline: none;
}

/* Submit button */
.login-container button {
    width: 100%;
    padding: 12px;
    background-color: #2575fc;
    border: none;
    border-radius: 6px;
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s;
}

.login-container button:hover {
    background-color: #1a5ed0;
}

/* Error message */
.error-message {
    color: #d93025;
    background-color: #fdecea;
    border: 1px solid #f5c6cb;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 15px;
    font-size: 14px;
}

/* Register link */
.register-links {
    margin-top: 20px;
    font-size: 14px;
}

.register-links a {
    color: #2575fc;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s;
}

.register-links a:hover {
    color: #1a5ed0;
}
</style>

<!-- stylesheet: use absolute path from htdocs so CSS always loads -->
<link rel="stylesheet" href="/springwtj/FacultyConnect/public/CSS/style.css">

<div class="login-container">
    <h2>FacultyConnect Login</h2>

    <?php if (!empty($data['error'])): ?>
        <p class="error-message"><?php echo $data['error']; ?></p>
    <?php endif; ?>

    <form action="/springwtj/FacultyConnect/App/controllers/LoginController.php" method="POST" id="loginForm" autocomplete="off">
        <input type="text" name="user_id" id="user_id" placeholder="Student ID or Teacher ID" required>
        <input type="password" name="password" id="password" placeholder="Enter password" required>
        <button type="submit">Login</button>
    </form>

    <div class="register-links">
        Don't have an account? Register as: 
        <a href="/springwtj/FacultyConnect/App/View/auth/student_register.php">Student</a> | 
        <a href="/springwtj/FacultyConnect/App/View/auth/teacher_register.php">Teacher</a>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const id = document.getElementById('user_id').value.trim();
    const pass = document.getElementById('password').value.trim();

    if (id === '' || pass === '') {
        alert('Please fill in all fields.');
        e.preventDefault();
    }
});
</script>

<?php require_once __DIR__ . '/../common/footer.php'; ?>
