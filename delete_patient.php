<?php
session_start();
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.html");
    exit();
}

$host = "localhost";
$username = "root";
$password = "";
$database = "health_records_2024";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = intval($_GET['id']);
$sql = "DELETE FROM patients WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    header("Location: view_patient.php");
    exit();
} else {
    echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>
