<?php
session_start();

// Include Database class
require_once __DIR__ . '/../models/Database.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Call the registerStudent function
    registerStudent();
}

/**
 * Function to handle student registration
 */
function registerStudent() {
    // Create Database instance and get connection
    $database = new Database();
    $conn = $database->conn;

    // Validate and sanitize input
    $student_id = htmlspecialchars(trim($_POST['student_id']));
    $name = htmlspecialchars(trim($_POST['name']));
    $dob = htmlspecialchars(trim($_POST['dob']));
    $gender = htmlspecialchars(trim($_POST['gender']));
    $nationality = htmlspecialchars(trim($_POST['nationality']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $address = htmlspecialchars(trim($_POST['address']));
    $emergency_contact = htmlspecialchars(trim($_POST['emergency_contact']));
    $department = htmlspecialchars(trim($_POST['department']));
    $education = htmlspecialchars(trim($_POST['education']));
    $guardian_name = htmlspecialchars(trim($_POST['guardian_name']));
    $guardian_phone = htmlspecialchars(trim($_POST['guardian_phone']));
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Handle file upload
    $student_picture = null;
    if (isset($_FILES['student_picture']) && $_FILES['student_picture']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../uploads/students/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_tmp = $_FILES['student_picture']['tmp_name'];
        $file_name = uniqid('student_', true) . '.' . pathinfo($_FILES['student_picture']['name'], PATHINFO_EXTENSION);
        $student_picture = 'uploads/students/' . $file_name;

        if (!move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
            $_SESSION['error'] = 'Failed to upload profile picture.';
            header('Location: ../View/auth/student_register.php');
            exit();
        }
    }

    // Insert student data into the database
    $sql = "INSERT INTO student (student_id, name, dob, gender, nationality, email, phone, address, emergency_contact, department, education, guardian_name, guardian_phone, student_picture, password)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        $_SESSION['error'] = 'Failed to prepare statement: ' . $conn->error;
        header('Location: ../View/auth/student_register.php');
        exit();
    }

    $stmt->bind_param(
        "sssssssssssssss",
        $student_id,
        $name,
        $dob,
        $gender,
        $nationality,
        $email,
        $phone,
        $address,
        $emergency_contact,
        $department,
        $education,
        $guardian_name,
        $guardian_phone,
        $student_picture,
        $password
    );

    if ($stmt->execute()) {
        // Redirect to the registration success page
        header('Location: ../View/auth/reg_success.php');
        exit();
    } else {
        $_SESSION['error'] = 'Failed to register student: ' . $stmt->error;
        header('Location: ../View/auth/student_register.php');
        exit();
    }
}
?>
