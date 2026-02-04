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
$sql = "SELECT s.slot_id, t.name AS teacher_name, t.department, s.date, s.time, s.teacher_id
        FROM slots s
        JOIN teacher t ON s.teacher_id = t.teacher_id
        WHERE s.is_booked = 0
        ORDER BY s.date, s.time";

$result = $conn->query($sql);

if (!$result) {
    die("Failed to fetch available slots: " . $conn->error);
}

/* ===============================
   HANDLE SLOT REQUEST
================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if slot_id is set
    if (!isset($_POST['slot_id']) || empty($_POST['slot_id'])) {
        $error_message = "Please select a slot to book.";
    } else {
        $slot_id = $_POST['slot_id'];
        $reason = $_POST['reason'] ?? '';

        // Debugging: Log the slot_id
        error_log("Requested slot_id: " . $slot_id);

        // Check if the slot exists and is not already booked
        $check_slot_sql = "SELECT s.slot_id, s.teacher_id FROM slots s WHERE s.slot_id = ? AND s.is_booked = 0";
        $check_stmt = $conn->prepare($check_slot_sql);

        if (!$check_stmt) {
            die("Failed to prepare statement: " . $conn->error);
        }

        $check_stmt->bind_param("i", $slot_id);
        $check_stmt->execute();
        $slot_result = $check_stmt->get_result();

        if ($slot_result->num_rows === 0) {
            $error_message = "The selected slot is no longer available.";
        } else {
            $slot = $slot_result->fetch_assoc();
            $teacher_id = $slot['teacher_id'];

            // Verify that the teacher_id exists in the teacher table
            $check_teacher_sql = "SELECT teacher_id FROM teacher WHERE teacher_id = ?";
            $check_teacher_stmt = $conn->prepare($check_teacher_sql);

            if (!$check_teacher_stmt) {
                die("Failed to prepare statement: " . $conn->error);
            }

            $check_teacher_stmt->bind_param("s", $teacher_id);
            $check_teacher_stmt->execute();
            $teacher_result = $check_teacher_stmt->get_result();

            if ($teacher_result->num_rows === 0) {
                $error_message = "The teacher associated with this slot does not exist.";
            } else {
                // Start transaction to ensure both operations succeed or fail together
                $conn->begin_transaction();
                
                try {
                    // Update the slot to mark it as booked
                    $update_sql = "UPDATE slots SET is_booked = 1 WHERE slot_id = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    
                    if (!$update_stmt) {
                        throw new Exception("Failed to prepare update statement: " . $conn->error);
                    }
                    
                    $update_stmt->bind_param("i", $slot_id);
                    if (!$update_stmt->execute()) {
                        throw new Exception("Failed to update slot: " . $update_stmt->error);
                    }

                    // Insert the booking into the appointments table WITH teacher_id
                    // Check the structure of your appointments table first
                    $check_appointments_sql = "SHOW COLUMNS FROM appointments";
                    $columns_result = $conn->query($check_appointments_sql);
                    $has_teacher_id_column = false;
                    
                    while ($column = $columns_result->fetch_assoc()) {
                        if ($column['Field'] == 'teacher_id') {
                            $has_teacher_id_column = true;
                            break;
                        }
                    }
                    
                    if ($has_teacher_id_column) {
                        // If appointments table has teacher_id column
                        $insert_sql = "INSERT INTO appointments (student_id, teacher_id, slot_id, reason, status) VALUES (?, ?, ?, ?, 'Pending')";
                        $insert_stmt = $conn->prepare($insert_sql);
                        
                        if (!$insert_stmt) {
                            throw new Exception("Failed to prepare insert statement: " . $conn->error);
                        }
                        
                        $insert_stmt->bind_param("ssis", $student_id, $teacher_id, $slot_id, $reason);
                    } else {
                        // If appointments table doesn't have teacher_id column
                        $insert_sql = "INSERT INTO appointments (student_id, slot_id, reason, status) VALUES (?, ?, ?, 'Pending')";
                        $insert_stmt = $conn->prepare($insert_sql);
                        
                        if (!$insert_stmt) {
                            throw new Exception("Failed to prepare insert statement: " . $conn->error);
                        }
                        
                        $insert_stmt->bind_param("sis", $student_id, $slot_id, $reason);
                    }
                    
                    if (!$insert_stmt->execute()) {
                        throw new Exception("Failed to create appointment: " . $insert_stmt->error);
                    }
                    
                    // Commit transaction
                    $conn->commit();
                    $success_message = "Slot requested successfully!";
                    
                } catch (Exception $e) {
                    // Rollback transaction on error
                    $conn->rollback();
                    $error_message = "Failed to request slot: " . $e->getMessage();
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Request Slot</title>
    <style>
        body { font-family: Arial; background: #f4f6f9; padding: 20px; }
        .box { background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px 15px; border: 1px solid #ddd; text-align: left; }
        th { background: #3498db; color: white; }
        tr:nth-child(even) { background-color: #f8f9fa; }
        .success-message { color: #28a745; margin-bottom: 15px; padding: 10px; background: #d4edda; border-radius: 4px; }
        .error-message { color: #d93025; margin-bottom: 15px; padding: 10px; background: #f8d7da; border-radius: 4px; }
        .back-link { color: #3498db; text-decoration: none; font-weight: bold; }
        .back-link:hover { text-decoration: underline; }
        button { padding: 8px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #1a73e8; }
        input[type="text"] { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .slot-form { margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="box">
    <h2>Request Slot</h2>

    <?php if (isset($success_message)): ?>
        <div class="success-message"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <?php if ($result->num_rows > 0) { ?>
        <table>
            <thead>
                <tr>
                    <th>Teacher Name</th>
                    <th>Department</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Reason</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($slot = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($slot['teacher_name']); ?></td>
                    <td><?php echo htmlspecialchars($slot['department']); ?></td>
                    <td><?php echo htmlspecialchars($slot['date']); ?></td>
                    <td><?php echo htmlspecialchars($slot['time']); ?></td>
                    <td>
                        <form method="POST" action="" class="slot-form">
                            <input type="hidden" name="slot_id" value="<?php echo $slot['slot_id']; ?>">
                            <input type="text" name="reason" placeholder="Reason for booking" required>
                    </td>
                    <td>
                            <button type="submit">Request</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No available slots at the moment.</p>
    <?php } ?>
</div>

<div style="text-align: center; margin-top: 20px;">
    <a class="back-link" href="dashboard.php">Back to Dashboard</a>
</div>

</body>
</html>