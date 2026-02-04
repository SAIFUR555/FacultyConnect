<?php

class AuthController extends Controller {
    // ...existing code...

    private function registerUser($userType) {
        $model = $userType === 'student' ? new Student() : new Teacher();
        $view = $userType === 'student' ? 'auth/student_register' : 'auth/teacher_register';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize and validate input data
            $data = [
                'name' => isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '',
                'email' => isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL) : '',
                'password' => $_POST['password'] ?? '',
                // ...other fields...
            ];

            // Handle image upload
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../uploads/';
                $fileName = basename($_FILES['profile_picture']['name']);
                $targetFile = $uploadDir . $fileName;

                // Move the uploaded file to the target directory
                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile)) {
                    $data['profile_picture'] = '/uploads/' . $fileName; // Save relative path
                } else {
                    $this->view($view, ['error' => 'Failed to upload profile picture.']);
                    return;
                }
            } else {
                $data['profile_picture'] = null; // No image uploaded
            }

            // Validate required fields
            if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
                $this->view($view, ['error' => 'All fields are required.']);
                return;
            }

            // Validate email format
            if (!$data['email']) {
                $this->view($view, ['error' => 'Invalid email format.']);
                return;
            }

            // Validate password complexity (example: minimum 8 characters)
            if (strlen($data['password']) < 8) {
                $this->view($view, ['error' => 'Password must be at least 8 characters long.']);
                return;
            }

            // Hash the password
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

            // Attempt to create the user
            $createMethod = $userType === 'student' ? 'createStudent' : 'createTeacher';
            if (method_exists($model, $createMethod)) {
                try {
                    if ($model->$createMethod($data)) {
                        header('Location: /auth/login');
                        exit;
                    } else {
                        $this->view($view, ['error' => 'Registration failed. Please try again.']);
                    }
                } catch (Exception $e) {
                    $this->view($view, ['error' => 'An error occurred: ' . $e->getMessage()]);
                }
            } else {
                $this->view($view, ['error' => 'Invalid user type.']);
            }
        } else {
            $this->view($view);
        }
    }

    public function registerStudent() {
        $this->registerUser('student');
    }

    public function registerTeacher() {
        $this->registerUser('teacher');
    }

    // ...existing code...
}