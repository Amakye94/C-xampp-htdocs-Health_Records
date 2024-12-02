<?php
$conn = new mysqli("localhost", "root", "", "health_records_2024");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

echo "Database connection successful!";
$conn->close();
?>
