<?php
session_start();
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Data Collection</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-section {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center text-primary mb-4">Patient Data Collection</h1>
        
        <!-- Patient Data Form -->
        <form action="save_patient.php" method="post" enctype="multipart/form-data">
            <div class="form-section">
                <h2>Patient Details</h2>
                <div class="mb-3">
                    <label for="patient_name" class="form-label">Name:</label>
                    <input type="text" id="patient_name" name="patient_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="age" class="form-label">Age:</label>
                    <input type="number" id="age" name="age" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="gender" class="form-label">Gender:</label>
                    <select id="gender" name="gender" class="form-select" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="date_of_birth" class="form-label">Date of Birth:</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" required>
                </div>
            </div>

            <div class="form-section">
                <h2>Medical Information</h2>
                <div class="mb-3">
                    <label for="immunization" class="form-label">Immunization:</label>
                    <textarea id="immunization" name="immunization" class="form-control" placeholder="List immunization details" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="allergies" class="form-label">Allergies:</label>
                    <textarea id="allergies" name="allergies" class="form-control" placeholder="List any allergies" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="diagnosis" class="form-label">Diagnosis:</label>
                    <textarea id="diagnosis" name="diagnosis" class="form-control" placeholder="Enter Diagnosis" required></textarea>
                </div>
            </div>

            <div class="form-section">
                <h2>File Uploads</h2>
                <div class="mb-3">
                    <label for="lab_results" class="form-label">Lab Results:</label>
                    <input type="file" id="lab_results" name="lab_results" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="radiology_image" class="form-label">Radiology Image:</label>
                    <input type="file" id="radiology_image" name="radiology_image" class="form-control" accept="image/*" required>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
