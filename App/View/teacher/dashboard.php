<?php 
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../auth/Login.php");
    exit();
}

// Include Database class
require_once __DIR__ . '/../../models/Database.php';

// Create Database instance and get connection
$database = new Database();
$conn = $database->conn;

$teacher_id = $_SESSION['teacher_id'];

/* ===============================
   FETCH UPCOMING APPOINTMENTS
================================ */
$sql = "SELECT s.name AS student_name, sl.date, sl.time, a.status, a.appointment_id
        FROM appointments a
        JOIN student s ON a.student_id = s.student_id
        JOIN slots sl ON a.slot_id = sl.slot_id
        WHERE sl.teacher_id = ? AND sl.date >= CURDATE()
        ORDER BY sl.date, sl.time";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Failed to prepare statement: " . $conn->error);
}

$stmt->bind_param("s", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

/* ===============================
   FETCH COUNTS FOR STATS CARDS
================================ */
$total_students_sql = "SELECT COUNT(DISTINCT student_id) AS total_students FROM appointments WHERE slot_id IN (SELECT slot_id FROM slots WHERE teacher_id = ?)";
$total_students_stmt = $conn->prepare($total_students_sql);
$total_students_stmt->bind_param("s", $teacher_id);
$total_students_stmt->execute();
$total_students_result = $total_students_stmt->get_result();
$total_students = $total_students_result->fetch_assoc()['total_students'] ?? 0;

$today_appointments_sql = "SELECT COUNT(*) AS today_appointments FROM appointments a
                           JOIN slots sl ON a.slot_id = sl.slot_id
                           WHERE sl.teacher_id = ? AND sl.date = CURDATE()";
$today_appointments_stmt = $conn->prepare($today_appointments_sql);
$today_appointments_stmt->bind_param("s", $teacher_id);
$today_appointments_stmt->execute();
$today_appointments_result = $today_appointments_stmt->get_result();
$today_appointments = $today_appointments_result->fetch_assoc()['today_appointments'] ?? 0;

$pending_requests_sql = "SELECT COUNT(*) AS pending_requests FROM appointments a
                         JOIN slots sl ON a.slot_id = sl.slot_id
                         WHERE sl.teacher_id = ? AND a.status = 'Pending'";
$pending_requests_stmt = $conn->prepare($pending_requests_sql);
$pending_requests_stmt->bind_param("s", $teacher_id);
$pending_requests_stmt->execute();
$pending_requests_result = $pending_requests_stmt->get_result();
$pending_requests = $pending_requests_result->fetch_assoc()['pending_requests'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - FacultyConnect</title>
    <style>
        /* Basic resets */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #4361ee;
            color: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }

        .navbar .logout-btn {
            background: #f72585;
            border: none;
            color: white;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
        }

        .navbar .logout-btn:hover {
            background: #d6136f;
        }

        /* Dashboard container */
        .dashboard {
            padding: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Greeting */
        .dashboard .welcome-box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .dashboard h2 {
            color: #333;
            margin-bottom: 10px;
            font-size: 24px;
        }

        .dashboard .welcome-box p {
            margin: 8px 0;
            color: #555;
        }

        .dashboard .welcome-box a {
            color: #4361ee;
            text-decoration: none;
            margin-right: 15px;
            font-weight: 500;
        }

        .dashboard .welcome-box a:hover {
            text-decoration: underline;
        }

        /* Stats Cards */
        .stats-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            flex: 1 1 200px;
            padding: 25px 20px;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            text-align: center;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            margin: 0;
            font-size: 28px;
            color: #2575fc;
            font-weight: 700;
        }

        .card p {
            margin: 10px 0 0 0;
            font-size: 16px;
            color: #555;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        table th {
            background-color: #4361ee;
            color: white;
            font-weight: 600;
        }

        table tr:hover {
            background-color: #f8f9fa;
        }

        .action-btn {
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            margin-right: 5px;
            transition: opacity 0.3s;
        }

        .approve { 
            background: #4cc9f0; 
            color: white; 
        }
        
        .decline { 
            background: #f72585; 
            color: white; 
        }
        
        .complete { 
            background: #43e97b; 
            color: white; 
        }

        .action-btn:hover {
            opacity: 0.85;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #777;
            font-style: italic;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stats-cards {
                flex-direction: column;
            }

            table {
                font-size: 14px;
            }
            
            table th, table td {
                padding: 10px;
            }
            
            .action-btn {
                display: block;
                margin-bottom: 5px;
                width: 100%;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <h1>FacultyConnect - Teacher Dashboard</h1>
    <form action="/springwtj/FacultyConnect/App/controllers/LogoutController.php" method="POST">
        <button type="submit" class="logout-btn">Logout</button>
    </form>
</div>

<!-- Dashboard Content -->
<div class="dashboard">
    <!-- Welcome Box -->
    <div class="welcome-box">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['teacher_name'] ?? 'Teacher'); ?> 🎓</h2>
        <p>Teacher ID: <?php echo htmlspecialchars($_SESSION['teacher_id'] ?? 'N/A'); ?></p>
        <p>
            <a href="manage_slots.php">Manage Slots</a> | 
            <a href="view_requests.php">View Appointment Requests</a> | 
            <a href="../auth/Login.php?logout=true">Logout</a>
        </p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-cards">
        <div class="card">
            <h3 id="total-students"><?php echo $total_students; ?></h3>
            <p>Total Students</p>
        </div>
        <div class="card">
            <h3 id="today-appointments"><?php echo $today_appointments; ?></h3>
            <p>Today's Appointments</p>
        </div>
        <div class="card">
            <h3 id="pending-requests"><?php echo $pending_requests; ?></h3>
            <p>Pending Requests</p>
        </div>
    </div>

    <!-- Upcoming Appointments Table -->
    <h2>Upcoming Appointments</h2>
    <?php if ($result && $result->num_rows > 0) { ?>
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($appointment = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appointment['student_name'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($appointment['date'] ?? $appointment['slot_date'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($appointment['time'] ?? $appointment['slot_time'] ?? 'N/A'); ?></td>
                        <td>
                            <span style="
                                padding: 4px 10px;
                                border-radius: 4px;
                                font-size: 12px;
                                font-weight: 600;
                                background-color: 
                                    <?php 
                                    $status = $appointment['status'] ?? $appointment['appointment_status'] ?? 'Pending';
                                    switch($status) {
                                        case 'Confirmed': echo '#4cc9f0'; break;
                                        case 'Completed': echo '#43e97b'; break;
                                        case 'Declined': echo '#f72585'; break;
                                        default: echo '#ffc107';
                                    }
                                    ?>;
                                color: white;
                            ">
                                <?php echo htmlspecialchars($status); ?>
                            </span>
                        </td>
                        <td>
                            <?php 
                            $status = $appointment['status'] ?? $appointment['appointment_status'] ?? 'Pending';
                            $appointment_id = $appointment['appointment_id'] ?? $appointment['id'] ?? 0;
                            ?>
                            
                            <?php if ($status === 'Pending') { ?>
                                <form method="POST" action="/springwtj/FacultyConnect/App/controllers/AppointmentController.php" style="display: inline;">
                                    <input type="hidden" name="appointment_id" value="<?php echo $appointment_id; ?>">
                                    <button type="submit" name="action" value="approve" class="action-btn approve">Approve</button>
                                    <button type="submit" name="action" value="decline" class="action-btn decline">Decline</button>
                                </form>
                            <?php } elseif ($status === 'Confirmed') { ?>
                                <span style="color: #4cc9f0; font-weight: 600;">Confirmed ✓</span>
                            <?php } elseif ($status === 'Completed') { ?>
                                <span style="color: #43e97b; font-weight: 600;">Completed ✓</span>
                            <?php } elseif ($status === 'Declined') { ?>
                                <span style="color: #f72585; font-weight: 600;">Declined ✗</span>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <div class="no-data">
            <p>No upcoming appointments found.</p>
        </div>
    <?php } ?>
</div>

<script>
// You can add JavaScript here to fetch real stats
document.addEventListener('DOMContentLoaded', function() {
    // Example of fetching stats via AJAX (you'll need to implement the backend)
    /*
    fetch('get_stats.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-students').textContent = data.totalStudents;
            document.getElementById('today-appointments').textContent = data.todayAppointments;
            document.getElementById('pending-requests').textContent = data.pendingRequests;
        });
    */
});
</script>

</body>
</html>