<?php
session_start();


if (!isset($_SESSION['id'])) {
    header("Location: login.php");
}



$servername = "localhost";
$username = "root";
$password = "";
$dbname = "health_records_2024";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate doctor selection
    if (empty($_POST['doctor_id']) || !is_numeric($_POST['doctor_id'])) {
        die("Invalid doctor selection. Please select a valid doctor.");
    }

    // Sanitize and validate inputs
    $doctor_id = intval($_POST['doctor_id']);
    $patient_name = trim($conn->real_escape_string($_POST['patient_name']));
    $age = intval($_POST['age']);
    $gender = trim($conn->real_escape_string($_POST['gender']));
    $date_of_birth = trim($conn->real_escape_string($_POST['date_of_birth']));
    $weight = intval($_POST['weight']);
    $blood_pressure = intval($_POST['blood_pressure']);
    $immunization = isset($_POST['immunization']) ? trim($conn->real_escape_string($_POST['immunization'])) : null;
    $allergies = isset($_POST['allergies']) ? trim($conn->real_escape_string($_POST['allergies'])) : null;

    // Insert patient data
    $sql = "INSERT INTO patients (patient_name, age, gender, date_of_birth, weight, blood_pressure, immunization, allergies, doctor_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sissdsssi", $patient_name, $age, $gender, $date_of_birth, $weight, $blood_pressure, $immunization, $allergies, $doctor_id);

        if ($stmt->execute()) {
            // Redirect to treatment page with the new patient ID
            $last_patient_id = $conn->insert_id;
            header("Location: treatment_page.php?patient_id=" . $last_patient_id);
            exit();
        } else {
            echo "Error inserting patient data: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing SQL statement: " . $conn->error;
    }
}

$conn->close();
?>
