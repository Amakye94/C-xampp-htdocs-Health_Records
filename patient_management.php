<?php
session_start();
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login1.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="patient_management.css">
    <title>Patient Management</title>
</head>
<body>
    <h1>Patient Management</h1>
    <div class="links">
        <a href="new_patient_registration.php" class="button">Add a New Patient</a>
        <a href="search_patient.php" class="button">Search and Manage Patients</a>
    </div>
</body>
</html>
