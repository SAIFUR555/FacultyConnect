<?php require_once __DIR__ . '/../common/header.php'; ?>

<style>
:root {
    --primary: #4361ee;
    --primary-dark: #3a56d4;
    --secondary: #7209b7;
    --light: #f8f9fa;
    --dark: #212529;
    --gray: #6c757d;
    --light-gray: #e9ecef;
    --success: #4cc9f0;
    --danger: #f72585;
    --border-radius: 12px;
    --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    --transition: all 0.3s ease;
}

* { margin:0; padding:0; box-sizing:border-box; }

body {
    font-family: 'Inter', sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    padding:20px;
}

.register-wrapper {
    display:flex;
    width:100%;
    max-width:900px;
    border-radius:var(--border-radius);
    box-shadow:var(--shadow);
    overflow:hidden;
    min-height:650px;
    background:white;
}

/* Left Branding */
.register-left {
    flex:1;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color:white;
    padding:50px 40px;
    display:flex;
    flex-direction:column;
    justify-content:center;
}

.brand-logo { font-size:28px; font-weight:700; margin-bottom:20px; display:flex; gap:10px; align-items:center; }
.brand-logo i { font-size:32px; }

.brand-title { font-size:24px; font-weight:600; margin-bottom:15px; }
.brand-subtitle { font-size:16px; opacity:0.9; margin-bottom:30px; }

.features-list { list-style:none; margin-top:30px; padding-left:0; }
.features-list li { display:flex; align-items:center; margin-bottom:15px; font-size:15px; }
.features-list li i { margin-right:10px; color:var(--success); }

.login-link { margin-top:40px; text-align:center; font-size:14px; }
.login-link a { color:white; font-weight:600; text-decoration:none; opacity:0.9; transition:var(--transition); }
.login-link a:hover { text-decoration:underline; opacity:1; }

/* Right Form */
.register-right {
    flex:1.2;
    padding:50px 40px;
    overflow-y:auto;
    max-height:650px;
}

.form-header { text-align:center; margin-bottom:30px; }
.form-header h1 { font-size:28px; font-weight:700; color:var(--dark); margin-bottom:8px; }
.form-header p { color:var(--gray); font-size:15px; }

.form-grid {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
    margin-bottom:25px;
}

.form-group { margin-bottom:20px; position:relative; }
.form-group.full-width { grid-column:1/-1; }

.form-group label { display:block; margin-bottom:8px; font-weight:600; font-size:14px; color:var(--dark); }
.form-group label .required { color:var(--danger); margin-left:3px; }

.form-control {
    width:100%;
    padding:12px 16px;
    border:2px solid var(--light-gray);
    border-radius:8px;
    font-size:15px;
    transition:var(--transition);
}
.form-control:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(67,97,238,0.1); }
textarea.form-control { min-height:100px; resize:vertical; }

.file-upload { position:relative; display:block; }
.file-upload input[type=file] { position:absolute; opacity:0; width:100%; height:100%; cursor:pointer; z-index:2; }
.file-upload-label {
    display:flex; align-items:center; gap:12px; padding:12px 16px; border:2px dashed var(--light-gray); border-radius:8px;
    background:var(--light); cursor:pointer; transition:var(--transition); z-index:1;
}
.file-upload-label:hover { border-color:var(--primary); background:rgba(67,97,238,0.05); }
.file-upload-label i { color:var(--primary); font-size:20px; }
.file-upload-label span { font-size:14px; color:var(--gray); }
.file-upload-label small { margin-left:auto; color:var(--primary); font-weight:600; }

.password-group { position:relative; }
.toggle-password {
    position:absolute; right:15px; top:50%; transform:translateY(-50%);
    background:none; border:none; color:var(--gray); cursor:pointer; font-size:18px; padding:5px;
}

.submit-btn {
    width:100%;
    padding:15px;
    background:linear-gradient(to right, var(--primary), var(--secondary));
    color:white;
    border:none;
    border-radius:8px;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
    transition:var(--transition);
    display:flex;
    justify-content:center;
    align-items:center;
    gap:10px;
}
.submit-btn:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(67,97,238,0.3); }
.submit-btn:active { transform:translateY(0); }

