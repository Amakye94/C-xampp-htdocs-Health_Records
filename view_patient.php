<?php
$servername = "localhost"; // Your database server, typically "localhost"
$username = "root";        // Your database username
$password = "";            // Your database password
$dbname = "health_records_2024"; // Your database name

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}


if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.html");
    exit();
}

$search_result = null;

// Handle search
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search_query'])) {
    $search_query = $_POST['search_query'];
    $stmt = $conn->prepare("SELECT * FROM patients WHERE patient_name LIKE ? OR id = ?");
    $search_param = "%$search_query%";
    $stmt->bind_param("si", $search_param, $search_query);
    $stmt->execute();
    $search_result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Patient</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Search Patient</h1>
        <form method="POST" action="">
            <div class="input-group mb-3">
                <input type="text" name="search_query" class="form-control" placeholder="Enter patient name or ID..." required>
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        <?php if ($search_result && $search_result->num_rows > 0): ?>
            <?php $patient = $search_result->fetch_assoc(); ?>
            <h2 class="mt-4">Search Results</h2>
            <div class="card">
                <div class="card-body">
                    <h4>Patient Details</h4>
                    <p><strong>Patient ID:</strong> <?php echo htmlspecialchars($patient['id']); ?></p>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($patient['patient_name']); ?></p>
                    <p><strong>Age:</strong> <?php echo htmlspecialchars($patient['age']); ?></p>
                    <p><strong>Gender:</strong> <?php echo htmlspecialchars($patient['gender']); ?></p>
                    <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($patient['date_of_birth']); ?></p>

                    <h4 class="mt-4">Radiology Images</h4>
                    <?php
                    $stmt_images = $conn->prepare("SELECT file_path FROM radiology_images WHERE patient_id = ?");
                    $stmt_images->bind_param("i", $patient['id']);
                    $stmt_images->execute();
                    $result_images = $stmt_images->get_result();
                    if ($result_images->num_rows > 0) {
                        while ($image = $result_images->fetch_assoc()) {
                            echo '<img src="' . htmlspecialchars($image['file_path']) . '" alt="Radiology Image" class="img-thumbnail" style="width: 100px; height: 100px; margin-right: 10px;">';
                        }
                    } else {
                        echo "<p>No radiology images uploaded.</p>";
                    }
                    ?>

                    <h4 class="mt-4">Lab Results</h4>
                    <?php
                    $stmt_lab = $conn->prepare("SELECT file_path FROM lab_results WHERE patient_id = ?");
                    $stmt_lab->bind_param("i", $patient['id']);
                    $stmt_lab->execute();
                    $result_lab = $stmt_lab->get_result();
                    if ($result_lab->num_rows > 0) {
                        while ($lab = $result_lab->fetch_assoc()) {
                            echo '<a href="' . htmlspecialchars($lab['file_path']) . '" target="_blank" class="btn btn-link">View Lab Result</a>';
                        }
                    } else {
                        echo "<p>No lab results uploaded.</p>";
                    }
                    ?>
                </div>
            </div>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <p class="text-danger">No patient found matching your query.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
