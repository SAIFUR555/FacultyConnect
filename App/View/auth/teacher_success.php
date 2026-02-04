<?php require_once __DIR__ . '/../common/header.php'; ?>

<style>
body {
    font-family: 'Inter', sans-serif;
    background: linear-gradient(135deg, #43e97b, #38f9d7);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    color: white;
    text-align: center;
}

.success-container {
    background: rgba(255, 255, 255, 0.1);
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.success-container h1 {
    font-size: 32px;
    margin-bottom: 15px;
}

.success-container p {
    font-size: 18px;
    margin-bottom: 20px;
}

.success-container a {
    color: #fff;
    text-decoration: none;
    font-weight: 600;
    background: #4361ee;
    padding: 10px 20px;
    border-radius: 6px;
    transition: background 0.3s;
}

.success-container a:hover {
    background: #3a56d4;
}
</style>

<div class="success-container">
    <h1>Registration Successful!</h1>
    <p>Thank you for registering as a teacher. You can now log in to your account.</p>
    <a href="/springwtj/FacultyConnect/App/View/auth/login.php">Go to Login</a>
</div>

<?php require_once __DIR__ . '/../common/footer.php'; ?>
