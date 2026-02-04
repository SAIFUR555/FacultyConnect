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

$success_message = '';
$error_message = '';

/* ===============================
   HANDLE SLOT ADDITION
================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_slot'])) {
    $date = $_POST['date'];
    $time = $_POST['time'];

    $sql = "INSERT INTO slots (teacher_id, date, time, is_booked) VALUES (?, ?, ?, 0)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Failed to prepare statement: " . $conn->error);
    }

    $stmt->bind_param("sss", $teacher_id, $date, $time);
    if ($stmt->execute()) {
        $success_message = "Slot added successfully!";
        // Redirect to the same page to prevent form resubmission
        header("Location: manage_slots.php");
        exit();
    } else {
        $error_message = "Failed to add slot: " . $conn->error;
    }
}

/* ===============================
   HANDLE SLOT DELETION
================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_slot'])) {
    $slot_id = $_POST['slot_id'];

    $sql = "DELETE FROM slots WHERE slot_id = ? AND teacher_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Failed to prepare statement: " . $conn->error);
    }

    $stmt->bind_param("is", $slot_id, $teacher_id);
    if ($stmt->execute()) {
        $success_message = "Slot deleted successfully!";
        // Redirect to the same page to prevent form resubmission
        header("Location: manage_slots.php");
        exit();
    } else {
        $error_message = "Failed to delete slot: " . $conn->error;
    }
}

/* ===============================
   FETCH TEACHER'S SLOTS
================================ */
$sql = "SELECT slot_id, date, time, is_booked FROM slots WHERE teacher_id = ? AND is_booked = 0 ORDER BY date, time";
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
    <title>Manage Slots</title>
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
    <h2>Manage Slots</h2>

    <?php if (!empty($success_message)): ?>
        <p class="success-message"><?php echo $success_message; ?></p>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <p class="error-message"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <!-- Add Slot Form -->
    <form method="POST" action="">
        <h3>Add New Slot</h3>
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required>
        <label for="time">Time:</label>
        <input type="time" id="time" name="time" required>
        <button type="submit" name="add_slot">Add Slot</button>
    </form>
</div>

<div class="box">
    <h3>Your Slots</h3>
    <?php if ($result->num_rows > 0) { ?>
        <table>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Action</th>
            </tr>
            <?php while ($slot = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($slot['date']); ?></td>
                    <td><?php echo htmlspecialchars($slot['time']); ?></td>
                    <td>
                        <form method="POST" action="" style="display: inline;">
                            <input type="hidden" name="slot_id" value="<?php echo $slot['slot_id']; ?>">
                            <button type="submit" name="delete_slot">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>No available slots. Add new slots above.</p>
    <?php } ?>
</div>

<div style="text-align: center;">
    <a class="back-link" href="dashboard.php">Back to Dashboard</a>
</div>

</body>
</html>
