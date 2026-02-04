<?php
// Start session at the VERY beginning
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include Database class WITHOUT session_start in it
require_once __DIR__ . '/../../models/Database.php';

// Create Database instance and get connection
$database = new Database();
$conn = $database->conn;

/* ===============================
   CHECK LOGIN
================================ */
if (!isset($_SESSION['student_id'])) {
    header("Location: ../auth/Login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

/* ===============================
   FETCH STUDENT INFO
================================ */
$sql = "SELECT * FROM student WHERE student_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Failed to prepare statement: " . $conn->error);
}

$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    die("Student not found!");
}

/* ===============================
   FETCH STUDENT'S APPOINTMENTS
================================ */
$sql = "SELECT t.name AS teacher_name, t.department, s.date, s.time, a.reason, a.status
        FROM appointments a
        JOIN slots s ON a.slot_id = s.slot_id
        JOIN teacher t ON s.teacher_id = t.teacher_id
        WHERE a.student_id = ?
        ORDER BY s.date, s.time";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Failed to prepare statement: " . $conn->error);
}

$stmt->bind_param("s", $student_id);
$stmt->execute();
$appointments = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
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
        img { border-radius: 8px; margin-top: 10px; border: 3px solid #3498db; }
        p { margin: 8px 0; }
        strong { color: #2c3e50; }
        a { color: #3498db; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .debug { background: #fff3cd; border: 1px solid #ffeaa7; padding: 10px; margin: 10px 0; color: #856404; }
    </style>
</head>
<body>

<!-- ===============================
     DEBUG INFO (remove in production)
================================ -->
<div class="debug" style="display: none;">
    <strong>Debug Info:</strong><br>
    Student ID: <?php echo htmlspecialchars($student_id); ?><br>
    Session Status: <?php echo session_status(); ?><br>
    Database: <?php echo $conn ? "Connected" : "Not Connected"; ?>
</div>

<!-- ===============================
     WELCOME
================================ -->
<div class="box">
    <h2>Welcome, <?php echo htmlspecialchars($student['name']); ?> 🎓</h2>
    <p>Student ID: <?php echo htmlspecialchars($student['student_id']); ?></p>
    <p><a href="request_slot.php">Request Slot</a></p>
</div>

<!-- ===============================
     STUDENT PROFILE
================================ -->
<div class="box">
    <h3>My Profile</h3>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
    <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($student['dob']); ?></p>
    <p><strong>Gender:</strong> <?php echo htmlspecialchars($student['gender']); ?></p>
    <p><strong>Nationality:</strong> <?php echo htmlspecialchars($student['nationality']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($student['phone']); ?></p>
    <p><strong>Department:</strong> <?php echo htmlspecialchars($student['department']); ?></p>
    <p><strong>Education:</strong> <?php echo htmlspecialchars($student['education']); ?></p>

    <?php 
    if (!empty($student['student_picture'])) {
        // Correct the path for the uploaded image
        $image_path = "../../uploads/students/" . $student['student_picture'];

        if (file_exists(__DIR__ . '/../../uploads/students/' . $student['student_picture'])) {
            ?>
            <img src="<?php echo htmlspecialchars($image_path); ?>" width="120" alt="Student Picture">
            <?php
        } else {
            echo "<p><em>Profile picture not found</em></p>";
        }
    } else {
        echo "<p><em>No profile picture uploaded</em></p>";
    }
    ?>
</div>

<!-- ===============================
     APPOINTMENTS
================================ -->
<div class="box">
    <h3>My Booked Slots</h3>
    <?php if ($appointments->num_rows > 0) { ?>
        <table>
            <tr>
                <th>Teacher Name</th>
                <th>Department</th>
                <th>Date</th>
                <th>Time</th>
                <th>Reason</th>
                <th>Status</th>
            </tr>
            <?php while ($appointment = $appointments->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($appointment['teacher_name']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['department']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['date']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['time']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['reason']); ?></td>
                    <td>
                        <?php
                        $status = $appointment['status'];
                        if ($status === 'Pending') {
                            echo '<span style="color: #ffc107; font-weight: bold;">Pending</span>';
                        } elseif ($status === 'Confirmed') {
                            echo '<span style="color: #4cc9f0; font-weight: bold;">Confirmed</span>';
                        } elseif ($status === 'Completed') {
                            echo '<span style="color: #43e97b; font-weight: bold;">Completed</span>';
                        } elseif ($status === 'Declined') {
                            echo '<span style="color: #f72585; font-weight: bold;">Declined</span>';
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>No booked slots found.</p>
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