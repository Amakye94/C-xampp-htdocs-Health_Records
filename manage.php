<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli("localhost", "root", "", "health_records_2024");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Action based on query parameters
$action = isset($_GET['action']) ? $_GET['action'] : 'list'; // Default action is 'list'
$patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : null;

// Handle CRUD operations
if ($action === 'list') {
    // Fetch all patients
    $search_query = isset($_POST['search']) ? $conn->real_escape_string($_POST['search']) : '';
    $sql = "SELECT * FROM patients";
    if (!empty($search_query)) {
        $sql .= " WHERE patient_name LIKE '%$search_query%'";
    }
    $result = $conn->query($sql);
} elseif ($action === 'view' && $patient_id) {
    // Fetch single patient and their treatments
    $patient_query = $conn->prepare("SELECT * FROM patients WHERE patient_id = ?");
    $patient_query->bind_param("i", $patient_id);
    $patient_query->execute();
    $patient_result = $patient_query->get_result();
    $patient = $patient_result->fetch_assoc();

    $treatment_query = $conn->prepare("SELECT * FROM diagnosis_treatment WHERE patient_id = ?");
    $treatment_query->bind_param("i", $patient_id);
    $treatment_query->execute();
    $treatments = $treatment_query->get_result();
} elseif ($action === 'update' && $patient_id) {
    // Handle update logic
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $patient_name = $conn->real_escape_string($_POST['patient_name']);
        $age = intval($_POST['age']);
        $gender = $conn->real_escape_string($_POST['gender']);
        $update_stmt = $conn->prepare("UPDATE patients SET patient_name = ?, age = ?, gender = ? WHERE patient_id = ?");
        $update_stmt->bind_param("sisi", $patient_name, $age, $gender, $patient_id);

        if ($update_stmt->execute()) {
            header("Location: patient_management.php?action=list");
            exit();
        } else {
            echo "Error updating patient: " . $update_stmt->error;
        }
    } else {
        $patient_query = $conn->prepare("SELECT * FROM patients WHERE patient_id = ?");
        $patient_query->bind_param("i", $patient_id);
        $patient_query->execute();
        $patient_result = $patient_query->get_result();
        $patient = $patient_result->fetch_assoc();
    }
} elseif ($action === 'delete' && $patient_id) {
    // Delete patient
    $delete_stmt = $conn->prepare("DELETE FROM patients WHERE patient_id = ?");
    $delete_stmt->bind_param("i", $patient_id);

    if ($delete_stmt->execute()) {
        header("Location: patient_management.php?action=list");
        exit();
    } else {
        echo "Error deleting patient: " . $delete_stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Patient Management</title>
</head>
<body>
    <?php if ($action === 'list'): ?>
        <h1>Manage Patients</h1>
        <!-- Search Form -->
        <form method="POST" action="patient_management.php?action=list">
            <input type="text" name="search" placeholder="Search by patient name">
            <button type="submit">Search</button>
        </form>
        <!-- Patient List -->
        <table border="1">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['age']); ?></td>
                            <td><?php echo htmlspecialchars($row['gender']); ?></td>
                            <td>
                                <a href="patient_management.php?action=view&patient_id=<?php echo $row['patient_id']; ?>">View</a> |
                                <a href="patient_management.php?action=update&patient_id=<?php echo $row['patient_id']; ?>">Update</a> |
                                <a href="patient_management.php?action=delete&patient_id=<?php echo $row['patient_id']; ?>" onclick="return confirm('Are you sure you want to delete this patient?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No patients found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php elseif ($action === 'view'): ?>
        <h1>View Patient: <?php echo htmlspecialchars($patient['patient_name']); ?></h1>
        <p><strong>Age:</strong> <?php echo htmlspecialchars($patient['age']); ?></p>
        <p><strong>Gender:</strong> <?php echo htmlspecialchars($patient['gender']); ?></p>
        <h2>Treatments</h2>
        <?php if ($treatments && $treatments->num_rows > 0): ?>
            <ul>
                <?php while ($treatment = $treatments->fetch_assoc()): ?>
                    <li>
                        <p><strong>Date:</strong> <?php echo htmlspecialchars($treatment['date_of_entry']); ?></p>
                        <p><strong>Diagnosis:</strong> <?php echo htmlspecialchars($treatment['diagnosis']); ?></p>
                        <p><strong>Medications:</strong> <?php echo htmlspecialchars($treatment['medications']); ?></p>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No treatments found.</p>
        <?php endif; ?>
    <?php elseif ($action === 'update'): ?>
        <h1>Update Patient</h1>
        <form method="POST">
            <label for="patient_name">Name:</label>
            <input type="text" id="patient_name" name="patient_name" value="<?php echo htmlspecialchars($patient['patient_name']); ?>" required>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($patient['age']); ?>" required>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Male" <?php echo ($patient['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo ($patient['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                <option value="Other" <?php echo ($patient['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
            </select>

            <button type="submit">Update</button>
        </form>
    <?php endif; ?>
</body>
</html>

<?php
if (isset($patient_query)) $patient_query->close();
if (isset($treatment_query)) $treatment_query->close();
$conn->close();
?>
