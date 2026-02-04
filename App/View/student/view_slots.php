<?php
session_start();

// Include Database class
require_once __DIR__ . '/../../models/Database.php';

// Check if the student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: ../auth/Login.php");
    exit();
}

// Create Database instance and get connection
$database = new Database();
$conn = $database->conn;

/* ===============================
   FETCH AVAILABLE SLOTS
================================ */
$sql = "SELECT s.slot_id, t.name AS teacher_name, t.department, s.date, s.time
        FROM slots s
        JOIN teacher t ON s.teacher_id = t.teacher_id
        WHERE s.is_booked = 0
        ORDER BY s.date, s.time";

$result = $conn->query($sql);

if (!$result) {
    die("Failed to fetch available slots: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available Slots</title>
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
    <h2>Available Slots</h2>
    <?php if ($result->num_rows > 0) { ?>
        <table>
            <tr>
                <th>Teacher Name</th>
                <th>Department</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
            <?php while ($slot = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($slot['teacher_name']); ?></td>
                    <td><?php echo htmlspecialchars($slot['department']); ?></td>
                    <td><?php echo htmlspecialchars($slot['date']); ?></td>
                    <td><?php echo htmlspecialchars($slot['time']); ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>No available slots at the moment.</p>
    <?php } ?>
</div>

<div style="text-align: center;">
    <a class="back-link" href="dashboard.php">Back to Dashboard</a>
</div>

</body>
</html>
