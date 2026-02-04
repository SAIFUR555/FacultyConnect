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

$student_id = $_SESSION['student_id'];

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

/* ===============================
   HANDLE SLOT BOOKING
================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $slot_id = $_POST['slot_id'];
    $reason = $_POST['reason'];

    // Update the slot to mark it as booked
    $update_sql = "UPDATE slots SET is_booked = 1 WHERE slot_id = ?";
    $stmt = $conn->prepare($update_sql);

    if (!$stmt) {
        die("Failed to prepare statement: " . $conn->error);
    }

    $stmt->bind_param("i", $slot_id);
    if ($stmt->execute()) {
        // Insert the booking into the appointments table
        $insert_sql = "INSERT INTO appointments (student_id, slot_id, reason) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);

        if (!$insert_stmt) {
            die("Failed to prepare statement: " . $conn->error);
        }

        $insert_stmt->bind_param("sis", $student_id, $slot_id, $reason);
        if ($insert_stmt->execute()) {
            header("Location: dashboard.php"); // Redirect to the student dashboard after booking
            exit();
        } else {
            $error_message = "Failed to book appointment: " . $conn->error;
        }
    } else {
        $error_message = "Failed to update slot: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Appointment</title>
    <style>
        body { font-family: Arial; background: #f4f6f9; padding: 20px; }
        .box { background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px 15px; border: 1px solid #ddd; text-align: left; }
        th { background: #3498db; color: white; }
        tr:nth-child(even) { background-color: #f8f9fa; }
        .success-message { color: #28a745; margin-bottom: 15px; }
        .error-message { color: #d93025; margin-bottom: 15px; }
        .back-link { color: #3498db; text-decoration: none; font-weight: bold; }
        .back-link:hover { text-decoration: underline; }
        button { padding: 8px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #1a73e8; }
    </style>
</head>
<body>

<div class="box">
    <h2>Book Appointment</h2>

    <?php if (!empty($error_message)): ?>
        <p class="error-message"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <?php if ($result->num_rows > 0) { ?>
        <form method="POST" action="">
            <table>
                <tr>
                    <th>Teacher Name</th>
                    <th>Department</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Reason</th>
                    <th>Action</th>
                </tr>
                <?php while ($slot = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($slot['teacher_name']); ?></td>
                        <td><?php echo htmlspecialchars($slot['department']); ?></td>
                        <td><?php echo htmlspecialchars($slot['date']); ?></td>
                        <td><?php echo htmlspecialchars($slot['time']); ?></td>
                        <td>
                            <input type="text" name="reason" placeholder="Reason for booking" required>
                        </td>
                        <td>
                            <button type="submit" name="slot_id" value="<?php echo $slot['slot_id']; ?>">Book</button>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </form>
    <?php } else { ?>
        <p>No available slots at the moment.</p>
    <?php } ?>
</div>

<div style="text-align: center;">
    <a class="back-link" href="dashboard.php">Back to Dashboard</a>
</div>

</body>
</html>
