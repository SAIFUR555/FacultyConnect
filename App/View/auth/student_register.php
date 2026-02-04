<?php require_once __DIR__ . '/../common/header.php'; ?>

<style>
/* Modern Color Palette */
:root {
    --primary: #4361ee;
    --primary-dark: #3a56d4;
    --secondary: #7209b7;
    --accent: #4cc9f0;
    --light: #f8f9fa;
    --dark: #212529;
    --gray: #6c757d;
    --light-gray: #e9ecef;
    --success: #38b000;
    --danger: #f72585;
    --warning: #ff9e00;
    --border-radius: 12px;
    --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    --transition: all 0.3s ease;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    line-height: 1.6;
    color: var(--dark);
}

/* Main Container */
.register-wrapper {
    width: 100%;
    max-width: 1000px;
    display: flex;
    min-height: 700px;
    overflow: hidden;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
}

/* Left Side - Branding */
.register-left {
    flex: 1;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    color: white;
    padding: 50px 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.register-left::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="white" opacity="0.05"/></svg>');
}

.brand-logo {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.brand-logo i {
    font-size: 32px;
}

.brand-title {
    font-size: 26px;
    font-weight: 600;
    margin-bottom: 15px;
    line-height: 1.3;
}

.brand-subtitle {
    font-size: 16px;
    opacity: 0.9;
    margin-bottom: 30px;
}

.features-list {
    list-style: none;
    margin-top: 30px;
}

.features-list li {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    font-size: 15px;
}

.features-list i {
    margin-right: 12px;
    color: var(--accent);
    font-size: 18px;
}

.login-link {
    margin-top: 40px;
    text-align: center;
    font-size: 14px;
}

.login-link a {
    color: white;
    text-decoration: none;
    font-weight: 600;
    opacity: 0.9;
    transition: var(--transition);
    display: inline-block;
    padding: 8px 20px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 6px;
}

.login-link a:hover {
    opacity: 1;
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.5);
}

/* Right Side - Form */
.register-right {
    flex: 1.5;
    background: white;
    padding: 50px 40px;
    overflow-y: auto;
    max-height: 700px;
}

.form-header {
    margin-bottom: 30px;
    text-align: center;
}

.form-header h1 {
    font-size: 28px;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 8px;
}

.form-header p {
    color: var(--gray);
    font-size: 15px;
}

/* Form Grid */
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 25px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--dark);
    font-size: 14px;
}

.form-group label .required {
    color: var(--danger);
    margin-left: 3px;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid var(--light-gray);
    border-radius: 8px;
    font-size: 15px;
    transition: var(--transition);
    background: white;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.form-control::placeholder {
    color: #adb5bd;
}

textarea.form-control {
    min-height: 100px;
    resize: vertical;
}

/* File Upload */
.file-upload {
    position: relative;
    display: block;
}

.file-upload input[type="file"] {
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

.file-upload-label {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: var(--light);
    border: 2px dashed var(--light-gray);
    border-radius: 8px;
    cursor: pointer;
    transition: var(--transition);
}

.file-upload-label:hover {
    border-color: var(--primary);
    background: rgba(67, 97, 238, 0.05);
}

.file-upload-label i {
    color: var(--primary);
    font-size: 20px;
}

.file-upload-label span {
    font-size: 14px;
    color: var(--gray);
}

.file-upload-label small {
    margin-left: auto;
    color: var(--primary);
    font-weight: 600;
}

/* Password Fields */
.password-group {
    position: relative;
}

.toggle-password {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--gray);
    cursor: pointer;
    font-size: 18px;
    padding: 5px;
    z-index: 2;
}

/* Submit Button */
.submit-btn {
    width: 100%;
    padding: 16px;
    background: linear-gradient(to right, var(--primary), var(--secondary));
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    margin-top: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    position: relative;
    overflow: hidden;
}

.submit-btn::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 5px;
    height: 5px;
    background: rgba(255, 255, 255, 0.5);
    opacity: 0;
    border-radius: 100%;
    transform: scale(1, 1) translate(-50%);
    transform-origin: 50% 50%;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
}

.submit-btn:active {
    transform: translateY(0);
}

.submit-btn:active::after {
    animation: ripple 1s ease-out;
}

@keyframes ripple {
    0% {
        transform: scale(0, 0);
        opacity: 0.5;
    }
    100% {
        transform: scale(50, 50);
        opacity: 0;
    }
}

