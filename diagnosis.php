<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnosis & Treatment</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <div class="container">
        <h1>Diagnosis & Treatment</h1>
        <?php
        // Ensure the request method is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve and sanitize form data
            $patient_name = htmlspecialchars($_POST['patient_name'] ?? 'N/A');
            $age = htmlspecialchars($_POST['age'] ?? 'N/A');
            $gender = htmlspecialchars($_POST['gender'] ?? 'N/A');
            $date_of_birth = htmlspecialchars($_POST['date_of_birth'] ?? 'N/A');
            $immunization = nl2br(htmlspecialchars($_POST['immunization'] ?? 'N/A'));
            $allergies = nl2br(htmlspecialchars($_POST['allergies'] ?? 'N/A'));
        } else {
            echo "<p>Error: No data received. Please go back to the previous step and fill out the form.</p>";
            exit;
        }
        ?>

        <!-- Display patient details for confirmation -->
        <h2>Patient Details</h2>
        <p><strong>Name:</strong> <?php echo $patient_name; ?></p>
        <p><strong>Age:</strong> <?php echo $age; ?></p>
        <p><strong>Gender:</strong> <?php echo $gender; ?></p>
        <p><strong>Date of Birth:</strong> <?php echo $date_of_birth; ?></p>
        <p><strong>Immunization:</strong> <?php echo $immunization; ?></p>
        <p><strong>Allergies:</strong> <?php echo $allergies; ?></p>

        <!-- Diagnosis and treatment form -->
        <form action="process_treatment.php" method="post" enctype="multipart/form-data">
            <h2>Diagnosis</h2>
            <div class="form-group">
                <label for="diagnosis">Diagnosis:</label>
                <textarea id="diagnosis" name="diagnosis" required></textarea>
            </div>

            <h2>Treatment</h2>
            <div class="form-group">
                <label for="treatment_plan">Treatment Plan:</label>
                <textarea id="treatment_plan" name="treatment_plan" required></textarea>
            </div>

            <h2>File Uploads</h2>
            <div class="form-group">
                <label for="lab_tests">Upload Lab Tests:</label>
                <input type="file" id="lab_tests" name="lab_tests[]" multiple>
            </div>
            <div class="form-group">
                <label for="radiology_images">Upload Radiology Images:</label>
                <input type="file" id="radiology_images" name="radiology_images[]" multiple>
            </div>

            <!-- Pass patient data as hidden inputs -->
            <input type="hidden" name="patient_name" value="<?php echo $patient_name; ?>">
            <input type="hidden" name="age" value="<?php echo $age; ?>">
            <input type="hidden" name="gender" value="<?php echo $gender; ?>">
            <input type="hidden" name="date_of_birth" value="<?php echo $date_of_birth; ?>">
            <input type="hidden" name="immunization" value="<?php echo htmlspecialchars($_POST['immunization']); ?>">
            <input type="hidden" name="allergies" value="<?php echo htmlspecialchars($_POST['allergies']); ?>">
            <button type="submit">Submit Diagnosis & Treatment</button>
        </form>
    </div>
</body>
</html>
