<?php
require_once __DIR__ . '/Database.php';

function registerTeacher($data, $file) {
    // Create a Database instance and get the connection
    $database = new Database();
    $conn = $database->conn;

    // File upload
    $uploadDir = __DIR__ . "/../uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = time() . "_" . basename($file['teacher_picture']['name']);
    $targetPath = $uploadDir . $fileName;

    if (!move_uploaded_file($file['teacher_picture']['tmp_name'], $targetPath)) {
        return false;
    }

    // Hash password
    $password = password_hash($data['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO teacher 
    (teacher_id, name, dob, gender, nationality, email, phone, department, qualifications, address, teacher_picture, password)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Check if the prepare() method failed
    if (!$stmt) {
        die("SQL Error: " . $conn->error); // Debugging output
    }

    $stmt->bind_param(
        "ssssssssssss",
        $data['teacher_id'],
        $data['name'],
        $data['dob'],
        $data['gender'],
        $data['nationality'],
        $data['email'],
        $data['phone'],
        $data['department'],
        $data['qualifications'],
        $data['address'],
        $fileName,
        $password
    );

    return $stmt->execute();
}

function getTeacherById($teacher_id) {
    // Create a Database instance and get the connection
    $database = new Database();
    $conn = $database->conn;

    $sql = "SELECT * FROM teacher WHERE teacher_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Failed to prepare statement: " . $conn->error); // Debugging output
    }

    $stmt->bind_param("s", $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null; // Teacher not found
    }
}

function getAppointmentsByTeacherId($teacher_id) {
    // Create a Database instance and get the connection
    $database = new Database();
    $conn = $database->conn;

    $sql = "SELECT a.date, a.time, s.name AS student_name, s.email AS student_email, s.phone AS student_phone
            FROM appointments a
            JOIN student s ON a.student_id = s.student_id
            WHERE a.teacher_id = ?
            ORDER BY a.date, a.time";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Failed to prepare statement: " . $conn->error); // Debugging output
    }

    $stmt->bind_param("s", $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $appointments = [];
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }

    return $appointments;
}
?>