<?php
require_once __DIR__ . '/../models/Teacher.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $file = $_FILES;

    if (registerTeacher($data, $file)) {
        header('Location: /springwtj/FacultyConnect/App/View/auth/teacher_success.php?success=Teacher registered successfully!');
        exit;
    } else {
        header('Location: /springwtj/FacultyConnect/App/View/auth/teacher_register.php?error=Failed to register teacher.');
        exit;
    }
}
?>
