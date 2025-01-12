<?php
session_start();

// Fetch patient_id from the URL
if (!isset($_GET['patient_id']) || !is_numeric($_GET['patient_id'])) {
    die("Invalid Patient ID.");
}

$patient_id = intval($_GET['patient_id']);

// Database connection
$conn = new mysqli("localhost", "root", "", "health_records_2024");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch patient details
$stmt = $conn->prepare("SELECT * FROM patients WHERE patient_id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();

if ($patient) {
    echo "<h1>Patient Details</h1>";
    echo "<p><strong>Name:</strong> " . htmlspecialchars($patient['patient_name']) . "</p>";
    echo "<p><strong>Age:</strong> " . htmlspecialchars($patient['age']) . "</p>";
    echo "<p><strong>Gender:</strong> " . htmlspecialchars($patient['gender']) . "</p>";
    echo "<p><strong>Date of Birth:</strong> " . htmlspecialchars($patient['date_of_birth']) . "</p>";

    // Fetch files associated with the patient
    $file_stmt = $conn->prepare("SELECT * FROM diagnosis_treatment WHERE patient_id = ?");
    $file_stmt->bind_param("i", $patient_id);
    $file_stmt->execute();
    $file_result = $file_stmt->get_result();

    echo "<h2>Uploaded Files</h2>";
    if ($file_result->num_rows > 0) {
        while ($file = $file_result->fetch_assoc()) {
            if (!empty($file['labs'])) {
                echo "<p>Lab: <a href='" . htmlspecialchars($file['labs']) . "' download>Download</a></p>";
            }
            if (!empty($file['radiology_image'])) {
                echo "<p>Radiology Image: <a href='" . htmlspecialchars($file['radiology_image']) . "' download>Download</a></p>";
            }
        }
    } else {
        echo "<p>No files uploaded.</p>";
    }

    echo "<a href='update_patient.php?patient_id=$patient_id'>Edit Details</a>";
} else {
    echo "<p>Patient not found.</p>";
}

$stmt->close();
$conn->close();
?>
