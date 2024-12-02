<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "health_records_2024");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = htmlspecialchars($_POST['title']);
    $full_name = htmlspecialchars($_POST['full_name']);
    $staff_id = htmlspecialchars($_POST['staff_id']);
    $email = htmlspecialchars($_POST['email']);
    $gender = htmlspecialchars($_POST['gender']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $password = $_POST['password'];

    // Validate required fields
    if (empty($title) || empty($full_name) || empty($staff_id) || empty($email) || empty($gender) || empty($telephone) || empty($password)) {
        die("All fields are required.");
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert query with debugging
    $stmt = $conn->prepare("INSERT INTO doctors_registration (title, full_name, staff_id, email, gender, telephone, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssssss", $title, $full_name, $staff_id, $email, $gender, $telephone, $hashed_password);

    if ($stmt->execute()) {
        echo "Registration successful! Redirecting to login page...";
        header("Location: login.html");
        exit;
    } else {
        // Output detailed error message
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
