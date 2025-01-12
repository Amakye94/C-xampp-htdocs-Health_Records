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

// Initialize search result variable
$search_result = null;

// Check if a new patient was added
$is_new_patient_added = isset($_GET['new_patient_added']) && $_GET['new_patient_added'] === 'true';

// Handle search request
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
    <title>Patient Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
        }
        .header img {
            height: 40px;
            margin-right: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 1.5rem;
        }
        .header nav a {
            margin-left: 15px;
            text-decoration: none;
            color: #007bff;
        }
        .container {
            flex: 1;
            max-width: 800px;
            margin: 30px auto;
            padding-bottom: 20px;
        }
        .footer {
            background-color: #fff;
            text-align: center;
            padding: 10px 0;
            border-top: 1px solid #ddd;
        }
        .thumbnail {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin: 5px;
        }
        .buttons a {
            margin: 5px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="d-flex align-items-center">
            <img src="icon.jpg" alt="Logo">
            <h1>Health Records</h1>
        </div>
        <nav>
            <a href="About_us.html">About Us</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <!-- Main Content -->
    <div class="container">
        <h2>Welcome, Dr. <?= htmlspecialchars($_SESSION['doctor_name'] ?? 'Unknown'); ?>!</h2>

        <!-- Success Alert for New Patient -->
        <?php if ($is_new_patient_added): ?>
            <div class="alert alert-success mt-3">Patient added successfully!</div>
        <?php endif; ?>

        <!-- Search Form -->
        <?php if (!$is_new_patient_added): ?>
            <form method="POST" action="dashboard.php" class="mt-4">
                <div class="input-group mb-3">
                    <input type="text" name="search_query" class="form-control" placeholder="Enter patient name or ID..." required>
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        <?php endif; ?>

        <!-- Display Search Results -->
        <?php if ($search_result && $search_result->num_rows > 0): ?>
            <?php $patient = $search_result->fetch_assoc(); ?>
            <div class="card mt-4">
                <div class="card-body">
                    <h4>Patient Details</h4>
                    <p><strong>Patient ID:</strong> <?= htmlspecialchars($patient['id']); ?></p>
                    <p><strong>Name:</strong> <?= htmlspecialchars($patient['patient_name']); ?></p>
                    <p><strong>Age:</strong> <?= htmlspecialchars($patient['age']); ?></p>
                    <p><strong>Gender:</strong> <?= htmlspecialchars($patient['gender']); ?></p>
                    <p><strong>Date of Birth:</strong> <?= htmlspecialchars($patient['date_of_birth']); ?></p>

                    <!-- Radiology Images -->
                    <h4 class="mt-4">Radiology Images</h4>
                    <div>
                        <?php
                        $stmt_images = $conn->prepare("SELECT file_path FROM radiology_images WHERE patient_id = ?");
                        $stmt_images->bind_param("i", $patient['id']);
                        $stmt_images->execute();
                        $result_images = $stmt_images->get_result();
                        if ($result_images->num_rows > 0) {
                            while ($image = $result_images->fetch_assoc()) {
                                echo '<a href="' . htmlspecialchars($image['file_path']) . '" target="_blank">
                                        <img src="' . htmlspecialchars($image['file_path']) . '" alt="Radiology Image" class="img-thumbnail thumbnail">
                                      </a>';
                            }
                        } else {
                            echo "<p>No radiology images uploaded.</p>";
                        }
                        ?>
                    </div>

                    <!-- Lab Results -->
                    <h4 class="mt-4">Lab Results</h4>
                    <div>
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
            </div>

            <!-- Update & Delete Buttons -->
            <div class="buttons mt-4">
                <a href="update_patient.php?id=<?= htmlspecialchars($patient['id']); ?>" class="btn btn-warning">Update Patient</a>
                <a href="delete_patient.php?id=<?= htmlspecialchars($patient['id']); ?>" class="btn btn-danger">Delete Patient</a>
            </div>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <div class="alert alert-danger mt-3">No patient found matching your query.</div>
        <?php endif; ?>

        <!-- Add New Patient Button -->
        <div class="buttons mt-4">
            <a href="new_patient_registration.php" class="btn btn-success">Add New Patient</a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        &copy; Health_Records 2024
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
