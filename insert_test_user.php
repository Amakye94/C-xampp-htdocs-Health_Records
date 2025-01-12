<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli("localhost", "root", "", "health_records_2024");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert test user
$password = password_hash('testpassword', PASSWORD_BCRYPT); // Hash the password
$sql = "INSERT INTO doctors_registration_table (title, full_name, staff_id, email, gender, telephone, password) 
        VALUES ('Dr.', 'Test User', '12345', 'testuser@example.com', 'Male', '1234567890', '$password')";

if ($conn->query($sql) === TRUE) {
    echo "Test user created successfully!";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
