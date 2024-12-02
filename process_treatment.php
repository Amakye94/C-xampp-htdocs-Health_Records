<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "health_records_2024");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from POST
$patient_name = htmlspecialchars($_POST['patient_name']);
$age = intval($_POST['age']);
$gender = htmlspecialchars($_POST['gender']);
$date_of_birth = htmlspecialchars($_POST['date_of_birth']);
$immunization = htmlspecialchars($_POST['immunization']);
$allergies = htmlspecialchars($_POST['allergies']);
$diagnosis = htmlspecialchars($_POST['diagnosis']);
$treatment_plan = htmlspecialchars($_POST['treatment_plan']);

// Save patient details
$stmt = $conn->prepare("INSERT INTO patients (name, age, gender, date_of_birth, immunization, allergies, diagnosis, treatment_plan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sissssss", $patient_name, $age, $gender, $date_of_birth, $immunization, $allergies, $diagnosis, $treatment_plan);

if ($stmt->execute()) {
    $patient_id = $stmt->insert_id; // Get the newly created patient ID

    // Handle file uploads
    if (!empty($_FILES['lab_tests']['name'][0])) {
        foreach ($_FILES['lab_tests']['tmp_name'] as $index => $tmp_name) {
            $file_name = basename($_FILES['lab_tests']['name'][$index]);
            $file_path = "uploads/" . $file_name;

            if (move_uploaded_file($tmp_name, $file_path)) {
                $file_stmt = $conn->prepare("INSERT INTO file_uploads (patient_id, file_path) VALUES (?, ?)");
                $file_stmt->bind_param("is", $patient_id, $file_path);
                $file_stmt->execute();
            }
        }
    }
    echo "Patient data saved successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>
