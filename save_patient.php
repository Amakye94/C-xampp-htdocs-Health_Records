<?php
session_start();
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.html");
    exit();
}

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "health_records_2024";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $patient_name = $conn->real_escape_string($_POST['patient_name']);
    $age = intval($_POST['age']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $date_of_birth = $conn->real_escape_string($_POST['date_of_birth']);
    $immunization = $conn->real_escape_string($_POST['immunization']);
    $allergies = $conn->real_escape_string($_POST['allergies']);
    $diagnosis = $conn->real_escape_string($_POST['diagnosis']);

    // Insert patient data into the `patients` table
    $stmt = $conn->prepare("INSERT INTO patients (patient_name, age, gender, date_of_birth, immunization, allergies, diagnosis) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisssss", $patient_name, $age, $gender, $date_of_birth, $immunization, $allergies, $diagnosis);

    if ($stmt->execute()) {
        $patient_id = $stmt->insert_id; // Get the newly inserted patient ID

        // File Upload Directories
        $upload_dir_radiology = "uploads/radiology/";
        $upload_dir_lab = "uploads/lab_results/";

        if (!is_dir($upload_dir_radiology)) mkdir($upload_dir_radiology, 0777, true);
        if (!is_dir($upload_dir_lab)) mkdir($upload_dir_lab, 0777, true);

        // Handle Radiology Image Upload
        if (isset($_FILES['radiology_image']) && $_FILES['radiology_image']['error'] === UPLOAD_ERR_OK) {
            $file_name = uniqid() . "_" . basename($_FILES['radiology_image']['name']);
            $file_path = $upload_dir_radiology . $file_name;
            
            // Validate file type (allow only image files)
            $allowed_types = ['image/jpeg', 'image/png'];
            if (in_array($_FILES['radiology_image']['type'], $allowed_types)) {
                if (move_uploaded_file($_FILES['radiology_image']['tmp_name'], $file_path)) {
                    $stmt_radiology = $conn->prepare("INSERT INTO radiology_images (patient_id, file_path) VALUES (?, ?)");
                    $stmt_radiology->bind_param("is", $patient_id, $file_path);
                    $stmt_radiology->execute();
                } else {
                    $_SESSION['error'] = "Failed to upload radiology image.";
                }
            } else {
                $_SESSION['error'] = "Invalid radiology image file type.";
            }
        }

        // Handle Lab Results Upload
        if (isset($_FILES['lab_results']) && $_FILES['lab_results']['error'] === UPLOAD_ERR_OK) {
            $file_name = uniqid() . "_" . basename($_FILES['lab_results']['name']);
            $file_path = $upload_dir_lab . $file_name;

            // Validate file type (allow only PDF, DOCX)
            $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            if (in_array($_FILES['lab_results']['type'], $allowed_types)) {
                if (move_uploaded_file($_FILES['lab_results']['tmp_name'], $file_path)) {
                    $stmt_lab = $conn->prepare("INSERT INTO lab_results (patient_id, file_path) VALUES (?, ?)");
                    $stmt_lab->bind_param("is", $patient_id, $file_path);
                    $stmt_lab->execute();
                } else {
                    $_SESSION['error'] = "Failed to upload lab result.";
                }
            } else {
                $_SESSION['error'] = "Invalid lab result file type.";
            }
        }

        // Redirect to dashboard with a success message
        $_SESSION['success'] = "Patient added successfully!";
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to add patient. Please try again.";
    }
}

$conn->close();
?>