.error-message { background:linear-gradient(to right, #ff758c, #ff7eb3); color:white; padding:15px; border-radius:8px; margin-bottom:25px; text-align:center; font-size:14px; }
.success-message { background:linear-gradient(to right, #43e97b, #38f9d7); color:white; padding:15px; border-radius:8px; margin-bottom:25px; text-align:center; font-size:14px; }

@media(max-width:768px) {
    .register-wrapper { flex-direction:column; }
    .register-left, .register-right { padding:30px; }
    .form-grid { grid-template-columns:1fr; gap:15px; }
}
</style>

<div class="register-wrapper">
    <div class="register-left">
        <div class="brand-logo"><i>👨‍🏫</i> FacultyConnect</div>
        <h2 class="brand-title">Join Our Faculty Community</h2>
        <p class="brand-subtitle">Connect with students, manage appointments, and share knowledge effortlessly.</p>
        <ul class="features-list">
            <li><i>✓</i> Easy appointment scheduling</li>
            <li><i>✓</i> Secure file sharing</li>
            <li><i>✓</i> Real-time availability</li>
            <li><i>✓</i> Student progress tracking</li>
            <li><i>✓</i> Department collaboration</li>
        </ul>
        <div class="login-link">
            Already have an account? <a href="/springwtj/FacultyConnect/App/View/auth/login.php">Sign In</a>
        </div>
    </div>

    <div class="register-right">
        <div class="form-header">
            <h1>Teacher Registration</h1>
            <p>Fill in your details to create your faculty account</p>
        </div>

        <?php if (!empty($_GET['success'])): ?>
            <div class="success-message"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>
        <?php if (!empty($_GET['error'])): ?>
            <div class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <form action="/springwtj/FacultyConnect/App/controllers/TeacherController.php" method="POST" enctype="multipart/form-data" id="teacherRegisterForm">
            <div class="form-grid">
                <!-- Teacher ID -->
                <div class="form-group">
                    <label>Teacher ID<span class="required">*</span></label>
                    <input type="text" name="teacher_id" class="form-control" placeholder="T-2024-1" pattern="T-\d{4}-\d+" title="Format: T-YYYY-N (e.g., T-2024-1)" required>
                </div>

                <!-- Full Name -->
                <div class="form-group">
                    <label>Full Name<span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <!-- Date of Birth -->
                <div class="form-group">
                    <label>Date of Birth<span class="required">*</span></label>
                    <input type="date" name="dob" class="form-control" required>
                </div>

                <!-- Gender -->
                <div class="form-group">
                    <label>Gender<span class="required">*</span></label>
                    <select name="gender" class="form-control" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <!-- Nationality -->
                <div class="form-group">
                    <label>Nationality<span class="required">*</span></label>
                    <input type="text" name="nationality" class="form-control" required>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label>Email<span class="required">*</span></label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <!-- Phone -->
                <div class="form-group">
                    <label>Phone<span class="required">*</span></label>
                    <input type="text" name="phone" class="form-control" required>
                </div>

                <!-- Department -->
                <div class="form-group">
                    <label>Department<span class="required">*</span></label>
                    <input type="text" name="department" class="form-control" required>
                </div>

                <!-- Qualifications -->
                <div class="form-group full-width">
                    <label>Qualifications<span class="required">*</span></label>
                    <input type="text" name="qualifications" class="form-control" required>
                </div>

                <!-- Address -->
                <div class="form-group full-width">
                    <label>Address<span class="required">*</span></label>
                    <textarea name="address" class="form-control" required></textarea>
                </div>

                <!-- Profile Picture Upload -->
                <div class="form-group full-width file-upload">
                    <input type="file" name="teacher_picture" id="teacher_picture" accept="image/*" required>
                    <label for="teacher_picture" class="file-upload-label">
                        <i>📷</i> <span>Click to upload profile picture</span>
                        <small>JPG, PNG (Max 2MB)</small>
                    </label>
                </div>

                <!-- Password -->
                <div class="form-group password-group">
                    <label>Password<span class="required">*</span></label>
                    <input type="password" name="password" id="password" class="form-control" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('password')">👁️</button>
                </div>

                <!-- Confirm Password -->
                <div class="form-group password-group">
                    <label>Confirm Password<span class="required">*</span></label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')">👁️</button>
                </div>
            </div>

            <button type="submit" class="submit-btn">🚀 Create Faculty Account</button>
        </form>
    </div>
</div>

<script>
// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const button = field.nextElementSibling;

    if (field.type === 'password') {
        field.type = 'text';
        button.textContent = '🔒'; // Change to "hidden" icon
    } else {
        field.type = 'password';
        button.textContent = '👁️'; // Change to "visible" icon
    }
}

// Client-side validation
document.getElementById('teacherRegisterForm').addEventListener('submit', function(e) {
    const pass = document.getElementById('password').value;
    const confirm = document.getElementById('confirm_password').value;
    if (pass !== confirm) {
        e.preventDefault();
        alert('Passwords do not match.');
    }
});
</script>

<?php require_once __DIR__ . '/../common/footer.php'; ?>
