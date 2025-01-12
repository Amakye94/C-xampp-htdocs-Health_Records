<?php
session_start();
echo "Session Data: ";
print_r($_SESSION); // Debug session data
if (!isset($_SESSION['doctor_id'])) {
    echo "Session doctor_id not found. Redirecting...";
    header("Location: login.html");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Details Overview</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <div class="container">
        <h1>Patient Details Overview</h1>
        <?php
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
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

       
        <p><strong>Name:</strong> <?php echo $patient_name; ?></p>
        <p><strong>Age:</strong> <?php echo $age; ?></p>
        <p><strong>Gender:</strong> <?php echo $gender; ?></p>
        <p><strong>Date of Birth:</strong> <?php echo $date_of_birth; ?></p>
        <p><strong>Immunization:</strong> <?php echo $immunization; ?></p>
        <p><strong>Allergies:</strong> <?php echo $allergies; ?></p>

    
        <form action="diagnosis.php" method="post">
         
            <input type="hidden" name="patient_name" value="<?php echo $patient_name; ?>">
            <input type="hidden" name="age" value="<?php echo $age; ?>">
            <input type="hidden" name="gender" value="<?php echo $gender; ?>">
            <input type="hidden" name="date_of_birth" value="<?php echo $date_of_birth; ?>">
            <input type="hidden" name="immunization" value="<?php echo htmlspecialchars($_POST['immunization']); ?>">
            <input type="hidden" name="allergies" value="<?php echo htmlspecialchars($_POST['allergies']); ?>">
            <button type="submit">Proceed to Diagnosis & Treatment</button>
        </form>
    </div>
</body>
</html>