/* Error Message */
.error-message {
    background: linear-gradient(to right, #ff758c, #ff7eb3);
    color: white;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 25px;
    text-align: center;
    font-size: 14px;
    animation: slideDown 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.success-message {
    background: linear-gradient(to right, var(--success), #38f9d7);
    color: white;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 25px;
    text-align: center;
    font-size: 14px;
    animation: slideDown 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Form Note */
.form-note {
    background: rgba(76, 201, 240, 0.1);
    border-left: 4px solid var(--accent);
    padding: 15px;
    margin: 20px 0;
    border-radius: 0 8px 8px 0;
    font-size: 14px;
    color: var(--gray);
}

/* Responsive */
@media (max-width: 768px) {
    .register-wrapper {
        flex-direction: column;
    }
    
    .register-left {
        padding: 30px 20px;
    }
    
    .register-right {
        padding: 30px 20px;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .brand-title {
        font-size: 22px;
    }
}

/* Password strength indicator */
.password-strength {
    height: 4px;
    background: var(--light-gray);
    border-radius: 2px;
    margin-top: 5px;
    overflow: hidden;
}

.strength-meter {
    height: 100%;
    width: 0;
    border-radius: 2px;
    transition: width 0.3s ease;
}

.strength-weak { background: var(--danger); width: 30%; }
.strength-medium { background: var(--warning); width: 60%; }
.strength-strong { background: var(--success); width: 100%; }

.strength-text {
    font-size: 12px;
    color: var(--gray);
    margin-top: 5px;
}

/* Login prompt at bottom */
.login-prompt {
    text-align: center;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid var(--light-gray);
    color: var(--gray);
    font-size: 15px;
}

.login-prompt a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
}

.login-prompt a:hover {
    color: var(--secondary);
    text-decoration: underline;
}
</style>

<div class="register-wrapper">
    <!-- Left Side - Branding -->
    <div class="register-left">
        <div class="brand-logo">
            <span>🎓</span>
            FacultyConnect
        </div>
        <h2 class="brand-title">Join Our Student Community</h2>
        <p class="brand-subtitle">Connect with faculty, schedule appointments, and enhance your academic journey.</p>
        
        <ul class="features-list">
            <li><span>✓</span> Easy appointment scheduling with faculty</li>
            <li><span>✓</span> Track your academic progress</li>
            <li><span>✓</span> Access to faculty office hours</li>
            <li><span>✓</span> Secure academic communication</li>
            <li><span>✓</span> Department resources and updates</li>
        </ul>
    </div>

    <!-- Right Side - Form -->
    <div class="register-right">
        <div class="form-header">
            <h1>Student Registration</h1>
            <p>Create your student account to connect with faculty</p>
        </div>

        <?php if (!empty($data['error'])): ?>
            <div class="error-message">
                <span>⚠️</span>
                <?php echo htmlspecialchars($data['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($data['success'])): ?>
            <div class="success-message">
                <span>✅</span>
                <?php echo htmlspecialchars($data['success']); ?>
            </div>
        <?php endif; ?>

        <div class="form-note">
            <strong>Note:</strong> All fields marked with <span style="color: #f72585;">*</span> are required.
        </div>

        <form action="/springwtj/FacultyConnect/App/controllers/StudentController.php" method="POST" enctype="multipart/form-data" id="studentRegisterForm">
            <div class="form-grid">
                <!-- Student ID -->
                <div class="form-group">
                    <label>Student ID<span class="required">*</span></label>
                    <input type="text" name="student_id" class="form-control" 
                           placeholder="S-2024-001" 
                           value="<?php echo isset($data['old']['student_id']) ? htmlspecialchars($data['old']['student_id']) : ''; ?>"
                           required>
                </div>

                <!-- Full Name -->
                <div class="form-group">
                    <label>Full Name<span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" 
                           placeholder="John Doe" 
                           value="<?php echo isset($data['old']['name']) ? htmlspecialchars($data['old']['name']) : ''; ?>"
                           required>
                </div>

                <!-- Date of Birth -->
                <div class="form-group">
                    <label>Date of Birth<span class="required">*</span></label>
                    <input type="date" name="dob" class="form-control" 
                           value="<?php echo isset($data['old']['dob']) ? htmlspecialchars($data['old']['dob']) : ''; ?>"
                           required>
                </div>

                <!-- Gender -->
                <div class="form-group">
                    <label>Gender<span class="required">*</span></label>
                    <select name="gender" class="form-control" required>
                        <option value="">Select Gender</option>
                        <option value="Male" <?php echo (isset($data['old']['gender']) && $data['old']['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo (isset($data['old']['gender']) && $data['old']['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo (isset($data['old']['gender']) && $data['old']['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>

                <!-- Nationality -->
                <div class="form-group">
                    <label>Nationality<span class="required">*</span></label>
                    <input type="text" name="nationality" class="form-control" 
                           placeholder="Enter nationality" 
                           value="<?php echo isset($data['old']['nationality']) ? htmlspecialchars($data['old']['nationality']) : ''; ?>"
                           required>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label>Email Address<span class="required">*</span></label>
                    <input type="email" name="email" class="form-control" 
                           placeholder="john.doe@student.edu" 
                           value="<?php echo isset($data['old']['email']) ? htmlspecialchars($data['old']['email']) : ''; ?>"
                           required>
                </div>

                <!-- Phone -->
                <div class="form-group">
                    <label>Phone Number<span class="required">*</span></label>
                    <input type="text" name="phone" class="form-control" 
                           placeholder="+1 (555) 123-4567" 
                           value="<?php echo isset($data['old']['phone']) ? htmlspecialchars($data['old']['phone']) : ''; ?>"
                           required>
                </div>

                <!-- Address -->
                <div class="form-group full-width">
                    <label>Address<span class="required">*</span></label>
                    <textarea name="address" class="form-control" 
                              placeholder="Enter your current address" 
                              required><?php echo isset($data['old']['address']) ? htmlspecialchars($data['old']['address']) : ''; ?></textarea>
                </div>

                <!-- Emergency Contact -->
                <div class="form-group">
                    <label>Emergency Contact<span class="required">*</span></label>
                    <input type="text" name="emergency_contact" class="form-control" 
                           placeholder="Emergency contact number" 
                           value="<?php echo isset($data['old']['emergency_contact']) ? htmlspecialchars($data['old']['emergency_contact']) : ''; ?>"
                           required>
                </div>

                <!-- Department -->
                <div class="form-group">
                    <label>Department<span class="required">*</span></label>
                    <input type="text" name="department" class="form-control" 
                           placeholder="Computer Science" 
                           value="<?php echo isset($data['old']['department']) ? htmlspecialchars($data['old']['department']) : ''; ?>"
                           required>
                </div>

                <!-- Education Level -->
                <div class="form-group">
                    <label>Education Level<span class="required">*</span></label>
                    <select name="education" class="form-control" required>
                        <option value="">Select Level</option>
                        <option value="Undergraduate" <?php echo (isset($data['old']['education']) && $data['old']['education'] == 'Undergraduate') ? 'selected' : ''; ?>>Undergraduate</option>
                        <option value="Graduate" <?php echo (isset($data['old']['education']) && $data['old']['education'] == 'Graduate') ? 'selected' : ''; ?>>Graduate</option>
                        <option value="Postgraduate" <?php echo (isset($data['old']['education']) && $data['old']['education'] == 'Postgraduate') ? 'selected' : ''; ?>>Postgraduate</option>
                        <option value="Doctoral" <?php echo (isset($data['old']['education']) && $data['old']['education'] == 'Doctoral') ? 'selected' : ''; ?>>Doctoral</option>
                        <option value="Other" <?php echo (isset($data['old']['education']) && $data['old']['education'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>

                <!-- Guardian Name -->
                <div class="form-group">
                    <label>Guardian Name<span class="required">*</span></label>
                    <input type="text" name="guardian_name" class="form-control" 
                           placeholder="Guardian's full name" 
                           value="<?php echo isset($data['old']['guardian_name']) ? htmlspecialchars($data['old']['guardian_name']) : ''; ?>"
                           required>
                </div>

                <!-- Guardian Phone -->
                <div class="form-group">
                    <label>Guardian Phone<span class="required">*</span></label>
                    <input type="text" name="guardian_phone" class="form-control" 
                           placeholder="Guardian's phone number" 
                           value="<?php echo isset($data['old']['guardian_phone']) ? htmlspecialchars($data['old']['guardian_phone']) : ''; ?>"
                           required>
                </div>

                <!-- Profile Picture -->
                <div class="form-group full-width">
                    <label>Profile Picture<span class="required">*</span></label>
                    <div class="file-upload">
                        <input type="file" name="student_picture" id="student_picture" accept="image/*" required>
                        <label for="student_picture" class="file-upload-label">
                            <span>📷</span>
                            <span id="file-label">Click to upload profile picture</span>
                            <small>JPG, PNG (Max 2MB)</small>
                        </label>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group password-group">
                    <label>Password<span class="required">*</span></label>
                    <input type="password" name="password" id="password" class="form-control" 
                           placeholder="Create a strong password" 
                           oninput="checkPasswordStrength(this.value)" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('password')"></button>
                    <div class="password-strength">
                        <div class="strength-meter" id="strength-meter"></div>
                    </div>
                    <div class="strength-text" id="strength-text"></div>
                </div>

                <!-- Confirm Password -->
                <div class="form-group password-group">
                    <label>Confirm Password<span class="required">*</span></label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" 
                           placeholder="Re-enter your password" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')"></button>
                </div>
            </div>

            <button type="submit" class="submit-btn">
                <span>🚀</span> Create Student Account
            </button>
            
            <!-- Login prompt at bottom -->
            <div class="login-prompt">
                Already have an account? <a href="/springwtj/FacultyConnect/App/View/auth/login.php">Login here</a>
            </div>
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
        button.textContent = '👁️'; // Set eye emoji when showing password
    } else {
        field.type = 'password';
        button.textContent = '👁️'; // Keep eye emoji when hiding password
    }
}

// Password strength checker
function checkPasswordStrength(password) {
    const meter = document.getElementById('strength-meter');
    const text = document.getElementById('strength-text');
    
    let strength = 0;
    let tips = '';
    
    // Check password length
    if (password.length < 6) {
        tips = 'Password too short';
    } else if (password.length < 8) {
        strength += 1;
        tips = 'Fair';
    } else {
        strength += 1;
    }
    
    // Check for mixed case
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) {
        strength += 1;
    }
    
    // Check for numbers
    if (/\d/.test(password)) {
        strength += 1;
    }
    
    // Check for special characters
    if (/[^A-Za-z0-9]/.test(password)) {
        strength += 1;
    }
    
    // Update strength meter
    let strengthClass = '';
    let strengthText = '';
    
    if (password.length === 0) {
        strengthClass = '';
        strengthText = '';
        meter.style.width = '0';
    } else if (strength <= 1) {
        strengthClass = 'strength-weak';
        strengthText = 'Weak';
        tips = 'Add numbers, uppercase, and special characters';
    } else if (strength <= 3) {
        strengthClass = 'strength-medium';
        strengthText = 'Medium';
        tips = 'Good, but could be stronger';
    } else {
        strengthClass = 'strength-strong';
        strengthText = 'Strong';
        tips = 'Excellent password!';
    }
    
    meter.className = 'strength-meter ' + strengthClass;
    text.textContent = strengthText + (tips ? ' - ' + tips : '');
}

// File upload preview
document.getElementById('student_picture').addEventListener('change', function(e) {
    const label = document.getElementById('file-label');
    const file = e.target.files[0];
    
    if (file) {
        label.textContent = file.name;
        
        // Check file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            showError('File size must be less than 2MB.');
            e.target.value = '';
            label.textContent = 'Click to upload profile picture';
        }
        
        // Check file type
        const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            showError('Only JPG, PNG, and GIF files are allowed.');
            e.target.value = '';
            label.textContent = 'Click to upload profile picture';
        }
    }
});

// Form validation
document.getElementById('studentRegisterForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    // Check password match
    if (password !== confirmPassword) {
        e.preventDefault();
        showError('Passwords do not match. Please ensure both passwords are identical.');
        return false;
    }
    
    // Check password strength
    if (password.length < 8) {
        e.preventDefault();
        showError('Password must be at least 8 characters long.');
        return false;
    }
    
    // Check for special characters
    if (!/(?=.*[!@#$%^&*])/.test(password)) {
        e.preventDefault();
        showError('Password must contain at least one special character (!@#$%^&*).');
        return false;
    }
    
    // Check for numbers
    if (!/(?=.*\d)/.test(password)) {
        e.preventDefault();
        showError('Password must contain at least one number.');
        return false;
    }
    
    // Check for uppercase
    if (!/(?=.*[A-Z])/.test(password)) {
        e.preventDefault();
        showError('Password must contain at least one uppercase letter.');
        return false;
    }
    
    // Check email format
    const email = document.querySelector('input[type="email"]').value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        showError('Please enter a valid email address.');
        return false;
    }
    
    return true;
});

function showError(message) {
    // Remove existing error message
    const existingError = document.querySelector('.error-message');
    if (existingError) existingError.remove();
    
    // Create new error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.innerHTML = `<span>⚠️</span> ${message}`;
    
    // Insert after form header
    const formHeader = document.querySelector('.form-header');
    formHeader.parentNode.insertBefore(errorDiv, formHeader.nextSibling);
    
    // Scroll to error
    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        errorDiv.style.opacity = '0';
        setTimeout(() => errorDiv.remove(), 300);
    }, 5000);
}

// Real-time validation for student ID
document.querySelector('input[name="student_id"]').addEventListener('blur', function(e) {
    const studentId = e.target.value;
    const regex = /^[A-Za-z0-9\-]+$/;
    
    if (studentId && !regex.test(studentId)) {
        showError('Student ID can only contain letters, numbers, and hyphens.');
        e.target.focus();
    }
});

// Real-time validation for phone number
document.querySelector('input[name="phone"]').addEventListener('blur', function(e) {
    const phone = e.target.value;
    const regex = /^[\d\s\-\+\(\)]+$/;
    
    if (phone && !regex.test(phone)) {
        showError('Phone number can only contain digits, spaces, hyphens, plus sign, and parentheses.');
        e.target.focus();
    }
});
</script>

<?php require_once __DIR__ . '/../common/footer.php'; ?>