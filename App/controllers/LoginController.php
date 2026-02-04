<?php
session_start();
require_once __DIR__ . '/../models/Teacher.php';
require_once __DIR__ . '/../models/Student.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? '';
    $password = $_POST['password'] ?? '';

    // Check if the user is a teacher
    $teacher = getTeacherById($user_id);
    if ($teacher && password_verify($password, $teacher['password'])) {
        // Set session variables for the teacher
        $_SESSION['teacher_id'] = $teacher['teacher_id'];
        $_SESSION['teacher_name'] = $teacher['name'];

        // Redirect to the teacher dashboard
        header('Location: /springwtj/FacultyConnect/App/View/teacher/dashboard.php');
        exit;
    }

    // Check if the user is a student
    $student = getStudentById($user_id);
    if ($student && password_verify($password, $student['password'])) {
        // Set session variables for the student
        $_SESSION['student_id'] = $student['student_id'];
        $_SESSION['student_name'] = $student['name'];

        // Redirect to the student dashboard
        header('Location: /springwtj/FacultyConnect/App/View/student/dashboard.php');
        exit;
    }

    // If no match, redirect back to login with an error
    header('Location: /springwtj/FacultyConnect/App/View/auth/login.php?error=Invalid ID or password');
    exit;
}
?>
