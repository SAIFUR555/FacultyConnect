<?php
session_start();

// Correct the path to Database.php
require_once __DIR__ . '/../models/Database.php';

// Check if the teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../auth/Login.php");
    exit();
}

// Create Database instance and get connection
$database = new Database();
$conn = $database->conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = $_POST['appointment_id'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        // Approve the appointment
        $update_sql = "UPDATE appointments SET status = 'Confirmed' WHERE appointment_id = ?";
        $stmt = $conn->prepare($update_sql);

        if (!$stmt) {
            die("Failed to prepare statement: " . $conn->error);
        }

        $stmt->bind_param("i", $appointment_id);
        if ($stmt->execute()) {
            // Redirect to the teacher's dashboard
            header("Location: ../View/teacher/dashboard.php");
            exit();
        } else {
            die("Failed to approve appointment: " . $stmt->error);
        }
    } elseif ($action === 'decline') {
        // Decline the appointment and delete it from the database
        $delete_sql = "DELETE FROM appointments WHERE appointment_id = ?";
        $stmt = $conn->prepare($delete_sql);

        if (!$stmt) {
            die("Failed to prepare statement: " . $conn->error);
        }

        $stmt->bind_param("i", $appointment_id);
        if ($stmt->execute()) {
            // Redirect back to the teacher's dashboard
            header("Location: ../View/teacher/dashboard.php");
            exit();
        } else {
            die("Failed to decline appointment: " . $stmt->error);
        }
    } else {
        die("Invalid action.");
    }
}
?>
