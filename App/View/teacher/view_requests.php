<?php
session_start();

// Include Database class
require_once __DIR__ . '/../../models/Database.php';

// Check if the teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../auth/Login.php");
    exit();
}

// Create Database instance and get connection
$database = new Database();
$conn = $database->conn;

$teacher_id = $_SESSION['teacher_id'];

/* ===============================
   FETCH APPOINTMENT REQUESTS
================================ */
$sql = "SELECT a.appointment_id, s.name AS student_name, s.email AS student_email, s.phone AS student_phone, 
               a.reason, sl.date, sl.time, a.status
        FROM appointments a
        JOIN student s ON a.student_id = s.student_id
        JOIN slots sl ON a.slot_id = sl.slot_id
        WHERE sl.teacher_id = ?
        ORDER BY sl.date, sl.time";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Failed to prepare statement: " . $conn->error);
}

$stmt->bind_param("s", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Appointment Requests</title>
    <style>
        body { font-family: Arial; background: #f4f6f9; padding: 20px; }
        .box { background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px 15px; border: 1px solid #ddd; text-align: left; }
        th { background: #3498db; color: white; }
        tr:nth-child(even) { background-color: #f8f9fa; }
        .back-link { color: #3498db; text-decoration: none; font-weight: bold; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="box">
    <h2>Appointment Requests</h2>
    <?php if ($result->num_rows > 0) { ?>
        <table>
            <tr>
                <th>Student Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Reason</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
            <?php while ($request = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($request['student_name']); ?></td>
                    <td><?php echo htmlspecialchars($request['student_email']); ?></td>
                    <td><?php echo htmlspecialchars($request['student_phone']); ?></td>
                    <td><?php echo htmlspecialchars($request['reason']); ?></td>
                    <td><?php echo htmlspecialchars($request['date']); ?></td>
                    <td><?php echo htmlspecialchars($request['time']); ?></td>
                    <td><?php echo htmlspecialchars($request['status']); ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>No appointment requests at the moment.</p>
    <?php } ?>
</div>

<div style="text-align: center;">
    <a class="back-link" href="dashboard.php">Back to Dashboard</a>
</div>

</body>
</html>
