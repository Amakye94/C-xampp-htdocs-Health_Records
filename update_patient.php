<?php
session_start();
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.html");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "health_records_2024";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['id'])) {
    echo "Invalid patient ID.";
    exit();
}

$patient_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM patients WHERE id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Patient not found.";
    exit();
}

$patient = $result->fetch_assoc();
$error_message = "";
$success_message = "";

// Handle form submission for updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_diagnosis = $conn->real_escape_string($_POST['diagnosis']);
    $immunization = $conn->real_escape_string($_POST['immunization']);
    $allergies = $conn->real_escape_string($_POST['allergies']);

    // Handle optional file uploads
    $lab_results = "";
    $radiology_image = "";

    if (isset($_FILES['lab_results']) && $_FILES['lab_results']['error'] === UPLOAD_ERR_OK) {
        $lab_results = "uploads/" . basename($_FILES['lab_results']['name']);
        move_uploaded_file($_FILES['lab_results']['tmp_name'], $lab_results);

        // Insert lab results into lab_results table
        $stmt_lab = $conn->prepare("INSERT INTO lab_results (patient_id, file_path) VALUES (?, ?)");
        $stmt_lab->bind_param("is", $patient_id, $lab_results);
        $stmt_lab->execute();
    }

    if (isset($_FILES['radiology_image']) && $_FILES['radiology_image']['error'] === UPLOAD_ERR_OK) {
        $radiology_image = "uploads/" . basename($_FILES['radiology_image']['name']);
        move_uploaded_file($_FILES['radiology_image']['tmp_name'], $radiology_image);

        // Insert radiology images into radiology_images table
        $stmt_radiology = $conn->prepare("INSERT INTO radiology_images (patient_id, file_path) VALUES (?, ?)");
        $stmt_radiology->bind_param("is", $patient_id, $radiology_image);
        $stmt_radiology->execute();
    }

    // Add new diagnosis entry
    $stmt_update = $conn->prepare("INSERT INTO diagnoses (patient_id, diagnosis, immunization, allergies, visit_date) VALUES (?, ?, ?, ?, NOW())");
    $stmt_update->bind_param("isss", $patient_id, $new_diagnosis, $immunization, $allergies);

    if ($stmt_update->execute()) {
        $success_message = "Patient details updated successfully!";
    } else {
        $error_message = "Error updating patient details.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Patient</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Update Patient: <?php echo htmlspecialchars($patient['patient_name']); ?></h1>
        
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="diagnosis" class="form-label">New Diagnosis:</label>
                <textarea id="diagnosis" name="diagnosis" class="form-control" placeholder="Add new diagnosis" required></textarea>
            </div>
            <div class="mb-3">
                <label for="immunization" class="form-label">Update Immunization:</label>
                <textarea id="immunization" name="immunization" class="form-control" placeholder="Update immunization details" required><?php echo htmlspecialchars($patient['immunization']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="allergies" class="form-label">Update Allergies:</label>
                <textarea id="allergies" name="allergies" class="form-control" placeholder="Update allergy details" required><?php echo htmlspecialchars($patient['allergies']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="lab_results" class="form-label">Lab Results (Optional):</label>
                <input type="file" id="lab_results" name="lab_results" class="form-control">
            </div>
            <div class="mb-3">
                <label for="radiology_image" class="form-label">Radiology Image (Optional):</label>
                <input type="file" id="radiology_image" name="radiology_image" class="form-control">
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success">Save Changes</button>
                <a href="patient_management.php" class="btn btn-secondary">Back to Patient Management</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
