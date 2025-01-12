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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']); // Patient ID to update
    $patient_name = $conn->real_escape_string($_POST['patient_name']);
    $age = intval($_POST['age']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $date_of_birth = $conn->real_escape_string($_POST['date_of_birth']);
    $immunization = $conn->real_escape_string($_POST['immunization']);
    $allergies = $conn->real_escape_string($_POST['allergies']);
    $diagnosis = $conn->real_escape_string($_POST['diagnosis']);

    $sql = "UPDATE patients SET 
            patient_name='$patient_name', 
            age=$age, 
            gender='$gender', 
            date_of_birth='$date_of_birth', 
            immunization='$immunization', 
            allergies='$allergies', 
            diagnosis='$diagnosis' 
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Patient updated successfully!";
        header("Location: patient_management.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM patients WHERE id=$id");
if ($result->num_rows === 0) {
    die("Patient not found.");
}
$patient = $result->fetch_assoc();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Edit Patient</h1>
        <form action="edit_patient.php" method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($patient['id']) ?>">
            <div class="mb-3">
                <label for="patient_name" class="form-label">Name:</label>
                <input type="text" id="patient_name" name="patient_name" class="form-control" value="<?= htmlspecialchars($patient['patient_name']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="age" class="form-label">Age:</label>
                <input type="number" id="age" name="age" class="form-control" value="<?= htmlspecialchars($patient['age']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="gender" class="form-label">Gender:</label>
                <select id="gender" name="gender" class="form-select" required>
                    <option value="Male" <?= $patient['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= $patient['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                    <option value="Other" <?= $patient['gender'] === 'Other' ? 'selected' : '' ?>>Other</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="date_of_birth" class="form-label">Date of Birth:</label>
                <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="<?= htmlspecialchars($patient['date_of_birth']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="immunization" class="form-label">Immunization:</label>
                <textarea id="immunization" name="immunization" class="form-control" required><?= htmlspecialchars($patient['immunization']) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="allergies" class="form-label">Allergies:</label>
                <textarea id="allergies" name="allergies" class="form-control" required><?= htmlspecialchars($patient['allergies']) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="diagnosis" class="form-label">Diagnosis:</label>
                <textarea id="diagnosis" name="diagnosis" class="form-control" required><?= htmlspecialchars($patient['diagnosis']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html>
