<?php
// Database connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "facultyconnect";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to insert student
function insertStudent($data, $file) {
    global $conn;

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
        if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $dest_path = $uploadDir . $newFileName;

        if(!move_uploaded_file($fileTmpPath, $dest_path)) {
            return ["error" => "Error uploading profile picture."];
        }
    }

    // Insert query
    $sql = "INSERT INTO students (
        student_id, name, dob, gender, nationality, email, phone, address, emergency_contact,
        department, education, guardian_name, guardian_phone, student_picture, password
    ) VALUES (
        '$student_id', '$name', '$dob', '$gender', '$nationality', '$email', '$phone', '$address', '$emergency_contact',
        '$department', '$education', '$guardian_name', '$guardian_phone', '$dest_path', '$password'
    )";

    if ($conn->query($sql) === TRUE) {
        return ["success" => "Student registered successfully!"];
    } else {
        return ["error" => "Database error: " . $conn->error];
    }
}
?>
