<?php
session_start();

// Include necessary files
require_once __DIR__ . '/../../models/Teacher.php';

// Check if the teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../auth/Login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];

// Fetch teacher info
$teacher = getTeacherById($teacher_id);
if (!$teacher) {
    die("Teacher not found!");
}

// Fetch appointments for the teacher
$appointments = getAppointmentsByTeacherId($teacher_id);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teacher Dashboard</title>
    <style>
        body { font-family: Arial; background: #f4f6f9; padding: 20px; }
        .box { background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; color: #2c3e50; }
        h3 { color: #3498db; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px 15px; border: 1px solid #ddd; text-align: left; }
        th { background: #3498db; color: white; }
        tr:nth-child(even) { background-color: #f8f9fa; }
        .logout { color: #e74c3c; text-decoration: none; font-weight: bold; padding: 8px 15px; border: 1px solid #e74c3c; border-radius: 4px; display: inline-block; }
        .logout:hover { background: #e74c3c; color: white; text-decoration: none; }
    </style>
</head>
<body>

<!-- ===============================
     WELCOME
================================ -->
<div class="box">
    <h2>Welcome, <?php echo htmlspecialchars($teacher['name']); ?> 🎓</h2>
    <p>Teacher ID: <?php echo htmlspecialchars($teacher['teacher_id']); ?></p>
</div>

<!-- ===============================
     APPOINTMENT REQUESTS
================================ -->
<div class="box">
    <h3>Appointment Requests</h3>
    <?php if (!empty($appointments)) { ?>
        <table>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Student Name</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>
            <?php foreach ($appointments as $appointment) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($appointment['date']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['time']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['student_name']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['student_email']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['student_phone']); ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>No appointment requests at the moment.</p>
    <?php } ?>
</div>

<!-- ===============================
     LOGOUT
================================ -->
<div class="box" style="text-align: center;">
    <a class="logout" href="../auth/Login.php?logout=true" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
</div>

</body>
</html>
