<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Default password for XAMPP is empty
$database = "health_records_2024";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $diagnosis = htmlspecialchars($_POST['diagnosis']);
    $treatment_plan = htmlspecialchars($_POST['treatment_plan']);
    $billing = htmlspecialchars($_POST['billing']);

    // Save diagnosis and treatment details
    $stmt = $conn->prepare("INSERT INTO patient_records (patient_id, diagnosis, treatment_plan, billing) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $patient_id, $diagnosis, $treatment_plan, $billing);
    $stmt->execute();
    $stmt->close();

    // Redirect to the patient details page to view all data
    header("Location: diagnosis.php?patient_id=$patient_id");
    exit();
}

// Fetch patient details for display
if (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];
    $stmt = $conn->prepare("SELECT * FROM patients WHERE id = ?");
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $patient = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Fetch patient files
$radiology_images = $conn->query("SELECT * FROM radiology_images WHERE patient_id = $patient_id");
$lab_results = $conn->query("SELECT * FROM lab_results WHERE patient_id = $patient_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Details</title>
</head>
<body>
    <h1>Patient Details</h1>

    <?php if ($patient): ?>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($patient['patient_name']); ?></p>
        <p><strong>Age:</strong> <?php echo htmlspecialchars($patient['age']); ?></p>
        <p><strong>Gender:</strong> <?php echo htmlspecialchars($patient['gender']); ?></p>
        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($patient['date_of_birth']); ?></p>
        <p><strong>Immunization:</strong> <?php echo htmlspecialchars($patient['immunization']); ?></p>
        <p><strong>Allergies:</strong> <?php echo htmlspecialchars($patient['allergies']); ?></p>
    <?php else: ?>
        <p>Patient details not found.</p>
    <?php endif; ?>

    <h2>Add New Details</h2>
    <form action="diagnosis.php" method="post">
        <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">
        <label for="diagnosis">Diagnosis:</label>
        <textarea id="diagnosis" name="diagnosis" required></textarea>
        <label for="treatment_plan">Treatment Plan:</label>
        <textarea id="treatment_plan" name="treatment_plan" required></textarea>
        <label for="billing">Billing:</label>
        <input type="number" id="billing" name="billing" required>
        <button type="submit">Submit</button>
    </form>

    <h2>Uploaded Files</h2>
    <h3>Radiology Images</h3>
    <?php while ($row = $radiology_images->fetch_assoc()): ?>
        <img src="<?php echo htmlspecialchars($row['file_path']); ?>" alt="Radiology Image" style="width:200px;"><br>
    <?php endwhile; ?>

    <h3>Lab Results</h3>
    <?php while ($row = $lab_results->fetch_assoc()): ?>
        <a href="<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank">View Lab Result</a><br>
    <?php endwhile; ?>
</body>
</html>
