<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="container">
        <?php
        session_start();
        if (!isset($_SESSION['doctor_id'])) {
            header("Location: login1.html");
            exit();
        }

        echo "<h1>Welcome, Doctor</h1>";
        //echo "<p>You are logged in as " . htmlspecialchars($_SESSION['email']) . ".</p>"; ->
        ?>
        <p><a href="patient_management.php" class="button">Go to Patient Management Page</a></p>
    </div>
</body>
</html>

