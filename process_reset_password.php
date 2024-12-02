<?php
$conn = new mysqli("localhost", "root", "", "health_records_2024");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $doctor_id = $_POST['doctor_id'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE doctors_registration SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $password, $doctor_id);

    if ($stmt->execute()) {
        echo "Password reset successfully!";
    } else {
        echo "Password reset failed.";
    }

    $stmt->close();
}

$conn->close();
?>
