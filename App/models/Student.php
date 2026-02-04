<?php
require_once __DIR__ . '/Database.php';

// Function to insert student
function insertStudent($data, $file) {
    // Create a Database instance and get the connection
    $database = new Database();
    $conn = $database->conn;

    $student_id = $conn->real_escape_string($data['student_id']);
    $name = $conn->real_escape_string($data['name']);
    $dob = $data['dob'];
    $gender = $data['gender'];
    $nationality = $conn->real_escape_string($data['nationality']);
    $email = $conn->real_escape_string($data['email']);
    $phone = $conn->real_escape_string($data['phone']);
    $address = $conn->real_escape_string($data['address']);
    $emergency_contact = $conn->real_escape_string($data['emergency_contact']);
    $department = $conn->real_escape_string($data['department']);
    $education = $data['education'];
    $guardian_name = $conn->real_escape_string($data['guardian_name']);
    $guardian_phone = $conn->real_escape_string($data['guardian_phone']);
    $password = password_hash($data['password'], PASSWORD_BCRYPT);

    // Handle file upload
    $dest_path = "";
    if (isset($file['student_picture']) && $file['student_picture']['error'] === 0) {
        $fileTmpPath = $file['student_picture']['tmp_name'];
        $fileName = $file['student_picture']['name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $newFileName = $student_id . '.' . $fileExt;
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $dest_path = $uploadDir . $newFileName;

        if (!move_uploaded_file($fileTmpPath, $dest_path)) {
            return ["error" => "Error uploading profile picture."];
        }
    }

    // Insert query
    $sql = "INSERT INTO student (
        student_id, name, dob, gender, nationality, email, phone, address, emergency_contact,
        department, education, guardian_name, guardian_phone, student_picture, password
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
    )";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL Error: " . $conn->error); // Debugging output
    }

    $stmt->bind_param(
        "sssssssssssssss",
        $student_id,
        $name,
        $dob,
        $gender,
        $nationality,
        $email,
        $phone,
        $address,
        $emergency_contact,
        $department,
        $education,
        $guardian_name,
        $guardian_phone,
        $dest_path,
        $password
    );

    if ($stmt->execute()) {
        return ["success" => "Student registered successfully!"];
    } else {
        return ["error" => "Database error: " . $stmt->error];
    }
}

// Function to fetch student by ID
function getStudentById($student_id) {
    // Create a Database instance and get the connection
    $database = new Database();
    $conn = $database->conn;

    $sql = "SELECT * FROM student WHERE student_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Failed to prepare statement: " . $conn->error); // Debugging output
    }

    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null; // Student not found
    }
}
?>
